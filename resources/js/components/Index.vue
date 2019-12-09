<template>
  <div class="index">
    <div class="page-main">
      <div class="header">
        <h2 style="color: #fff;">CHAT</h2>
        <div class="flex-spacer"/>
        <button type="button" class="btn header-item">我要聊天</button>
        <button type="button" class="btn header-item pa-0">
          <avatar
            id="user-info"
            @click.native="loginModal = !loginModal"
            class="header-item"
            avatar="http://chat.l.com/uploads/61c1b32a961b0d868a78dae00e4997f9.png"
            size="35px"
          />
        </button>
      </div>
      <div class="main">
        <div class="contact"/>
        <div class="chat-main pb-4" :style="{ background: black ? '#12152f' : '#fff' }">
          <div class="recent-items">
            <recent-contact-item
              v-for="item of recentContacts"
              :key="item.id"
              :item="item"
            />
          </div>
          <div class="dialog-main">
            <div class="dialog-header">
              <avatar avatar="http://chat.l.com/uploads/61c1b32a961b0d868a78dae00e4997f9.png" size="60px"/>
              <div class="target-name pb-2 pl-1">
                <span>头上有灰机</span>
                <span class="online-indicate"/>
              </div>
              <div class="flex-spacer"/>
              <span style="color: #fff;" @click="black = !black">好友</span>
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
      attach="#user-info"
      align="right-top"
    />
  </div>
</template>

<script>
export default {
  name: 'Index',
  data: () => ({
    black: true,
    loginModal: true,
    recentContacts: [
      {
        id: 1,
        avatar: 'http://chat.l.com/uploads/61c1b32a961b0d868a78dae00e4997f9.png',
        online: true,
        name: '头上有灰机',
        recent_content: '你好啊',
        created_at: '刚刚',
        unreads_count: 3,
      },
      {
        id: 2,
        avatar: 'http://chat.l.com/uploads/41abbcca9305be17900ecd62d852b733.jpg',
        online: false,
        name: '黑客帝国',
        recent_content: '你好啊你好啊你好啊你好啊你好啊你好啊你好啊你好啊你好啊你好啊你好啊你好啊',
        created_at: '10:10',
        unreads_count: 0,
      },
      {
        id: 3,
        avatar: 'http://chat.l.com/uploads/5bcfe346bdb0b0a64c2d79eb25030f74.jpg',
        online: true,
        name: '守望者',
        recent_content: 'Test test test test test test test test test test test test test test ',
        created_at: '一天前',
        unreads_count: 3,
      },
    ],
  }),
  methods: {
    showModal(e) {
      this.modal = !this.modal
    },
  },
}
</script>

<style scoped lang="scss">
$chat-radius: 12px;

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
  + .header-item {
    margin-left: 30px;
  }
}

.main {
  display: flex;
  width: 100%;
  height: 100%;
}

.contact {
  width: 220px;
  flex-shrink: 0;
  background: #212950;
  border-bottom-left-radius: $chat-radius;
}

.chat-main {
  width: 100%;
  background: #12152f;
  border-bottom-right-radius: $chat-radius;
  position: relative;
}

.recent-items {
  position: absolute;
  left: 0;
  top: 0;
  box-sizing: border-box;
  width: 300px;
  height: 100%;
  padding: 20px 15px 20px 20px;
}

.dialog-main {
  width: 100%;
  height: 100%;
  box-sizing: border-box;
  padding: 0px 30px 0px 315px;
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
}

.online-indicate {
  position: absolute;
  right: -20px;
  width: 10px;
  height: 10px;
}
</style>

<style lang="scss">
</style>
