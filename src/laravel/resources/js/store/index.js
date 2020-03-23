import Vue from 'vue'
import Vuex from 'vuex'

// ストア読み込み
import auth from './auth'
import error from './error'
import message from './message'


Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    auth,
    error,
    message
  }
})

export default store
