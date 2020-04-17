<template>
  <!-- :classはv-bind:classの省略形 -->
  <div 
    v-if="photo" 
    class="photo-detail"
    :class="{ 'photo-detail--column': fullWidth }"> 
    <figure 
      class="photo-detail__pane photo-detail__image"
      @click="fullWidth = ! fullWidth">
      <img :src="photo.url" alt="">
      <figcaption>Posted by {{ photo.owner.name }}</figcaption>
    </figure>
    <div class="photo-detail__pane">
      <button class="button button--like" title="Like Photo">
        <i class="icon ion-md-heart"></i>12
      </button>
      <a
        :href="`/photos/${photo.id}/download`"
        class="button"
        title="Download photo"
      >
        <i class="icon ion-md-arrow-round-down"></i>Download
      </a>
      <h2 class="photo-detail__title">
        <i class="icon ion-md-chatboxes"></i>Comments
      </h2>
      <form>
        <textarea class="form__item" v-model="commentContent"></textarea>
        <div class="form__button">
          <button type="submit" class="button button--inverse">submit comment</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { OK } from '../util'

export default {
  props:{
    id: {
      type: String,
      required: true
    }
  },
  data () {
    return {
      photo: null,
      // @clickでバインドされたクラスを付け替える
      fullWidth: false,
      commentContent: ''
    }
  },
  methods:{
    async fetchPhoto () {
      const response = await axios.get(`/api/photos/${this.id}`)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.photo = response.data
    },
    async addComment () {
      const response = await axios.post(`/api/photos/${this.id}/comments`, {
        content: this.commentContent
      })

      this.commentContent = ''
    }
  },
  watch:{
    $route: {
      async handler () {
        await this.fetchPhoto()
      },
      immediate: true
    }
  }
}
</script>
