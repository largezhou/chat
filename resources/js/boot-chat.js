import store from '@/store'

window.ChatClient = require('@/libs/chat-client').default
/** @type {ChatClient} 聊天客户端实例 */
window.chat = ChatClient.instance()

chat.addHandler(ChatClient.ONLINE_FRIEND_IDS, (data, client) => {
  store.commit('SET_ONLINE_FRIEND_IDS', data)
})
chat.addHandler(ChatClient.FRIEND_ONLINE, (data, client) => {
  store.dispatch('addOnlineFriendId', data)
})
chat.ws().addEventListener('error', e => {
  store.commit('SET_ONLINE_FRIEND_IDS', [])
})
chat.addHandler(ChatClient.FRIEND_OFFLINE, (data, client) => {
  store.dispatch('removeOnlineFriendId', data)
})
chat.addHandler(ChatClient.OTHER_LOGGED_IN, () => {
  location.reload()
})
