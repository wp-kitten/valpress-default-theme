<?php

/*
 * Add custom routes or override existent ones
 */

use App\Http\Controllers\DefaultThemeController;
use Illuminate\Support\Facades\Route;

/*
 * Web routes
 */

//#! Already loaded in the appropriate context
//#! @see ./resources/routes/web.php

//#! Override the default route
Route::get( "/", [ DefaultThemeController::class, 'index' ] )->name( "app.home" );

//#! Required in CategoriesWalker.. if not defined admin/post-type/category fails to load
Route::get( "categories/{slug}", [ DefaultThemeController::class, 'category' ] )->name( "blog.category" );

Route::get( "tags/{slug}", [ DefaultThemeController::class, 'tag' ] )->name( "blog.tag" );

Route::get( "author/{id}", [ DefaultThemeController::class, 'author' ] )->name( "blog.author" );

Route::any( "search", [ DefaultThemeController::class, 'search' ] )->name( "blog.search" );

//#! Special entries
Route::get( "lang/{code}", [ DefaultThemeController::class, 'lang' ] )->name( "app.switch_language" );

Route::post( 'comment/{post_id}', [ DefaultThemeController::class, '__submitComment' ] )->name( 'app.submit_comment' );
Route::post( 'comment/delete/{id}', [ DefaultThemeController::class, '__deleteComment' ] )->name( 'app.delete_comment' );

/*
 * Theme options routes
 */
$vpdtBaseRoute = 'admin.themes.valpress-default-theme-options';
Route::get( 'admin/themes/valpress-default-theme/options', [ DefaultThemeController::class, 'themeOptionsPageView' ] )
    ->middleware( [ 'web', 'auth', 'active_user', 'under_maintenance' ] )
    ->name( $vpdtBaseRoute );
Route::post( 'admin/themes/valpress-default-theme/options/save', [ DefaultThemeController::class, 'themeOptionsSave' ] )
    ->middleware( [ 'web', 'auth', 'active_user', 'under_maintenance' ] )
    ->name( "{$vpdtBaseRoute}.save" );
Route::post( 'admin/themes/valpress-default-theme/options/install-main-demo', [ DefaultThemeController::class, 'installMainDemo' ] )
    ->middleware( [ 'web', 'auth', 'active_user', 'under_maintenance' ] )
    ->name( "{$vpdtBaseRoute}.install-main-demo" );
unset( $vpdtBaseRoute );
