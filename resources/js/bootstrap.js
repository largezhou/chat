window.log = console.log.bind(console)

window.ChatClient = require('@/libs/chat-client').default
window.chat = ChatClient.instance()
