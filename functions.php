<?php

use App\Helpers\DirAutoloader;
use App\Http\Controllers\SiteController;

define( 'DEFAULT_THEME_DIR_PATH', untrailingslashit( wp_normalize_path( dirname( __FILE__ ) ) ) );
define( 'DEFAULT_THEME_DIR_NAME', basename( dirname( __FILE__ ) ) );

/**
 * The name of the option storing whether the main demo has been installed or not
 * @var string
 */
define( 'DEFAULT_THEME_MAIN_DEMO_INSTALLED_OPT_NAME', 'vpdt_main_demo_installed' );

/**
 * The name of the option storing whether the main demo is being installed or not
 * @var string
 */
define( 'DEFAULT_THEME_MAIN_DEMO_INSTALLING_OPT_NAME', 'vpdt_main_demo_installing' );

DirAutoloader::registerPath( DEFAULT_THEME_DIR_PATH . '/controllers' );
DirAutoloader::registerPath( DEFAULT_THEME_DIR_PATH . '/seeders' );
DirAutoloader::registerPath( DEFAULT_THEME_DIR_PATH . '/src' );

require_once( DEFAULT_THEME_DIR_PATH . '/theme-hooks.php' );

vp_add_image_size( '55', [ 'w' => 55 ] );
vp_add_image_size( 'w210', [ 'w' => 210 ] );
vp_add_image_size( 'w289', [ 'w' => 289 ] );
vp_add_image_size( 'w350', [ 'w' => 350 ] );
vp_add_image_size( 'w510', [ 'w' => 510 ] );
vp_add_image_size( 'w690', [ 'w' => 690 ] );
vp_add_image_size( 'w825', [ 'w' => 825 ] );

add_action( 'valpress/frontend/before-render/single-post-view', function ( string $slug, SiteController $siteController ) {
    if ( !vp_is_user_logged_in() ) {
        var_dump( 'NOT LOGGED IN' );
        return redirect( '404' );
    }
}, 10, 2 );

add_action( 'valpress/frontend/before-render/homepage', function ( SiteController $siteController ) {
    if ( !vp_is_user_logged_in() ) {
        var_dump( 'NOT LOGGED IN' );
    }
} );

add_filter( 'valpress/admin/http/verify-ssl', function () {
    return false;
} );
