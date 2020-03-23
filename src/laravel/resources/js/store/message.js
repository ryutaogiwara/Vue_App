const state = {
  content: ''
}

const mutations = {
  setContent(state, { content, timeout }) {
    state.content = content

    if (typeof timeout === 'undefined') {
      timeout = 3000
    }

    // メッセージがタイムアウトを過ぎると自動で消去される
    setTimeout(() => (state.content = ''), timeout)
  }
}

export default {
  namespaced: true,
  state,
  mutations
}
