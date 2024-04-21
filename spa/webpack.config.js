const webpack = require('webpack')
const Encore = require('@symfony/webpack-encore')
const HtmlWebpackPlugin = require('html-webpack-plugin')

Encore
    .setOutputPath('public/')
    .enableSassLoader()
    .setPublicPath('/')
    .cleanupOutputBeforeBuild()
    .addEntry('app', './src/app.js')
    .enablePreactPreset()
    .enableSingleRuntimeChunk()
    .addPlugin(new HtmlWebpackPlugin({template: 'src/index.ejs', alwaysWriteToDisk: true}))
    .addPlugin(new webpack.DefinePlugin({
        'ENV_API_ENDPOINT': JSON.stringify(process.env.API_ENDPOINT),
    }))

module.exports = Encore.getWebpackConfig()