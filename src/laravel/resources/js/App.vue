<template>
  <div>
    <header>
      <!-- script部でテンプレートを呼び出す -->
      <Navbar />
    </header>

    <main>
      <div class="container">
        <Message />
        <RouterView />
      </div>
    </main>

    <footer>
      <Footer />
    </footer>
  </div>
</template>

<script>

import Message from './components/Message.vue'
import Navbar from './components/Navbar'
import Footer from './components/Footer'
import { UNAUTHORIZED, INTERNAL_SERVER_ERROR } from './util'


export default {
  components: {
    Message,
    Navbar,
    Footer
  },

  // 算出プロパティ
  computed: {
    // errorモジュールのステートを確認しerrorCode()の引数に代入
    errorCode () {
      return this.$store.state.error.code
    }
  },

  // 算出プロパティの変更を監視する
  watch: {
    errorCode: {
      async handler (val) {
        // errorCodeの引数にINTERNAL_SERVER_ERRORが入った場合の処理
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push('/500')
        } else if (val === UNAUTHORIZED) {
          // リフレッシュトークンAPIを叩いてcsrfトークンをリセット
          await axios.get('/api/refresh-token')
          // ストアのユーザーをリセット
          this.$store.commit('auth/setUser', null)
          // リダイレクト処理
          this.$router.push('/login')
        }
      },
      // ウォッチャオプション trueにすると初期読み込み時にも関数を呼び出せる
      immediate: true
    },

    $route () {
      this.$store.commit('error/setCode', null)
    }
  }
}
</script>
