<template>
  <footer class="footer">
    <button v-if="isLogin" class="button button--link" @click="logout">Logout</button>
    <RouterLink v-else class="button button--link" to="/login">
      Login / Register
    </RouterLink>
  </footer>
</template>

<script>
export default {
  methods: {
    async logout () {
      // authストアlogoutメソッド呼び出し
      await this.$store.dispatch('auth/logout')
      //  logoutメソッド終了後にVuerouterに/loginでアクセス
      this.$router.push('/login')
    }
  },

  computed: {
    isLogin () {
      return this.$store.getters['auth/check']
    }
  }
}
</script>
