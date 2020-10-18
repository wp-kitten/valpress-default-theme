<?php

namespace Database\Seeders;

use App\Helpers\CPML;
use App\Helpers\ImageHelper;
use App\Helpers\MediaHelper;
use App\Helpers\MetaFields;
use App\Models\Category;
use App\Models\CommentStatuses;
use App\Models\MediaFile;
use App\Models\Post;
use App\Models\PostComments;
use App\Models\PostMeta;
use App\Models\PostStatus;
use App\Models\PostType;
use App\Models\Tag;
use App\Themes\ContentPressDefaultTheme\MainDemoData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CpdtContentSeeder extends Seeder
{
    /**
     * @throws \Exception
     */
    public function run()
    {
        $postMetaModel = new PostMeta();
        $postTypePost = PostType::where( 'name', 'post' )->first();
        $postTypePage = PostType::where( 'name', 'page' )->first();
        $postStatus = PostStatus::where( 'name', 'publish' )->first();
        $languageID = CPML::getDefaultLanguageID();
        $userID = cp_get_current_user_id();

        $mediaHelper = new MediaHelper();
        $uploadsDir = $mediaHelper->getUploadsDir();
        $uploadSubDirs = date( 'Y' ) . '/' . date( 'n' );

        $theme = cp_get_current_theme();
        $themeDirPath = $theme->getDirPath();

        try {
            $_uploadDirPath = path_combine($uploadsDir, $uploadSubDirs);
            if( ! File::isDirectory($_uploadDirPath)){
                File::makeDirectory($_uploadDirPath, 755, true, true);
            }
            unset($_uploadDirPath);
        }
        catch(\Exception $e){
            return;
        }


        //#! Create Pages
        $pages = MainDemoData::getPages();
        if ( !empty( $pages ) ) {
            foreach ( $pages as $page ) {
                $title = $page[ 'title' ];
                $content = $page[ 'content' ];

                $pageID = $this->__getPostID( $title, $content, $postStatus, $postTypePage, $languageID, $userID );
                if ( empty( $pageID ) ) {
                    throw new \Exception( __( "cpdt::m. The page :page could not be created.", [ 'page' => $title ] ) );
                }

                //#! Set templates for Home & Blog
                if ( 'home' == strtolower( $title ) ) {
                    $meta = MetaFields::getInstance( $postMetaModel, 'post_id', $pageID, 'template', $languageID );
                    if ( $meta ) {
                        $meta->meta_value = 'templates.home';
                        $meta->update();
                    }
                    else {
                        MetaFields::add( $postMetaModel, 'post_id', $pageID, 'template', 'templates.home', $languageID );
                    }
                }
                elseif ( 'blog' == strtolower( $title ) ) {
                    $meta = MetaFields::getInstance( $postMetaModel, 'post_id', $pageID, 'template', $languageID );
                    if ( $meta ) {
                        $meta->meta_value = 'templates.blog';
                        $meta->update();
                    }
                    else {
                        MetaFields::add( $postMetaModel, 'post_id', $pageID, 'template', 'templates.blog', $languageID );
                    }
                }
            }
        }

        //#! Create Posts, Categories, Tags, Comments
        $posts = MainDemoData::getPosts();
        if ( !empty( $posts ) ) {
            foreach ( $posts as $post ) {
                $title = $post[ 'title' ];
                $content = $post[ 'content' ];
                $featured_image = $post[ 'featured_image' ];
                $images = $post[ 'images' ];
                $category = $post[ 'category' ];
                $tags = $post[ 'tags' ];
                $comments = $post[ 'comments' ];

                //#! [::1] Set content images
                //#! Must run before inserting content so we can replace image placeholders
                if ( !empty( $images ) ) {
                    //#! Upload images
                    foreach ( $images as $placeholderSrc => $imageName ) {
                        $fileSourcePath = path_combine( $themeDirPath, 'assets/img', $imageName );
                        $fileDestPath = path_combine( $uploadsDir, $uploadSubDirs, $imageName );
                        $imgInfo = $this->__uploadImage( $fileSourcePath, $fileDestPath, $uploadSubDirs, $languageID, $mediaHelper );
                        if ( !empty( $imgInfo[ 'url' ] ) ) {
                            $content = str_replace( $placeholderSrc, $imgInfo[ 'url' ], $content );
                        }
                    }
                }

                //#! [::2] Create the post
                $postID = $this->__getPostID( $title, $content, $postStatus, $postTypePost, $languageID, $userID );
                if ( empty( $postID ) ) {
                    throw new \Exception( __( "cpdt::m. The post :post could not be created.", [ 'post' => $title ] ) );
                }
                $thePost = Post::find( $postID );

                //#! Update the excerpt
                $thePost->excerpt = substr( wp_strip_all_tags( $content ), 0, 190 );
                $thePost->update();

                //#! Create category
                $catID = $this->__getCategoryID( $category, $postTypePost, $languageID );
                if ( $catID ) {
                    $thePost->categories()->detach();
                    $thePost->categories()->attach( [ $catID ] );
                }

                //#! Create tags
                if ( $tags ) {
                    $tagIds = [];
                    foreach ( $tags as $tag ) {
                        $tagID = $this->__getTagID( $tag, $postTypePost, $languageID );
                        if ( $tagID ) {
                            array_push( $tagIds, $tagID );
                        }
                    }
                    if ( !empty( $tagIds ) ) {
                        $thePost->tags()->detach();
                        $thePost->tags()->attach( $tagIds );
                    }
                }

                //#! Create comments
                if ( $comments ) {
                    $commentStatusID = CommentStatuses::where( 'name', 'approve' )->first()->id;
                    foreach ( $comments as $comment ) {
                        $content = $comment[ 'content' ];
                        $replies = $comment[ 'replies' ];

                        //#! Create the comment
                        $theComment = $this->__getComment( $content, $userID, $thePost->id, $commentStatusID );
                        if ( $theComment && !empty( $replies ) ) {
                            foreach ( $replies as $reply ) {
                                $this->__getComment( $reply, $userID, $thePost->id, $commentStatusID, $theComment->id );
                            }
                        }
                    }

                    //#! Disable comments
                    if ( $meta = MetaFields::getInstance( $postMetaModel, 'post_id', $thePost->id, '_comments_enabled', $languageID ) ) {
                        $meta->meta_value = false;
                        $meta->update();
                    }
                    else {
                        MetaFields::add( $postMetaModel, 'post_id', $thePost->id, '_comments_enabled', false, $languageID );
                    }
                }

                //#! Set featured image
                if ( !empty( $featured_image ) ) {
                    $fileSourcePath = path_combine( $themeDirPath, 'assets/img', $featured_image );
                    $fileDestPath = path_combine( $uploadsDir, $uploadSubDirs, $featured_image );
                    $imgInfo = $this->__uploadImage( $fileSourcePath, $fileDestPath, $uploadSubDirs, $languageID, $mediaHelper );
                    if ( !empty( $imgInfo[ 'id' ] ) ) {
                        $postMeta = PostMeta::where( 'post_id', $thePost->id )
                            ->where( 'language_id', $languageID )
                            ->where( 'meta_name', '_post_image' )
                            ->first();
                        if ( $postMeta ) {
                            $postMeta->meta_value = $imgInfo[ 'id' ];
                            $postMeta->update();
                        }
                        else {
                            PostMeta::create( [
                                'post_id' => $thePost->id,
                                'language_id' => $languageID,
                                'meta_name' => '_post_image',
                                'meta_value' => $imgInfo[ 'id' ],
                            ] );
                        }
                    }
                }
            }
        }
    }

    //#! Creates the category (if it doesn't exist) and retrieve its ID
    private function __getCategoryID( string $name, PostType $postType, int $languageID )
    {
        if ( empty( $name ) ) {
            return 0;
        }

        $name = ucfirst( $name );
        $slug = Str::slug( $name );
        $theCategory = Category::where( function ( $query ) use ( $name, $slug ) {
            $query->where( 'name', $name )
                ->orWhere( 'slug', $slug );
        } )
            ->where( 'category_id', null )
            ->where( 'post_type_id', $postType->id )
            ->where( 'language_id', $languageID )
            ->where( 'translated_category_id', null )
            ->first();
        if ( !$theCategory || !$theCategory->id ) {
            $theCategory = Category::create( [
                'name' => $name,
                'slug' => $slug,
                'post_type_id' => $postType->id,
                'language_id' => $languageID,
            ] );
        }
        return ( $theCategory && $theCategory->id ? $theCategory->id : 0 );
    }

    //#! Creates the tag (if it doesn't exist) and retrieve its ID
    private function __getTagID( string $name, PostType $postType, int $languageID )
    {
        if ( empty( $name ) ) {
            return 0;
        }

        $name = ucfirst( $name );
        $slug = Str::slug( $name );
        $theTag = Tag::where( function ( $query ) use ( $name, $slug ) {
            $query->where( 'name', $name )
                ->orWhere( 'slug', $slug );
        } )
            ->where( 'post_type_id', $postType->id )
            ->where( 'language_id', $languageID )
            ->where( 'translated_tag_id', null )
            ->first();
        if ( !$theTag || !$theTag->id ) {
            $theTag = Tag::create( [
                'name' => $name,
                'slug' => $slug,
                'post_type_id' => $postType->id,
                'language_id' => $languageID,
            ] );
        }
        return ( $theTag && $theTag->id ? $theTag->id : 0 );
    }

    //#! Creates the page/post (if it doesn't exist) and retrieve its ID
    private function __getPostID( string $title, string $content, PostStatus $postStatus, PostType $postType, int $languageID, int $userID )
    {
        $title = ucfirst( $title );
        $slug = Str::slug( $title );

        $entry = Post::where( function ( $query ) use ( $title, $slug ) {
            $query->where( 'title', $title )
                ->orWhere( 'slug', $slug );
        } )
            ->where( 'post_type_id', $postType->id )
            ->where( 'language_id', $languageID )
            ->where( 'translated_post_id', null )
            ->first();

        if ( !$entry || !$entry->id ) {
            $excerpt = substr( wp_strip_all_tags( $content ), 0, 190 );
            $entry = Post::create( [
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'excerpt' => $excerpt,
                'post_status_id' => $postStatus->id,
                'post_type_id' => $postType->id,
                'language_id' => $languageID,
                'user_id' => $userID,
            ] );
        }
        return ( $entry && $entry->id ? $entry->id : 0 );
    }

    private function __getComment( string $content, int $userID, int $postID, int $commentStatusID, $parentCommentID = null )
    {
        return PostComments::create( [
            'content' => $content,
            'user_id' => $userID,
            'post_id' => $postID,
            'comment_status_id' => $commentStatusID,
            'comment_id' => $parentCommentID,
        ] );
    }

    private function __uploadImage( string $sourceImageFilePath, string $fileDestPath, string $subdirs, int $languageID, MediaHelper $mediaHelper )
    {
        $result = [
            'id' => 0,
            'url' => '',
        ];
        $extension = strtolower( pathinfo( $sourceImageFilePath, PATHINFO_EXTENSION ) );
        try {
            File::copy( $sourceImageFilePath, $fileDestPath );

            $fileName = basename( $sourceImageFilePath );
            $mediaFile = MediaFile::create( [
                'slug' => Str::slug( $fileName ),
                'path' => $subdirs . '/' . $fileName,
                'language_id' => $languageID,
            ] );

            if ( !$mediaFile ) {
                File::delete( $fileDestPath );
                return $result;
            }

            //#! Resize image
            $mimeType = File::mimeType( $fileDestPath );
            if ( $mimeType && in_array( $mimeType, [ 'image/jpeg', 'image/png', 'image/gif' ] ) ) {
                ImageHelper::resizeImage( $fileDestPath, $mediaFile );
            }
            $result[ 'id' ] = $mediaFile->id;
            $result[ 'url' ] = $mediaHelper->getUrl( $fileDestPath );
        }
        catch ( \Exception $e ) {
            logger( 'Error: ' . $e->getMessage() );
            return $result;
        }
        return $result;
    }
}
