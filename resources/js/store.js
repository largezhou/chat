import Vue from 'vue'
import Vuex from 'vuex'
import { postLogout } from '@/api'
import _get from 'lodash/get'
import { jsonParse } from '@/libs/utils'

Vue.use(Vuex)

const getData = (key, defaultVal = null) => {
  return jsonParse(_get(document.querySelector('#app'), `dataset.${key}`), defaultVal)
}

export default new Vuex.Store({
  state: {
    /**
     * 当前登录用户
     */
    user: getData('user'),
    /**
     * 当前聊天目标
     */
    target: null,
    /**
     * 配置
     */
    config: getData('config'),
  },
  mutations: {
    SET_USER(state, user) {
      state.user = user
    },
    SET_TARGET(state, target) {
      state.target = target
    },
  },
  actions: {
    async logout() {
      try {
        await postLogout()
      } catch (e) {
        const { response: res } = e
        if (!res || (res.status !== 401) || (res.status !== 419)) {
          throw e
        }
      }

      location.reload()
    },
  },
  getters: {
    getUserInfo(state) {
      return field => {
        return _get(state.user, field)
      }
    },
    getConfig(state) {
      return key => {
        return _get(state.config, key)
      }
    },
  },
})
