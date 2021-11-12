const path = require('path');
let mix = require('laravel-mix');
var { CleanWebpackPlugin } = require('clean-webpack-plugin');
var FaviconsWebpackPlugin = require('favicons-webpack-plugin');
var StylelintPlugin = require('stylelint-webpack-plugin');

require("@tinypixelco/laravel-mix-wp-blocks");

if (mix.inProduction()) {
  mix.webpackConfig({
    plugins: [
      new CleanWebpackPlugin()
    ]
  })
}

mix.block('src/scripts/admin/settings/index.js', 'js/theme-admin-settings.js')
  .sass('src/styles/admin/theme-settings.scss', 'theme-admin-settings.css');


mix.setPublicPath('assets');
mix.setResourceRoot('./');
mix.autoload({
    jquery: ['$', 'window.jQuery', 'jQuery'], // more than one
  });


mix.browserSync({
  host: 'starter.localhost',
  open: 'external',
  proxy: {
    target: 'https://starter.localhost'
  },
  port: 3000,
  https: {
    key: path.resolve(process.env.HOME, 'Work/_tools/traefik-proxy/devcerts/starter.localhost+1-key.pem'),
    cert: path.resolve(process.env.HOME, 'Work/_tools/traefik-proxy/devcerts/starter.localhost+1.pem')
  },
  files: [
    "src/styles/**/*.scss",
    "src/scripts/",
    "src/blocks/",
    "template-parts/",
    "inc/*.php",
    "inc/**/*.php",
    "woocommerce/**/*.php",
    "classes/",
    "*.php"
  ]
});
  


