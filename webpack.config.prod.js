const path = require('path');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const WebpackManifestPlugin = require('webpack-manifest-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const ImageminWebpackPlugin = require('imagemin-webpack-plugin').default;

module.exports = {
  mode: 'production',
  context: path.resolve(__dirname, 'resources'),
  entry: {
    index: './js/index.js',
    products: './js/products.js',
    show: './js/show.js',
    login: './js/login.js',
    register: './js/register.js',
    cart: './js/cart.js',
    admin: './js/admin.js',
    adminGames: './js/adminGames',
    adminNewGames: './js/adminNewGames',
    adminViewGame: './js/adminViewGame',
    adminEditGame: './js/adminEditGame',
    checkout: './js/checkout.js',
    'checkout.stripe': './js/checkout/stripe.js',
    'checkout.success': './js/checkout/success.js',
    'checkout.braintree': './js/checkout/braintree.js'
  },
  output: {
    filename: 'js/[name].[contenthash].js',
    path: path.resolve(__dirname, 'public'),
    publicPath: '/'
  },
  plugins: [
    new WebpackManifestPlugin({
      fileName: path.resolve(__dirname, 'manifest.json'),
      filter: file => {
        const ext = path.extname(file.name).slice(1);
        return ['js', 'css'].includes(ext);
      },
      map: file => {
        const ext = path.extname(file.name).slice(1);

        const newFile = {
          ...file
        };

        if (ext === 'js') {
          newFile.name = `${ext}/${file.name}`;
        } else if (ext === 'css') {
          newFile.name = `css/style.css`;
        }

        return newFile;
      }
    }),
    new MiniCssExtractPlugin({
      filename: 'css/style.[contenthash].css'
    }),
    new CopyWebpackPlugin([
      {
        from: 'img',
        to: 'img'
      }
    ]),
    new ImageminWebpackPlugin({ test: /\.(jpe?g|png|gif)$/i }),
    new CleanWebpackPlugin(['public/js', 'public/img', 'public/css'])
  ],
  optimization: {
    minimizer: [new UglifyJsPlugin({}), new OptimizeCSSAssetsPlugin({})],
    splitChunks: {
      cacheGroups: {
        commons: {
          // Split all node_modules packages included in the bundles out into a separate vendors.js file.
          // This could result in a large vendors.js bundle so it is recommended to dynamically load some modules only when required.
          // https://webpack.js.org/plugins/split-chunks-plugin/#split-chunks-example-2
          test: /[\\/]node_modules[\\/]/,
          name: 'vendors',
          chunks: 'all'
        }
      }
    }
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        // Do not process node_module files with babel, as it slows down bundling and they should be already processed anyway.
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env']
          }
        }
      },
      {
        test: /\.(jpe?g|png|gif|svg)$/,
        use: [
          {
            // Extract images and dump into img folder.
            loader: 'file-loader',
            options: {
              name: '[path][name].[ext]',
              context: path.resolve(__dirname, 'resources')
            }
          },
          {
            // Optimize images.
            loader: 'image-webpack-loader'
          }
        ]
      },
      {
        test: /\.s[ac]ss$/,
        use: [
          {
            // Extract CSS out of JS bundle
            loader: MiniCssExtractPlugin.loader
          },
          {
            // Parse CSS file.
            loader: 'css-loader',
            options: {
              importLoaders: 3
            }
          },
          {
            // Requires a postcss.config.js file.
            loader: 'postcss-loader'
          },
          {
            // Allows relative paths to be used in scss partials, instead of having to write them relative to the main style.scss which imports them.
            // Requires a sourceMap from all previous loaders in the chain.
            loader: 'resolve-url-loader'
          },
          {
            loader: 'sass-loader',
            options: {
              // sourceMap required for resolve-url-loader
              sourceMap: true
            }
          }
        ]
      }
    ]
  }
};
