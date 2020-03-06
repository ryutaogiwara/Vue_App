// ステータスコードのインポート
import { OK } from '../util'

const state = {
  // デフォルト値
  user: null,
  apiStatus: null
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
  },

  setApiStatus(state, status) {
    // apiステータスのセット
    state.apiStatus = status
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
  },

  // ユーザーデータ取得
  async currentUser(context) {
    // userAPIを叩いてユーザー情報の取得
    const response = await axios.get('/api/user')
    // ユーザー情報が得られれば変数userに、なければnullを代入
    const user = response.data || null
    // setUserを呼び出し、stateの書き換え
    context.commit('setUser', user)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
