const state = {
  // デフォルト値
  user: null
}

const getters = {}

const mutations = {
  setUser(state, user) {
    // ユーザーデータのセット
    state.user = user
  }
}

const actions = {
  // registerアクション
  async register(context, data) {
    // apiを叩く
    const response = await axios.post('/api/register', data)
    // commitでmutation呼び出し→stateを更新
    context.commit('setUser', response.data)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
