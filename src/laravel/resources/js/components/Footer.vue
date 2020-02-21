<template>
  <footer class="footer">
    <button class="button button--link" @click="logout">Logout</button>
    <RouterLink class="button button--link" to="/login">
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
  }
}
</script>
