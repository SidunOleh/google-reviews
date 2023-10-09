const path = require('path')
const { VueLoaderPlugin } = require('vue-loader')

module.exports = {
    mode: 'development',
    entry: './src/source/js/app.js',
    output: {
        path: path.resolve(__dirname, 'src/assets/admin/js'),
        filename: 'bundle.js',
    },
    module: {
        rules: [{
            test: /\.vue$/,
            loader: 'vue-loader',
        }, {
            test: /\.css$/i,
            use: ["style-loader", "css-loader", ],
        }, ],
    },
    plugins: [
        new VueLoaderPlugin(),
    ],
}