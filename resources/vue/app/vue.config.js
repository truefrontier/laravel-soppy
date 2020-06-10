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

  // configureWebpack: {
  //   resolve: {
  //     alias: {
  //       '@@': path.join(__dirname, '../../'), // the /resources directory
  //     },
  //   },
  // },
};
