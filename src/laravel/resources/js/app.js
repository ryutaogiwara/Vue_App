// 先頭に固定
import './bootstrap'

import Vue from 'vue'

// ルーティングの定義をインポートする
import router from './router'

// ルートコンポーネントをインポートする
import App from './App.vue'

// ストアデータのインポート
import store from './store'


// currentUserは非同期処理で行われる
// 非同期処理をawaitさせるにはasyncメソッド内で行う必要があるため一度関数にまとめる
const createApp = async () => {
  // vueインスタンス生成前にcurrentUserを発火させる
  // storeインポート後なのでdispachメソッドが使える
  await store.dispatch('auth/currentUser')

  // インスタンスの作成
  new Vue({
    el: '#app',
    router, // ルーティングの定義を読み込む
    store,
    components: { App }, // ルートコンポーネントの使用を宣言する
    template: '<App />' // ルートコンポーネントを描画する
  })
}


// 実行
createApp()

