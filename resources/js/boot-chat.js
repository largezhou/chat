import store from '@/store'
import { makeDialogKey } from '@/libs/utils'
import { Notice } from '@/libs/notice'

window.ChatEventEnum = require('@/libs/chat-event-enum').ChatEventEnum
window.ChatClient = require('@/libs/chat-client').default
/** @type {ChatClient} 聊天客户端实例 */
window.chat = ChatClient.instance()

chat.addHandler(ChatEventEnum.ONLINE_FRIEND_IDS, (data, client) => {
  store.commit('SET_ONLINE_FRIEND_IDS', data)
})
chat.addHandler(ChatEventEnum.FRIEND_ONLINE, (data, client) => {
  store.dispatch('addOnlineFriendId', data)
})
chat.ws().addEventListener('error', e => {
  store.commit('SET_ONLINE_FRIEND_IDS', [])
})
chat.addHandler(ChatEventEnum.FRIEND_OFFLINE, (data, client) => {
  store.dispatch('removeOnlineFriendId', data)
})
chat.addHandler(ChatEventEnum.OTHER_LOGGED_IN, () => {
  location.reload()
})
chat.addHandler(ChatEventEnum.MSG_RES, (data, client) => {
  store.dispatch('updateMsgStatus', data)
})
chat.addHandler(ChatEventEnum.MSG, (data, client) => {
  store.commit('PUSH_DIALOG', {
    key: makeDialogKey(data.user_id, data.target_id),
    msg: data,
  })
  store.commit('SET_RECENT_CONTACT', {
    targetId: data.user_id,
    msg: data.content_text,
  })
})
chat.addHandler(ChatEventEnum.NOTIFY, (data, client) => {
  store.commit('SET_HAS_NOTIFICATIONS', true)
  Notice.show('新的好友申请。')
})

chat.addEventListener('close', () => {
  Notice.error('聊天服务已断开。')
})

chat.addEventListener('open', () => {
  Notice.show('已连接至聊天服务。')
})
