<template>
  <div class="contact-item">
    <avatar class="avatar" :avatar="contact.avatar" size="35px">
      <div class="online-indicate" :class="{ off: !contact.online }"/>
    </avatar>
    <span class="name ellipsis">{{ contact.name }}</span>
  </div>
</template>

<script>
export default {
  name: 'ContactItem',
  props: {
    contact: Object,
  },
}
</script>

<style scoped lang="scss">
.contact-item {
  display: flex;
  align-items: center;
  padding: 10px 20px 10px 30px;
  cursor: pointer;
  transition: all .3s;
  border-left: 5px solid transparent;

  &:hover {
    border-color: #0e8bff;
    background: linear-gradient(to right, #1a40ab, #2b346c);
    .name {
      color: #fff;
    }
  }
}

.avatar {
  position: relative;
}

.online-indicate {
  right: 0px;
}

.name {
  color: #5e6487;
  margin-left: 10px;
  font-weight: bold;
  font-size: 14px;
  transition: all .3s;
}
</style>
