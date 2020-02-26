<template>
  <div
    class="notification"
    :class="{ unread: !data.read_at }"
    @click="onMarkAsRead"
  >
    <span
      class="action"
      :class="{ invalid }"
      @click="onViewUser"
    >{{ inviterName || '[无效用户]' }}</span>
    <span>想要添加你为好友</span>
    <span
      v-if="!invalid"
      class="action"
      @click="onRequest"
    >同意</span>
  </div>
</template>

<script>
export default {
  name: 'NoticeFriendRequested',
  props: {
    data: Object,
  },
  computed: {
    inviterName() {
      return this.data.data.inviter_name
    },
    invalid() {
      return !this.inviterName
    },
  },
  methods: {
    onRequest() {
      if (this.invalid) {
        return
      }

      log('同意')
    },
    onMarkAsRead() {
      if (this.data.read_at) {
        return
      }

      log('标记为已读')
    },
    onViewUser() {
      if (this.invalid) {
        return
      }

      log('查看用户')
    },
  },
}
</script>

<style scoped lang="scss">
@import '~@s/notification';

.action {
  cursor: pointer;
  color: #0e6efd;
}

.invalid {
  cursor: initial;
  color: #c1c1c1;
}
</style>
