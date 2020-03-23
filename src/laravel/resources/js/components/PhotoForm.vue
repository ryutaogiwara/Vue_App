<template>
  <div v-show="value" class="photo-form">
    <h2 class="title">Submit a photo</h2>
    <!-- .preventはデフォルトの送信処理を抑えるため -->
    <form class="form" @submit.prevent="submit">>
      <input class="form__item" type="file" @change="onFileChange">
      <output class="form__output" v-if="preview">
        <img :src="preview" alt="">
      </output>
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
  },

  data () {
    return {
      // previewにはプレビュー画像のデータURLが入る。初期値はnull
      preview: null,
      photo: null
    }
  },

  methods: {
    // プレビューリセット
    reset () {
      // プレビューを消す
      this.preview = ''
      // inputデータを消す this.$el.querySelectorはコンポーネントそのもののDOMを探すためのもの
      this.$el.querySelector('input[type="file"]').value = null
      // 保持していた画像データの情報を消す
      this.photo = null
    },

    // フォームでファイルが選択されたら発火
    onFileChange (event) {
      // エラーハンドリング1 何も選択されていなかったら処理中断
      if (event.target.files.length === 0) {
        this.reset()
        return false
      }
      // エラーハンドリング2 画像ファイル以外が選択されていたら処理中断
      if (! event.target.files[0].type.match('image.*')) {
        this.reset()
        return false
      }

      
      // FileReaderクラスのインスタンスを取得
      const reader = new FileReader()


      // ファイルを読み込み終わったタイミングで実行する処理
      reader.onload = e => {
        // previewに読み込み結果（データURL）を代入する
        // previewに値が入ると<output>につけたv-ifがtrueと判定される
        // また<output>内部の<img>のsrc属性はpreviewの値を参照しているので
        // 結果として画像が表示される
        this.preview = e.target.result
      }

      // ファイルを読み込む
      // 読み込まれたファイルはデータURL形式で受け取れる（上記onload参照）
      reader.readAsDataURL(event.target.files[0])

      // 選択された画像をthis.photoとして保持
      this.photo = event.target.files[0] 
    },

    // 送信
    async submit () {
      // HTML5のFormDataAPIを使ってAjaxでファイル送信を行う
      const formData = new FormData()
      // onFileChangeで保持したデータを代入
      formData.append('photo', this.photo)
      // APIを叩く
      const response = await axios.post('/api/photos', formData)

      this.reset()
      // NavbarのshowFormの値とPhotoFormのinputはv-modelでバインドされている
      // ここでfalseを返すことで投稿完了時にフォームが閉じる
      this.$emit('input', false)
      // 投稿後は詳細ページに画面遷移
      this.$router.push(`/photos/${response.data.id}`)
    }
  }
}
</script>