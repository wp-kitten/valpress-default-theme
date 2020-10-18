<?php

use App\Themes\ContentPressDefaultTheme\ThemeHelper;
use Illuminate\Support\Facades\Artisan;

$themeHelper = new ThemeHelper();

Artisan::command( 'cpdt_install_main_demo', function () use ( $themeHelper ) {
    if ( $themeHelper->mainDemoInstalling() ) {
        return 0;
    }

    //#! Execute seeders
    /*
     * - CategorySeeder
     * - TagSeeder
     * - PostSeeder
     * - MenuSeeder
     * - SettingsSeeder
     */

    //#! Update reading options

    return 1;
} )->describe( 'Install Main Demo' );
