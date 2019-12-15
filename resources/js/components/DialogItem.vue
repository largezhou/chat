<template>
  <div class="dialog-item" :class="{ me }">
    <avatar avatar="http://chat.l.com/uploads/61c1b32a961b0d868a78dae00e4997f9.png"/>
    <div class="content-main">
      <div class="content">
        你说啥呢你说啥呢你说啥呢你说啥呢你说啥呢你说啥呢你说啥呢你说啥呢你说啥呢你说啥呢你说啥呢你说啥呢你说啥呢你说啥呢你说啥呢你说啥呢你说啥呢
      </div>
      <span class="time">17:24</span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DialogItem',
  props: {
    me: Boolean,
  },
}
</script>

<style scoped lang="scss">
$dialog-radius: 15px;

.dialog-item {
  display: flex;
  flex-direction: row;
  padding-top: 20px;
  padding-bottom: 10px;
}

.content-main {
  margin-left: 15px;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
}

.content {
  color: #fff;
  font-size: 14px;
  width: 350px;
  background: linear-gradient(to right, #af53f9, #4c35ff);
  border-radius: $dialog-radius;
  box-shadow: 0 0 20px 8px #000;
  padding: 16px;
}

.time {
  font-size: 12px;
  color: #5e6e86;
  margin-top: 10px;
}

.dialog-item {
  &.me {
    flex-direction: row-reverse;

    .content-main {
      margin-left: 0px;
      margin-right: 15px;
      align-items: flex-start;
      background: initial;
    }

    .content {
      background: linear-gradient(to right, #00a1ff, #0e6efd);
      border-top-right-radius: 0px;
    }
  }

  &:not(.me) {
    .content {
      border-top-left-radius: 0px;
    }
  }
}
</style>
