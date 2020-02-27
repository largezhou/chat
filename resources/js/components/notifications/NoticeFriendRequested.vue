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
import { updateNotification, updateUserFriend } from '@/api'

export default {
  name: 'NoticeFriendRequested',
  data: () => ({
    reading: false,
  }),
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
    async onRequest() {
      if (this.invalid) {
        return
      }

      const { data } = await updateUserFriend(
        this.data.data.user_friend_id,
        { accepted: 1 },
      )
      log(data)
    },
    async onMarkAsRead() {
      if (this.data.read_at || this.reading) {
        return
      }

      this.reading = true
      try {
        const { data } = await updateNotification(this.data.id, { read_at: 1 })
        this.data.read_at = data.read_at
      } finally {
        this.reading = false
      }
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
