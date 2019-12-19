import store from '@/store'

window.log = console.log.bind(console)

window.ChatClient = require('@/libs/chat-client').default
/** @type {ChatClient} 聊天客户端实例 */
window.chat = ChatClient.instance()

chat.addHandler(ChatClient.ONLINE_FRIEND_IDS, (client, data) => {
  store.commit('SET_ONLINE_FRIEND_IDS', data)
})
chat.addHandler(ChatClient.FRIEND_ONLINE, (client, data) => {
  store.dispatch('addOnlineFriendId', data)
})
chat.ws().addEventListener('error', e => {
  store.commit('SET_ONLINE_FRIEND_IDS', [])
})
chat.addHandler(ChatClient.FRIEND_OFFLINE, (client, data) => {
  store.dispatch('removeOnlineFriendId', data)
})
