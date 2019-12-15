<template>
  <div class="index">
    <div class="page-main">
      <div class="header">
        <h2 style="color: #fff;">CHAT</h2>
        <div class="flex-spacer"/>
        <div class="header-item">
          <lz-button @click="onStartChat">我要聊天</lz-button>
          <lz-button @click="onAuthChat">认证</lz-button>
        </div>
        <div v-if="user" class="header-item">
          <lz-button
            style="width: 35px; padding: 0px;"
            :action="onLogout"
          >
            <avatar :avatar="user.avatar" size="35px"/>
          </lz-button>
        </div>
        <div v-if="!user" class="header-item">
          <lz-button
            id="login-modal-btn"
            @click="loginModal = !loginModal"
          >
            加入
          </lz-button>
        </div>
      </div>
      <div class="main">
        <contacts @select-contact="onSelectContact"/>
        <div class="chat-main">
          <div class="recent-items">
            <div class="recent-items-inner" ref="recentItemsInner">
              <recent-contact-item
                v-for="item of recentContacts"
                :key="item.id"
                :item="item"
              />
            </div>
          </div>
          <div class="dialog-main">
            <div class="dialog-header">
              <avatar avatar="http://chat.l.com/uploads/61c1b32a961b0d868a78dae00e4997f9.png" size="60px"/>
              <div class="target-name">
                <span>头上有灰机</span>
                <span class="online-indicate"/>
              </div>
              <div class="flex-spacer"/>
              <span style="color: #fff;">好友</span>
            </div>
            <div class="dialog-items">
              <dialog-item/>
              <dialog-item me/>
              <dialog-item/>
              <dialog-item me/>
            </div>
          </div>
        </div>
      </div>
    </div>

    <login-modal
      v-model="loginModal"
      attach="#login-modal-btn"
      align="right-top"
    />
  </div>
</template>

<script>
import { mapState } from 'vuex'
import { jsonParse, showIn } from '@/libs/utils'
import Cookie from 'js-cookie'
import { getUserInfo } from '@/api'

export default {
  name: 'Index',
  data: () => ({
    loginModal: false,
    recentContacts: [],
  }),
  computed: {
    ...mapState({
      user: state => state.user,
    }),
    recentIds() {
      return this.recentContacts.map(i => i.id)
    },
  },
  created() {
    // this.onStartChat()
  },
  methods: {
    async onSelectContact(user) {
      window.c = this.$refs.recentItemsInner
      let i = this.recentIds.indexOf(user.id)
      if (i === -1) {
        i = 0
        this.recentContacts.unshift(user)
      }
      // TODO 获取该与该用户的最近联系记录
      this.$store.commit('SET_TARGET', user)

      await this.$nextTick()
      showIn(
        this.$refs.recentItemsInner,
        this.$refs.recentItemsInner.children[i],
      )
    },
    getUserInfo,
    async onLogout() {
      await this.$store.dispatch('logout')
    },
    onStartChat() {
      this.reconnectTimes = 3
      this.intervals = {}
      this.connectWS()
    },
    encode(type, data) {
      return JSON.stringify({ type, data })
    },
    connectWS() {
      this.ws = new WebSocket('ws://chat.l.com:9501')

      this.ws.addEventListener('open', e => {
        this.clearInterval('reconnectInterval')

        this.intervals.WSOnlineCountInterval = setInterval(() => {
          this.ws.send(this.encode('online_count'))
        }, 10 * 1000)

        this.user && this.ws.send(this.encode('auth', Cookie.get('laravel_session')))
      })

      this.ws.addEventListener('message', e => {
        const { data, type } = jsonParse(e.data)

        switch (type) {
          case 'connected':
            this.intervals.WSPingInterval = setInterval(() => {
              this.ws.send(this.encode('ping'))
            }, data.interval * 1000)
            break
          case 'online_count':
            log('当前在线人数：', data)
            break
          case 'other_logged_in':
            // location.reload()
            break
          default:
          // do nothing
        }
      })

      this.ws.addEventListener('close', e => {
        log('close: ', e);
        ['WSPingInterval', 'WSOnlineCountInterval'].forEach(i => {
          this.clearInterval(i)
        })

        if ([4000, 4001].indexOf(e.code) !== -1) {
          log(e.reason)
          return
        }

        this.reconnectWS(e)
      })

      this.ws.addEventListener('error', e => {
        log('error: ', e)
        this.reconnectWS(e)
      })

      window.ws = this.ws
    },
    reconnectWS(e) {
      if (this.intervals.reconnectInterval) {
        log('already in reconnecting...')
        return
      }

      log('go reconnect')
      this.connectWS()
      this.intervals.reconnectInterval = setInterval(this.connectWS, 5000)
    },
    clearInterval(key) {
      if (this.intervals[key]) {
        clearInterval(this.intervals[key])
        this.intervals[key] = null
      }
    },
    onAuthChat() {
      this.ws.send(this.encode('auth', Cookie.get('laravel_session')))
    },
  },
  watch: {
    user(user) {
      if (
        user &&
        this.ws &&
        (this.ws.readyState === WebSocket.OPEN)
      ) {
        this.onAuthChat()
      }

      if (!user) {
        this.recentContacts = []
      }
    },
  },
}
</script>

<style scoped lang="scss">
@import '~@s/_variables';

.index {
  position: absolute;
  display: flex;
  justify-content: center;
  align-items: center;
  min-width: 100%;
  min-height: 100%;
}

.page-main {
  width: 1200px;
  height: 800px;
  border-radius: $chat-radius;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.header {
  background: #19234b;
  height: 70px;
  border-radius: $chat-radius $chat-radius 0 0;
  box-shadow: 0px 5px 8px -1px rgba(0, 0, 0, 0.3);
  z-index: 1;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  padding: 0 40px;
}

.header-item {
  display: flex;
  flex-direction: row;

  button + button {
    margin-left: 30px;
  }

  + .header-item {
    margin-left: 30px;
  }
}

.main {
  display: flex;
  width: 100%;
  height: calc(100% - 70px);
}

.chat-main {
  width: 100%;
  background: #12152f;
  border-bottom-right-radius: $chat-radius;
  padding-bottom: 30px;
  display: flex;
}

.recent-items {
  width: 300px;
  height: 100%;
  padding: 20px 0px;
  flex-shrink: 0;
  overflow: hidden;
}

.recent-items-inner {
  padding-left: 20px;
  padding-right: 10px;
  margin-right: -17px;
  overflow-y: scroll;
  overflow-x: hidden;
  height: 100%;
}

.dialog-main {
  width: 100%;
  height: 100%;
  box-sizing: border-box;
  padding-right: 30px;
}

.dialog-header {
  display: flex;
  height: 90px;
  border-bottom: 1px solid #293055;
  align-items: center;
}

.target-name {
  color: #fff;
  position: relative;
  padding: 0px 0px 16px 8px;
}

.online-indicate {
  position: absolute;
  right: -20px;
  width: 10px;
  height: 10px;
}
</style>
