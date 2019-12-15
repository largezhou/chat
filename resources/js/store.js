import Vue from 'vue'
import Vuex from 'vuex'
import { postLogout } from '@/api'
import _get from 'lodash/get'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: null,
  },
  mutations: {
    SET_USER(state, user) {
      state.user = user
    },
  },
  actions: {
    async logout({ commit }) {
      await postLogout()
      commit('SET_USER', null)
    },
  },
  getters: {
    getUserInfo(state) {
      return field => {
        return _get(state.user, field)
      }
    },
  },
})
