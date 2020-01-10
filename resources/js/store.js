import Vue from 'vue'
import Vuex from 'vuex'
import { postLogout } from '@/api'
import _get from 'lodash/get'
import { jsonParse } from '@/libs/utils'
import { MSG_STATUS, MAX_MSGS_COUNT } from '@/libs/constants'

Vue.use(Vuex)

const getData = (key, defaultVal = null) => {
  return jsonParse(_get(document.querySelector('#app'), `dataset.${key}`), defaultVal)
}

const store = new Vuex.Store({
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
    /**
     * 聊天记录
     */
    dialogs: {},
    /**
     * key -> msg 的映射，用来处理消息发送的结果
     */
    keyMsgMap: {},

    /**
     * 每 2 秒改变一次值
     */
    ticker2: Date.now(),
    /**
     * 最近的消息
     * @var {{targetId, msg}|null}
     */
    recentContact: null,
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
    PUSH_DIALOG(state, { key, msg }) {
      if (state.dialogs[key] === undefined) {
        Vue.set(state.dialogs, key, [])
      }

      state.dialogs[key].push(msg)
      if (state.dialogs[key].length > MAX_MSGS_COUNT) {
        state.dialogs[key].shift()
      }
    },
    SET_KEY_MSG_MAP(state, msg) {
      state.keyMsgMap[msg.key] = msg
    },
    REMOVE_KEY_MSG_MAP(state, key) {
      delete state.keyMsgMap[key]
    },
    SET_DIALOGS(state, { key, dialogs }) {
      Vue.set(state.dialogs, key, dialogs)
    },
    SET_RECENT_CONTACT(state, payload) {
      state.recentContact = payload
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
    updateMsgStatus({ state, commit }, { key, error }) {
      const msg = state.keyMsgMap[key]
      if (!msg) {
        return
      }

      if (error) {
        msg.status = MSG_STATUS.FAILED
        Vue.set(msg, 'error', error)
      } else {
        msg.status = MSG_STATUS.OK
      }

      // 纯粹为了在 devtool 中加一个快照
      commit('SET_KEY_MSG_MAP', { key, msg })
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
    getDialogsByKey(state) {
      return key => {
        return state.dialogs[key] || []
      }
    },
  },
})

window.setInterval(() => {
  store.state.ticker2 = Date.now()
}, 2 * 1000)

export default store
