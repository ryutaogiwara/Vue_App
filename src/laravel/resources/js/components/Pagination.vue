<template>
  <div class="pagination">
    <!-- (prev)前へボタンは1ページ目以外の場合に表示 -->
    <RouterLink
      v-if="! isFirstPage"
      :to="`/?page=${currentPage - 1}`"
      class="button"
    >&laquo; prev</RouterLink>

    <!-- (next)次へボタンは最終ページ以外のページで表示 -->
    <RouterLink
      v-if="! isLastPage"
      :to="`/?page=${currentPage + 1}`"
      class="button"
    >next &raquo;</RouterLink>
  </div>
</template>

<script>
export default {
  props: {
    // 現在ページ
    currentPage: {
      type: Number,
      required: true
    },
    // 最終ページ
    lastPage: {
      type: Number,
      required: true
    }
  },
  computed: {
    // 現在が1ページ目かどうかを判定
    isFirstPage () {
      return this.currentPage === 1
    },

    // 現在が最終ページかどうかを判定
    isLastPage () {
      return this.currentPage === this.lastPage
    }
  }
}
</script>
