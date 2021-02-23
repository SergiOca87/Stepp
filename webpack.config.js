// webpack v4
const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const VueLoaderPlugin = require('vue-loader/lib/plugin');

module.exports = {
	entry: { main: './src/index.js' },
	output: {
		path: path.resolve(__dirname, 'build'),
		filename: 'properties.js'
	},
	watch: true,
	watchOptions: {
		poll: true,
		ignored: /node_modules/
	},
	module: {
		rules: [
			{
				test: /\.vue$/,
				loader: 'vue-loader'
			},
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader'
				}
			},
			{
				test: /\.(gif|png|jpe?g|svg)$/i,
				use: [
					'file-loader',
					{
						loader: 'image-webpack-loader',
						options: {
							bypassOnDebug: true
						}
					}
				]
			},
			{
				test: /\.s[c|a]ss$/,
				use: ['vue-style-loader', 'css-loader', 'sass-loader']
			}
		]
	},
	plugins: [
		new VueLoaderPlugin(),
		new CleanWebpackPlugin('build', {}),
		new MiniCssExtractPlugin({
			filename: 'style.css'
		}),
		new BrowserSyncPlugin({
			proxy: 'http://lvh.me',
			files: [
				{
					match: ['**/*.php', '**/*.css', '**/*.js'],
					fn: function(event, file) {
						if (event === 'change') {
							const bs = require('browser-sync').get(
								'bs-webpack-plugin'
							);
							bs.reload();
						}
					}
				}
			]
		})
	]
};
