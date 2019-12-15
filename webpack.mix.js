const mix = require('laravel-mix')
const path = require('path')

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

mix.js('resources/js/app.js', 'public/js')
  .sass('resources/sass/app.scss', 'public/css')
  .disableNotifications()
  .webpackConfig({
    resolve: {
      symlinks: false,
      alias: {
        '@': path.resolve(__dirname, 'resources/js/'),
        '@c': path.resolve(__dirname, 'resources/js/components'),
        '@s': path.resolve(__dirname, 'resources/sass'),
      },
    },
  })
  .browserSync({
    files: [
      'resources/views/**/*.php',
      `${Config.publicPath || 'public'}/**/*.(js|css)`,
    ],
    proxy: process.env.APP_URL,
  })
