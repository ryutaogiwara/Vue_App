<template>
  <div class="photo">
    <figure class="photo__wrapper">
      <img 
        class="photo__image"
        :src="item.url"
        :alt="'Photo by ${item.owner.name}'"
      >
    </figure>

    <!-- オーバーレイ -->
    <RouterLink
      class="photo__overlay"
      :to="`/photos/${item.id}`"
      :title="`View the photo by ${item.owner.name}`"
    >
      <!-- like button & download link-->
      <!-- :classはいいねがすでにいいねがついている場合に付与するクラス -->
      <!-- @clickイベントでlikeメソッドを呼び出していいね状態を切り替える -->
      <div class="photo__controls">
        <button
          class="photo__action photo__action--like"
          :class="{ 'photo__action--liked': item.liked_by_user }"
          title="Like photo"
          @click.prevent="like"
        >
          <i class="icon ion-md-heart"></i>{{ item.likes_count }}
        </button>

        <!-- ダウンロード昨日はvueのRouterLinkではなくサーバー再度で行うためaタグで用意する必要がある -->
        <!-- .stopはダウンロードがクリックされた際に親要素のクリックを止めないと画面遷移が起きてしまうため -->
        <a
          class="photo__action"
          title="Download photo"
          @click.stop
          :href="`/photos/${item.id}/download`"
        >
          <i class="icon ion-md-arrow-round-down"></i>
        </a>
      </div>

      <div class="photo__username">
        {{ item.owner.name }}
      </div>

    </RouterLink>
 
  </div>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      required: true
    }
  },

  methods: {
    like () {
      this.$emit('like', {
        id: this.item.id,
        liked: this.item.liked_by_user,
      })
    }
  }
}
</script>

