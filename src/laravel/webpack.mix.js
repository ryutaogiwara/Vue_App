const mix = require('laravel-mix')

mix.browserSync({
    proxy: 'nginx_vue_app',
    open: false
})
    .js('resources/js/app.js', 'public/js')
    .version()
    // 引数は読み込みもと,展開先のパス
    .sass('resources/sass/app.scss', 'public/css')

