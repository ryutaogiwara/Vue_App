const state = {
  // デフォルト値
  user: null
}

const getters = {
  // ログインチェック
  check: state => !!state.user,
  // ログインユーザー名検索
  username: state => state.user ? state.user.name : ''
}

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
  },

  // loginアクション
  async login(context, data) {
    const response = await axios.post('/api/login', data)
    context.commit('setUser', response.data)
  },

  // logoutアクション
  async logout(context) {
    // logoutにユーザー情報は比喩等ないので引数にdataは必要ない
    const response = await axios.post('/api/logout')
    // setUserをnullにすることでユーザー情報を未ログイン状態に戻す
    context.commit('setUser', null)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
