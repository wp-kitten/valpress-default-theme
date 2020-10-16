<?php
define( 'DEFAULT_THEME_DIR_PATH', untrailingslashit( wp_normalize_path( dirname( __FILE__ ) ) ) );
define( 'DEFAULT_THEME_DIR_NAME', basename( dirname( __FILE__ ) ) );

require_once( DEFAULT_THEME_DIR_PATH . '/src/ThemeHelper.php' );
require_once( DEFAULT_THEME_DIR_PATH . '/controllers/DefaultThemeController.php' );
require_once( DEFAULT_THEME_DIR_PATH . '/theme-hooks.php' );

cp_add_image_size( '55', [ 'w' => 55 ] );
cp_add_image_size( 'w210', [ 'w' => 210 ] );
cp_add_image_size( 'w289', [ 'w' => 289 ] );
cp_add_image_size( 'w350', [ 'w' => 350 ] );
cp_add_image_size( 'w510', [ 'w' => 510 ] );
cp_add_image_size( 'w690', [ 'w' => 690 ] );
