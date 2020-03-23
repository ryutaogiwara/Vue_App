<template>
  <div v-show="value" class="photo-form">
    <h2 class="title">Submit a photo</h2>
    <form class="form">
      <input class="form__item" type="file">
      <div class="form__button">
        <button type="submit" class="button button--inverse">submit</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  // 親要素(navbar)側で制御を行うためpropsを用いる
  // propsに送られたvalueを参照して小要素(PhotoForm)の内容が決まる
  props: {
    // valueに真偽値を与えることでv-showが機能し、表示/非表示を切り替える
    value: {
      type: Boolean,
      // requiredがないとエラーなどで真偽値情報が飛んだ時に動作しなくなる
      required: true
    }
  }
}
</script>
