// ステータスコードのインポート
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'

const state = {
  // デフォルト値
  user: null,
  apiStatus: null,
  loginErrorMessages: null,
  registerErrorMessages: null
}

const getters = {
  // ログインチェック
  check: state => !! state.user,
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
  },

  setLoginErrorMessages(state, messages) {
    state.loginErrorMessages = messages
  },

  setRegisterErrorMessages(state, messages) {
    state.registerErrorMessages = messages
  }
}

const actions = {
  // registerアクション
  async register(context, data) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/register', data)

    if (response.status === CREATED) {
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data)
      return false
    }

    context.commit('setApiStatus', false)
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setRegisterErrorMessages', response.data.errors)
    } else {
      context.commit('error/setCode', response.status, { root:true })
    }
  },

  // loginアクション
  async login(context, data) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/login', data)
    
    // responseの値を参照して処理を出し分ける
    if (response.status === OK) {
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data)
      return false
    }

    context.commit('setApiStatus', false)
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setLoginErrorMessages', response.data.errors)
    } else {
      // { root: true }は異なるストア間('setApiStatus'と'error/SetCode'など)のミューテーションを呼び出す際に必要
      context.commit('error/setCode', response.status, { root: true })
    }

  },

  // logoutアクション
  async logout(context) {
    context.commit('setApiStatus', null)
    // logoutにユーザー情報は比喩等ないので引数にdataは必要ない
    const response = await axios.post('/api/logout')

    if (response.status === OK) {
      context.commit('setApiStatus', true)
      // setUserをnullにすることでユーザー情報を未ログイン状態に戻す
      context.commit('setUser', null)
      return false
    }

    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true })
  },

  // ユーザーデータ取得
  async currentUser(context) {
    context.commit('setApiStatus', null)
    // userAPIを叩いてユーザー情報の取得
    const response = await axios.get('/api/user')
    // ユーザー情報が得られれば変数userに、なければnullを代入
    const user = response.data || null

    if (response.status === OK) {
      context.commit('setApiStatus', true)
      // setUserを呼び出し、stateの書き換え
      context.commit('setUser', user)
      return false
    }
    
    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true })
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
