var mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.copy('node_modules/jquery/dist/jquery.js', 'public/js/vendor/jquery.js');
mix.copy('node_modules/popper.js/dist/umd/popper.js', 'public/js/vendor/popper.js');
mix.copy('node_modules/bootstrap/dist/js/bootstrap.js', 'public/js/vendor/bootstrap.js');
mix.copy('node_modules/select2/dist/js/select2.js', 'public/js/vendor/select2.js');
mix.copy('node_modules/chart.js/dist/Chart.js', 'public/js/vendor/chartjs.js');

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');
