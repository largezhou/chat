export class ChatEventEnum {
  static get CONNECTED() { return 'connected' }

  static get PING() { return 'ping' }

  static get PONG() { return 'pong' }

  static get ONLINE_COUNT() { return 'online_count' }

  static get OTHER_LOGGED_IN() { return 'other_logged_in' }

  static get AUTH() { return 'auth' }

  static get ONLINE_FRIEND_IDS() { return 'online_friend_ids' }

  static get FRIEND_ONLINE() { return 'friend_online' }

  static get FRIEND_OFFLINE() { return 'friend_offline' }

  static get MSG() { return 'msg' }

  static get MSG_RES() { return 'msg_res' }

  static get NOTIFY() { return 'notify' }
}
