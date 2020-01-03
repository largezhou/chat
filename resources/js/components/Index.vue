<template>
  <div class="index">
    <div class="page-main">
      <div class="header">
        <h2 style="color: #fff;">CHAT</h2>
        <div class="flex-spacer"/>
        <div class="header-item">
          <lz-button @click="onStartChat">我要聊天</lz-button>
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
          <chat-main/>
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
import { showIn } from '@/libs/utils'
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
      log(chat.connect())
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
</style>
