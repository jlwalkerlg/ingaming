const path = require('path');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const WebpackManifestPlugin = require('webpack-manifest-plugin');

module.exports = {
  mode: 'development',
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
    filename: chunkData =>
      'js/' + chunkData.chunk.name.split('.').join('/') + '.js',
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
    new CopyWebpackPlugin([
      {
        from: 'img',
        to: 'img'
      }
    ]),
    new CleanWebpackPlugin(['public/js', 'public/img', 'public/css'])
  ],
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
          }
        ]
      },
      {
        test: /\.s[ac]ss$/,
        use: [
          {
            // Inject CSS into DOM from JS bundle.
            loader: 'style-loader'
          },
          {
            // Parse CSS file.
            loader: 'css-loader',
            options: {
              sourceMap: true,
              importLoaders: 3
            }
          },
          {
            // Requires a postcss.config.js file.
            loader: 'postcss-loader',
            options: {
              sourceMap: true
            }
          },
          {
            // Allows relative paths to be used in scss partials, instead of having to write them relative to the main style.scss which imports them.
            // Requires a sourceMap from all previous loaders in the chain.
            loader: 'resolve-url-loader',
            options: {
              sourceMap: true
            }
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
