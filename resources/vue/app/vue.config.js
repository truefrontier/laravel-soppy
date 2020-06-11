let path = require('path');
const AREA = 'app';

module.exports = {
  // devServer: {
  //   proxy: 'http://my-project.test',
  // },

  outputDir: `../../../public/assets/${AREA}`,

  publicPath: process.env.NODE_ENV === 'production' ? `/assets/${AREA}/` : '/',

  indexPath:
    process.env.NODE_ENV === 'production'
      ? `../../../resources/views/${AREA}.blade.php`
      : 'index.html',

  // If you want to run multiple areas with the same postcss config,
  // then put your postcss.config.js in /resources/vue/ and comment the lines below

  // css: {
  //   loaderOptions: {
  //     postcss: {
  //       config: {
  //         path: '../postcss.config.js',
  //       },
  //     },
  //   },
  // },

  // An optional alias for easet access other directories in /resources/vue
  // ie. a shared directory between multiple vue-cli projects

  // configureWebpack: {
  //   resolve: {
  //     alias: {
  //       '@@': path.join(__dirname, '../'), // the /resources/vue/ directory
  //     },
  //   },
  // },
};
