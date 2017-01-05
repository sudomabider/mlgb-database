const elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(mix => {

  mix.sass('app.scss');

  mix.webpack('app.js');

  mix.version([
    'public/css/app.css', 'public/js/app.js'
  ]);

  mix.copy('bower_components/components-font-awesome/fonts', 'public/build/fonts');
});