<template>
  <footer class="footer">
    <button v-if="isLogin" class="button button--link" @click="logout">Logout</button>
    <RouterLink v-else class="button button--link" to="/login">
      Login / Register
    </RouterLink>
  </footer>
</template>

<script>
import {mapState, mapGetters} from 'vuex'
export default {
  computed: {
    ...mapState({
      apiStatus: state => state.auth.apiStatus
    }),
    ...mapGetters({
      isLogin: 'auth/check'
    })
  },
  methods: {
    async logout () {
      // authストアlogoutメソッド呼び出し
      await this.$store.dispatch('auth/logout')

      if (this.apiStatus) {
        //  logoutメソッド終了後にVuerouterに/loginでアクセス
        this.$router.push('/login')
      }
    }
  }
}
</script>
