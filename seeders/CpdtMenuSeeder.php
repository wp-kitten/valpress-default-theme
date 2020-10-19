<?php

namespace Database\Seeders;

use App\Helpers\CPML;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuItemType;
use App\Models\Options;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CpdtMenuSeeder extends Seeder
{
    public function run()
    {
        $menuItemType = MenuItemType::where( 'name', 'page' )->first();
        $languageID = CPML::getDefaultLanguageID();
        $options = new Options();

        $data = [
            'Main Menu' => [
                //#! Page slugs
                'blog',
                'about',
                'contact-us',
            ],
            'Footer Menu' => [
                'about',
                'contact-us',
                'credits',
            ],
        ];

        foreach ( $data as $menuName => $pageSlugs ) {
            //#! Create the menu
            $menu = Menu::create( [
                'name' => $menuName,
                'slug' => Str::slug( $menuName ),
                'language_id' => $languageID,
            ] );
            //#! Create menu items
            if ( $menu && $menu->id ) {
                foreach ( $pageSlugs as $i => $pageSlug ) {
                    $page = Post::where( 'slug', $pageSlug )->first();
                    if ( $page && $page->id ) {
                        MenuItem::create( [
                            'menu_order' => $i,
                            'ref_item_id' => $page->id,
                            'menu_id' => $menu->id,
                            'menu_item_type_id' => $menuItemType->id,
                        ] );
                    }
                }

                //#! Set the menu's display option
                if ( 'Main Menu' == $menuName ) {
                    $options->addOption( "menu-{$menu->id}-display-as", 'dropdown' );
                }
                else {
                    $options->addOption( "menu-{$menu->id}-display-as", 'basic' );
                }
            }
        }
    }
}
