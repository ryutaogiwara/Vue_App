<template>
  <nav class="navbar">
    <!-- aタグの代わりにRouterLinkを用いる。Getリクエストが発生せずvuerouterにて画面を切り替える。 -->
    <RouterLink class="navbar__brand" to="/">
      Vuesplash
    </RouterLink>

    <div class="navbar__menu">
      <div v-if="isLogin" class="navbar__item">
        <button class="button" @click="showForm = ! showForm">
          <i class="icon ion-md-add"></i>
          Submit a photo
        </button>
      </div>

      <span v-if="isLogin" class="navbar__item">
        {{ username }}
      </span>

      <div v-else class="navbar__item">
        <RouterLink class="button button__link" to="/login">
          Login / Regiser
        </RouterLink>
      </div>
    </div>
    <PhotoForm v-model="showForm" />
  </nav>
</template>

<script>
import PhotoForm from './PhotoForm.vue'

export default {
  components: {
    PhotoForm
  },
  data () {
    return {
      // デフォルトではfalseを設定。ボタンを押した際にフォームが表示される
      showForm: false
    }
  },

  computed: {
    isLogin () {
      return this.$store.getters['auth/check']
    },

    username () {
      return this.$store.getters['auth/username']
    }
  }
}
</script>
