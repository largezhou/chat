<template>
  <div class="index">
    <div class="page-main" id="page-main">
      <div class="header">
        <h2 style="color: #fff;">CHAT</h2>
        <div class="flex-spacer"/>
        <div v-if="user" class="header-item">
          <lz-button
            @click="onSearch"
            icon="svg-search"
            icon-size="20px"
          />
          <lz-button
            id="notification-modal-btn"
            class="notification"
            icon="svg-notification"
            icon-size="20px"
            @click="notificationCenterModal = !notificationCenterModal"
          >
            <span v-show="hasNotifications" class="indicate"/>
          </lz-button>
          <lz-button class="avatar" :action="onLogout">
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
        <contacts/>
        <div class="chat-main">
          <recent-contacts/>
          <chat-main/>
        </div>
      </div>
    </div>

    <login-modal
      v-model="loginModal"
      attach="#login-modal-btn"
      align="right-top"
    />

    <chat-finder-modal
      v-model="chatFinderModal"
      attach="#page-main"
      align="top"
      top-offset="20px"
      persistent
    />

    <notification-center-modal
      v-model="notificationCenterModal"
      attach="#notification-modal-btn"
      align="right-top"
      persistent
    />
  </div>
</template>

<script>
import { mapState } from 'vuex'
import { getUserInfo } from '@/api'
import NotificationCenterModal from '@c/NotificationCenterModal'

export default {
  name: 'Index',
  components: { NotificationCenterModal },
  data: () => ({
    loginModal: false,
    chatFinderModal: false,
    notificationCenterModal: true,
  }),
  computed: {
    ...mapState({
      user: state => state.user,
      hasNotifications: state => state.hasNotifications,
    }),
  },
  methods: {
    getUserInfo,
    async onLogout() {
      await this.$store.dispatch('logout')
    },
    onSearch() {
      this.chatFinderModal = !this.chatFinderModal
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

  .notification {
    position: relative;

    .indicate {
      position: absolute;
      background: #ff3535;
      top: 0;
      right: 0;
      border-radius: 50%;
      width: 10px;
      height: 10px;
    }
  }
}

.header-item {
  display: flex;
  flex-direction: row;

  > * + * {
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

.avatar {
  padding: 0 !important;
}
</style>
