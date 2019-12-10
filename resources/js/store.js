import Vue from 'vue'
import Vuex from 'vuex'
import { getUserInfo, postLogout } from '@/api'

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
    async getUser({ commit }) {
      const { data } = await getUserInfo()
      commit('SET_USER', data)
    },
    async logout({ commit }) {
      await postLogout()
      commit('SET_USER', null)
      location.href = '/'
    },
  },
  getters: {
    getUserInfo(state) {
      return field => {
        return _.get(state.user, field)
      }
    },
  },
})
