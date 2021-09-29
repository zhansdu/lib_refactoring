const mix = require('laravel-mix');

mix
    .ts('resources/js/main.ts', 'public/js')
    .vue({ version: 3 });
mix.sass('resources/sass/app.scss', 'public/css');