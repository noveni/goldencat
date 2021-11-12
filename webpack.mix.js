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

mix.js('src/scripts/theme/index.js', 'js/theme.js')
  .sass('src/styles/theme.scss', 'style.css')
  .block('src/scripts/editor/index.js', 'js/editor.js')
  .sass('src/styles/editor.scss', 'editor.css')
  .sass('src/styles/admin-editor.scss', 'admin-editor.css')
  .js('src/scripts/admin/index.js', 'js/admin.js')
  .sass('src/styles/admin.scss', 'admin.css')
  .block('src/scripts/admin/settings/index.js', 'js/theme-admin-settings.js')
  .sass('src/styles/admin/theme-settings.scss', 'theme-admin-settings.css')
  .sass('src/styles/woocommerce.scss', 'woocommerce.css')
  .js('src/scripts/admin/taxonomy-meta-field/index.js', 'js/admin/taxonomy-meta-field.js')
  .options({
    fileLoaderDirs: {
      images: 'img',
      fonts: 'fonts'
    }
  });


mix.setPublicPath('assets');
mix.setResourceRoot('./');
mix.autoload({
    jquery: ['$', 'window.jQuery', 'jQuery'], // more than one
  });

mix.sourceMaps(false, 'source-map');

mix.webpackConfig({
  plugins: [
    new StylelintPlugin({
      context: path.resolve(__dirname, 'src/styles'),
    }),
    new FaviconsWebpackPlugin({
      logo: path.resolve(__dirname, './src/favicon.png'),
      prefix: mix.inProduction() ? path.join('wp-content/themes', path.basename(__dirname), 'assets/icons') : './icons',
      outputPath: './icons/',
      inject: false,
      favicons: {
        appName: 'Ecran Noir',
        appDescription: 'Site Vitrine',
        developerName: null,
        developerURL: null, // prevent retrieving from the nearest package.json
        background: '#1D1D1B',
        theme_color: '#1D1D1B',
        icons: {
          coast: false,
          appleStartup: false,
          yandex: false,
          firefox: false
        }
      }
    })
  ]
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
  


