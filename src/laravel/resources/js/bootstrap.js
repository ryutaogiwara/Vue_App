import { getCookieValue } from './util'

window.axios = require('axios')

// Ajaxリクエストであることを示すヘッダーを付与する
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

window.axios.interceptors.request.use(config => {
  // クッキーからトークンを取り出してヘッダーに添付する
  config.headers['X-XSRF-TOKEN'] = getCookieValue('XSRF-TOKEN')

  return config
})

// インターセプター(バリデーションエラー)
window.axios.interceptors.response.use(
  // 第一引数には成功時の処理
  response => response,
  // 第二引数には失敗時の処理 ここではエラー内容を配列で返す
  error => error.response || error
)
