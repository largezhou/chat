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
    /**
     * 当前在线好友的 id
     */
    onlineFriendIds: [],
  },
  mutations: {
    SET_USER(state, user) {
      state.user = user
    },
    SET_TARGET(state, target) {
      state.target = target
    },
    SET_ONLINE_FRIEND_IDS(state, ids) {
      state.onlineFriendIds = ids
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
    addOnlineFriendId({ state, commit }, id) {
      commit('SET_ONLINE_FRIEND_IDS', [...(new Set([...state.onlineFriendIds, id]))])
    },
    removeOnlineFriendId({ state, commit }, id) {
      const t = [...state.onlineFriendIds]
      const i = t.indexOf(id)
      if (i !== -1) {
        t.splice(i, 1)
        commit('SET_ONLINE_FRIEND_IDS', t)
      }
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
