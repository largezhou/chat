<template>
  <div class="container">
    <div class="wrapper">
      <div class="toolbar"/>
      <div class="main-wrap">
        <div class="sidebar"/>
        <div class="main">
          <div class="recent">
            <recent-contact-item
              v-for="item of recentContacts"
              :key="item.id"
              :item="item"
            />
          </div>
          <div class="chat-zone"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Index',
  data: () => ({
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
}
</script>

<style scoped lang="scss">
$chat-radius: 12px;
$toolbar-height: 70px;

.container {
  position: absolute;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
}

.wrapper {
  width: 1200px;
  height: 800px;
  border-radius: $chat-radius;
  display: flex;
  flex-direction: column;
}

.toolbar {
  background: #19234b;
  height: $toolbar-height;
  border-radius: $chat-radius $chat-radius 0 0;
  box-shadow: 0px 5px 8px -1px rgba(0, 0, 0, 0.3);
  z-index: 1;
  flex-shrink: 0;
}

.main-wrap {
  display: flex;
  width: 100%;
  height: 100%;
}

.sidebar {
  width: 220px;
  flex-shrink: 0;
  background: #212950;
  border-bottom-left-radius: $chat-radius;
}

.main {
  width: 100%;
  background: #10132c;
  padding-bottom: 30px;
  border-bottom-right-radius: $chat-radius;
  position: relative;
}

.recent {
  position: absolute;
  left: 0;
  top: 0;
  box-sizing: border-box;
  width: 300px;
  height: 100%;
  padding: 20px 15px 20px 20px;
}

.chat-zone {
  width: 100%;
  height: 100%;
  box-sizing: border-box;
  padding: 20px 30px 20px 315px;
}
</style>
