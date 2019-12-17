import Cookie from 'js-cookie'
import { jsonParse } from '@/libs/utils'
import store from '@/store'

const config = store.state.config

class ChatClient {
  /**
   * @type {WebSocket}
   */
  #ws = null

  /**
   * 连接失败重试次数
   * @type {number}
   */
  #retry = 10

  /**
   * 是否在进行重试
   * @type {boolean}
   */
  #retrying = false

  /**
   * 已重试次数
   * @type {number}
   */
  #currentTried = 0

  #initialEventHandlers = {
    open: this.#onOpen,
    message: this.#onMessage,
    close: this.#onClose,
  }

  #messageHandlers = {}

  static get CONNECTED() { return 'connected' }

  static get PING() { return 'ping' }

  static get PONG() { return 'pong' }

  static get ONLINE_COUNT() { return 'online_count' }

  static get OTHER_LOGGED_IN() { return 'other_logged_in' }

  static get AUTH() { return 'auth' }

  static get CLOSED_MANUALLY_CODE() { return 4000 }

  static get CLOSED_MANUALLY() { return 'closed_manually' }

  static #ins

  constructor() {
    if (ChatClient.#ins) {
      throw new Error('滚')
    }

    this.connect()
    this.#initHandlers()

    ChatClient.#ins = this
  }

  static instance() {
    if (!ChatClient.#ins) {
      new ChatClient()
    }

    return ChatClient.#ins
  }

  /**
   * 连接 WebSocket 服务器
   */
  connect() {
    return this.#doConnect(false)
  }

  /**
   * 实际执行连接操作的方法
   * @param {boolean} force 设置为 true 的话，则无视当前是否正在重连
   */
  #doConnect(force = false) {
    if (this.isAvailable()) {
      return false
    }

    if (!force && this.#retrying) {
      return false
    }

    this.#ws = new WebSocket(`ws://${location.hostname}:${config.ws_port}`)
    this.#initEvents()
    return true
  }

  /**
   * 当前连接是否有效
   * @returns {boolean}
   */
  isAvailable() {
    return this.#ws &&
      ([WebSocket.CONNECTING, WebSocket.OPEN].indexOf(this.#ws.readyState) !== -1)
  }

  /**
   * 初始化 WebSocket 的基本事件
   */
  #initEvents() {
    const t = this.#initialEventHandlers
    Object.keys(t).forEach(e => {
      this.#ws.addEventListener(e, t[e].bind(this))
    })
  }

  #initHandlers() {
    this.addHandler(ChatClient.CONNECTED, this.#connectedHandler.bind(this))
  }

  #onOpen(e) {
    this.#resetRetry()
    this.#sendOnlineCount()
    this.#sendAuth()
  }

  #sendOnlineCount() {
    if (this.isConnected()) {
      this.send(ChatClient.ONLINE_COUNT)
      setTimeout(this.#sendOnlineCount.bind(this), 5 * 1000)
    }
  }

  #connectedHandler(client, data) {
    const sendPing = () => {
      setTimeout(() => {
        if (this.isConnected()) {
          this.send(ChatClient.PING)
          sendPing()
        }
      }, data.interval * 1000)
    }
    sendPing()
  }

  #resetRetry() {
    this.#currentTried = 0
    this.#retrying = false
  }

  #onMessage(e) {
    const { type, data } = this.data(e.data)
    const handlers = this.#messageHandlers[type] || []
    handlers.forEach(cb => {
      cb(this, data)
    })
  }

  #onClose(e) {
    const { code, reason } = e
    if (code === ChatClient.CLOSED_MANUALLY_CODE) {
      console.log(reason)
      return
    }

    this.#reconnect()
  }

  #reconnect() {
    this.#ws = null
    this.#retrying = true

    if (this.#currentTried >= this.#retry) {
      console.error('重连失败')
      this.#resetRetry()
      return
    }

    this.#currentTried++
    console.log(`重连中... (${this.#currentTried} 次)`)
    this.#doConnect(true)
  }

  send(type, data) {
    this.#ws.send(JSON.stringify({
      type,
      data,
    }))
  }

  data(strData) {
    const { type, data } = jsonParse(strData, { type: null, data: undefined })
    return { type, data }
  }

  addHandler(type, callback) {
    if ((typeof type !== 'string') || (type.trim() === '')) {
      return
    }

    if (!this.#messageHandlers[type]) {
      this.#messageHandlers[type] = []
    }

    this.#messageHandlers[type].push(callback)
  }

  removeHandler(type, callback) {
    const h = this.#messageHandlers[type] || []
    const i = h.indexOf(callback)
    h.splice(i, 1)
  }

  #sendAuth() {
    this.send(ChatClient.AUTH, Cookie.get('laravel_session'))
  }

  isConnected() {
    return this.#ws && (this.#ws.readyState === WebSocket.OPEN)
  }

  close() {
    if (this.isConnected()) {
      this.#ws.close(
        ChatClient.CLOSED_MANUALLY_CODE,
        ChatClient.CLOSED_MANUALLY,
      )
      this.#ws = null
    }
  }
}

export default ChatClient
