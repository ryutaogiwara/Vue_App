<template>
  <div class="photo-list">
    <div class="grid">
      <Photo
        class="grid__item"
        v-for="photo in photos"
        :key="photo.id"
        :item="photo"
        @like="onLikeClick"
      />
    </div>
    <Pagination :current-page="currentPage" :last-page="lastPage" />
  </div>
</template>

<script>
import { OK } from '../util'
import Photo from '../components/Photo.vue'
import Pagination from '../components/Pagination.vue'

export default {
  components: {
    Photo,
    Pagination
  },

  props: {
    page: {
      type: Number,
      required: false,
      default: 1
    }
  },

  data () {
    return {
      photos: [],
      currentPage: 0,
      lastPage: 0
    }
  },

  methods: {
    async fetchPhotos () {
      // PhotoController@indexを呼び出す
      const response = await axios.get(`/api/photos/?page=${this.page}`)

      // エラーハンドリング
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // response.dataでAPIから取得したJSONが取得できる
      this.photos = response.data.data
      this.currentPage = response.data.current_page
      this.lastPage = response.data.last_page
    },

    // photo.vueのいいねボタンから送られてきたemitが引数に入る
    onLikeClick ({ id, liked }) {
      if (! this.$store.getters['auth/check']) {
        alert('いいね機能を使うにはログインしてください。')
        return false
      }

      // いいねがすでにされていた場合とそうでない場合の分岐
      if (liked) {
        this.unlike(id)
      } else {
        this.like(id)
      }
    },

    async like (id) {
      // apiを叩いていいねをつける
      const response = await axios.put(`/api/photos/${id}/like`)

      // エラーハンドリング
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false
      }

      // いいねがつけられた際のフロント画面の更新
      this.photos = this.photos.map(photo => {
        if (photo.id === response.data.photo_id) {
          photo.likes_count += 1,
          photo.liked_by_user = true
        }
        return photo
      })
    },

    async unlike (id) {
      const response = await axios.delete(`/api/photos/${id}/like`)

      // エラーハンドリング
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false
      }

      // いいねが取り消された際のフロント画面の更新
      this.photos = this.photos.map(photo => {
        if (photo.id === response.data.photo_id) {
          photo.likes_count -= 1,
          photo.liked_by_user = false
        }
        return photo
      })
    },
  },

  watch: {
    $route: {
      async handler () {
        await this.fetchPhotos()
      },
      immediate: true
    }
  }
}
</script>
