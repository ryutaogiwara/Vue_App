const mix = require('laravel-mix')

mix.browserSync({
    proxy: 'nginx_vue_app',
    open: false
})
    .js('resources/js/app.js', 'public/js')
    .version()
