<?php

namespace App\Themes\ValPress\DefaultTheme;

class MainDemoData
{

    public static $images = [
        'unsplash-7.jpg',
        'unsplash-8.jpg',
        'unsplash-9.jpg',
        'unsplash-10.jpg',
        'unsplash-11.jpg',
        'unsplash-12.jpg',
        'unsplash-13.jpg',
        'unsplash-14.jpg',
        'unsplash-15.jpg',
        'unsplash-16.jpg',
        'unsplash-17.jpg',
        'unsplash-18.jpg',
        'unsplash-19.jpg',
        'unsplash-20.jpg',
        'unsplash-21.jpg',
        'unsplash-22.jpg',
        'unsplash-23.jpg',
    ];

    public static $categories = [
        'General',
        'Core',
        'Travelling',
        'Landscapes',
        'Nature',
    ];

    public static function getPosts()
    {
        return [
            //#! Category: General
            'General' => [
                [
                    'title' => "Morning News",
                    'content' => "<p>No, no, not the girl, I'm pretty sure she's already taken, I mean the morning news that you get when you wake up and discover that it's a beautiful day and it's only getting better.</p>" . self::loremIpsumText(),
                    'featured_image' => 'unsplash-7.jpg',
                    'images' => false,
                    'tags' => [ 'Morning', 'News' ],
                    'comments' => false,
                ],
            ],

            //#! Category: Core
            'Core' => [
                [
                    'title' => "Commands",
                    'content' => '<p>Since Laravel is not WordPress, sometimes you might need to run various commands and if your website is on a shared server without access to a <strong>SSH</strong> console then things get ugly. For that you now have a beautiful <strong>Commands</strong> section under your <strong>Administration Dashboard</strong> that helps you execute the most common commands. Yay!&nbsp;</p><p><img style="border-style: none; margin: 20px; float: left;" src="IMG_PLACEHOLDER_1" alt="" width="400" height="211" /> As the Lorem Ipsum says, Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><p style="text-align: left;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',
                    'featured_image' => 'unsplash-13.jpg',
                    'images' => [
                        'IMG_PLACEHOLDER_1' => '__commands.png',
                    ],
                    'tags' => [ 'Commands', 'Dashboard', 'Admin', 'Core' ],
                    'comments' => false,
                ],
                [
                    'title' => "Themes & Plugins",
                    'content' => "<p>Say yes, 'cause they're here! We also have a Marketplace that will provide you with themes and plugins to fit your needs! No longer reinvent the wheel every time you have a new project!</p>" . self::loremIpsumText(),
                    'featured_image' => 'unsplash-12.jpg',
                    'images' => false,
                    'tags' => [ 'Core', 'Themes', 'Plugins', ],
                    'comments' => false,
                ],
                [
                    'title' => "Custom Actions And Filters",
                    'content' => "<p>We've got that too! You can super charge your themes and plugins with actions and filters just like you would do it in any WordPress websites.</p>" . self::loremIpsumText(),
                    'featured_image' => 'unsplash-11.jpg',
                    'images' => false,
                    'tags' => [ 'Core', 'Actions', 'Filters' ],
                    'comments' => false,
                ],
                [
                    'title' => "Custom Post Types? Yes!",
                    'content' => "<p>We've got you covered if you want Custom Post Types. No coding necessary, everything is visual and intuitive.</p>" . '<p><img style="border-style: none; margin: 20px; float: left;" src="IMG_PLACEHOLDER_1" alt="" width="400" height="211" /> As the Lorem Ipsum says, Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><p style="text-align: left;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',
                    'featured_image' => 'unsplash-10.jpg',
                    'images' => [
                        'IMG_PLACEHOLDER_1' => '__post-types.png',
                    ],
                    'tags' => [ 'Core', 'Posts', 'Custom Post Types', ],
                    'comments' => false,
                ],
                [
                    'title' => "Creating Posts? Easy!",
                    'content' => "<p>Pretty simple, click a button, write some text and you're ready to go!</p>" . '<p><img style="border-style: none; margin: 20px; float: left;" src="IMG_PLACEHOLDER_1" alt="" width="400" height="211" /> As the Lorem Ipsum says, Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><p style="text-align: left;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',
                    'featured_image' => 'unsplash-9.jpg',
                    'images' => [
                        'IMG_PLACEHOLDER_1' => '__posts.png',
                    ],
                    'tags' => [ 'Core', 'Posts', 'Editing' ],
                    'comments' => false,
                ],
            ],

        ];
    }

    public static function getPages()
    {
        return [
            [
                'title' => 'Home',
                'content' => '',
            ],
            [
                'title' => "Blog",
                'content' => '',
            ],
            [
                'title' => "About",
                'content' => '<p>Hello! This is the about page. Here you can write something meaningful about you rather than the default lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',
            ],
            [
                'title' => "Contact us",
                'content' => '<p>Hello! This is the contact page. Here you can write something meaningful about your company and maybe add a contact form rather than the default lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',
            ],
            [
                'title' => "Credits",
                'content' => "<p>We're using images from " . '<a href="https://unsplash.com/" target="_blank">Unsplash</a> and we would like to express our thanks to the following for their images we use in the main demo:</p><ul><li><a href="https://unsplash.com/@domenicoloia" target="_blank">@domenicoloia</a></li>
<li><a href="https://unsplash.com/@trueagency" target="_blank">@trueagency</a></li>
<li><a href="https://unsplash.com/@rhondak" target="_blank">@rhondak</a></li>
<li><a href="https://unsplash.com/@edwardhowellphotography" target="_blank">@edwardhowellphotography</a></li>
<li><a href="https://unsplash.com/@xps" target="_blank">@xps</a></li>
<li><a href="https://unsplash.com/@clarktibbs" target="_blank">@clarktibbs</a></li>
<li><a href="https://unsplash.com/@kellysikkema" target="_blank">@kellysikkema</a></li>
<li><a href="https://unsplash.com/@nickkarvounis" target="_blank">@nickkarvounis</a></li>
<li><a href="https://unsplash.com/@andres11hernandez" target="_blank">@andres11hernandez</a></li>
<li><a href="https://unsplash.com/@thoughtcatalog" target="_blank">@thoughtcatalog</a></li>
<li><a href="https://unsplash.com/@headwayio" target="_blank">@headwayio</a></li>
<li><a href="https://unsplash.com/@heftiba" target="_blank">@heftiba</a></li>
<li><a href="https://unsplash.com/@sloppyperfectionist" target="_blank">@sloppyperfectionist</a></li>
<li><a href="https://unsplash.com/@hannahjoshua" target="_blank">@hannahjoshua</a></li>
<li><a href="https://unsplash.com/@athulca" target="_blank">@athulca</a></li>
<li><a href="https://unsplash.com/@tobiasrehbein" target="_blank">@tobiasrehbein</a></li>
<li><a href="https://unsplash.com/@luca42" target="_blank">@luca42</a></li>
<li><a href="https://unsplash.com/@jeremythomasphoto" target="_blank">@jeremythomasphoto</a></li>
<li><a href="https://unsplash.com/@shotbycerqueira" target="_blank">@shotbycerqueira</a></li>
<li><a href="https://unsplash.com/@helvetiica" target="_blank">@helvetiica</a></li>
<li><a href="https://unsplash.com/@heinrich_boll" target="_blank">@heinrich_boll</a></li>
<li><a href="https://unsplash.com/@medhatdawoud" target="_blank">@medhatdawoud</a></li>
<li><a href="https://unsplash.com/@arnelhasanovic" target="_blank">@arnelhasanovic</a></li>
<li><a href="https://unsplash.com/@kellysikkema" target="_blank">@kellysikkema</a></li>
<li><a href="https://unsplash.com/@speedoshots" target="_blank">@speedoshots</a></li>
<li><a href="https://unsplash.com/@codypboard" target="_blank">@codypboard</a></li>
<li><a href="https://unsplash.com/@jamesthethomas5" target="_blank">@jamesthethomas5</a></li>
<li><a href="https://unsplash.com/@janisfasel" target="_blank">@janisfasel</a></li>
<li><a href="https://unsplash.com/@farallon" target="_blank">@farallon</a></li>
<li><a href="https://unsplash.com/@bethhopes" target="_blank">@bethhopes</a></li>
<li><a href="https://unsplash.com/@marekpiwnicki" target="_blank">@marekpiwnicki</a></li>
<li><a href="https://unsplash.com/@eadesstudio" target="_blank">@eadesstudio</a></li>
<li><a href="https://unsplash.com/@eadesstudio" target="_blank">@eadesstudio</a></li>
<li><a href="https://unsplash.com/@appzgo" target="_blank">@appzgo</a></li>
<li><a href="https://unsplash.com/@luciandachman" target="_blank">@luciandachman</a></li>
<li><a href="https://unsplash.com/@asoggetti" target="_blank">@asoggetti</a></li>
</ul>',
            ],
        ];
    }

    public static function loremIpsumText()
    {
        $html = '<p>As the Lorem Ipsum says, Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
        $html .= '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
        $html .= '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
        $html .= '<blockquote><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></blockquote>';
        $html .= '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
        $html .= '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';

        return $html;
    }
}

