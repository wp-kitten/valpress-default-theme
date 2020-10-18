<?php

namespace Database\Seeders;

use App\Models\Options;
use App\Models\Post;
use App\Models\Settings;
use Illuminate\Database\Seeder;

class CpdtSettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = new Settings();

        //#! Ensure post type "post" allows: categories, tags & comments
        $optionNames = [
            //#! request var -> option name
            'allow_categories' => "post_type_post_allow_categories",
            'allow_comments' => "post_type_post_allow_comments",
            'allow_tags' => "post_type_post_allow_tags",
        ];
        foreach ( $optionNames as $requestVar => $optionName ) {
            $opt = Options::where( 'name', $optionName )->first();
            if ( $opt && $opt->id ) {
                $opt->value = '1';
                $opt->update();
            }
            else {
                Options::create( [
                    'name' => $optionName,
                    'value' => '1',
                ] );
            }
        }

        //#! Update reading settings
        $settings->updateSetting( 'show_on_front', 'page' );
        $settings->updateSetting( 'page_on_front', Post::where( 'slug', 'home' )->first()->id );
        $settings->updateSetting( 'blog_page', Post::where( 'slug', 'blog' )->first()->id );
        $settings->updateSetting( 'posts_per_page', 6 );
    }
}
