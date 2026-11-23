const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 */

mix.js('resources/js/lucide-pearl.js', 'public/js')
    .js('resources/js/lucide-admin.js', 'public/js')
    .js('resources/js/welcome-pearl.js', 'public/js')
    .js('resources/js/radial-orbit-wheel.js', 'public/js')
    .postCss('resources/css/pearl.css', 'public/css', [
        tailwindcss('./tailwind.config.js'),
    ])
    .webpackConfig({
        output: {
            chunkFilename: 'js/[name].js',
        },
    });
