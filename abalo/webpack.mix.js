const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js').js('resources/js/articlesVue.js', 'public/js')
    .vue()
    .css('resources/css/app.css', 'public/css');

//mix.copyDirectory('resources/content', 'public/content');
//mix.copyDirectory('resources/fonts', 'public/fonts');
//mix.copyDirectory('resources/lang', 'public/lang');