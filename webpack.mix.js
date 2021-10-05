const mix = require('laravel-mix');
const path = require('path');

mix.webpackConfig({
    module: {
        rules: [{
            test: /\.jsx?$/,
            exclude: /node_modules(?!\/foundation-sites)|bower_components/,
            use: [{
                loader: 'babel-loader',
                options: Config.babel()
            }]
        }]
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources'),
            '@user': path.resolve(__dirname, 'resources/js/user'),
            '@admin': path.resolve(__dirname, 'resources/js/admin')
        }
    },
})

mix
    .ts('resources/js/admin/main.ts', 'public/js/admin.js')
    .vue({ version: 3 })
    .sass('resources/sass/admin/main.scss', 'public/css/admin.css');

mix
    .ts('resources/js/user/main.ts', 'public/js/user.js')
    .vue({ version: 3 })
    .sass('resources/sass/user/main.scss', 'public/css/user.css');