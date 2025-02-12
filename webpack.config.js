const path = require('path');
const defaults = require('@wordpress/scripts/config/webpack.config.js');
const webpack = require('webpack');

module.exports = {
  ...defaults,
  entry: {
    'app': path.resolve(process.cwd(), 'resources/scripts', 'app.ts'),
  },
  output: {
    filename: '[name].js',
    path: path.resolve(process.cwd(), 'resources/build'),
  },
  module: {
    ...defaults.module,
    rules: [
      ...defaults.module.rules,
      {
        test: /\.tsx?$/,
        use: [
          {
            loader: 'ts-loader',
            options: {
              configFile: 'tsconfig.json',
              transpileOnly: true,
            }
          }
        ]
      }
    ]
  },
  plugins: [
    new webpack.DefinePlugin({}),
    ...defaults.plugins
  ],
  resolve: {
    extensions: ['.ts', ...(defaults.resolve ? defaults.resolve.extensions || ['.js'] : [])]
  }
};
