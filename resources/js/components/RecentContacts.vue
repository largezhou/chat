<template>
  <div class="recent-items">
    <div class="recent-items-inner" ref="recentItemsInner">
      <recent-contact-item
        v-for="item of recentContacts"
        :key="item.id"
        :item="item"
      />
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import { getRecentContacts, storeRecentContact } from '@/api'
import { showIn } from '@/libs/utils'

export default {
  name: 'RecentContacts',
  data: () => ({
    recentContacts: [],
  }),
  computed: {
    ...mapState({
      user: state => state.user,
      recentContact: state => state.recentContact,
      target: state => state.target,
    }),
    recentIds() {
      return this.recentContacts.map(i => i.target.id)
    },
  },
  created() {
    this.getRecentContacts()
  },
  methods: {
    async getRecentContacts() {
      if (!this.user) {
        return
      }
      const { data } = await getRecentContacts()
      this.recentContacts = data
    },
  },
  watch: {
    async recentContact(newVal) {
      // 如果最近联系人栏里没有的话，就请求一下接口
      if (this.recentIds.indexOf(newVal.targetId) === -1) {
        await this.getRecentContacts()
      }

      const i = this.recentIds.indexOf(newVal.targetId)
      const t = this.recentContacts[i]
      t.msg = newVal.msg || '[图片]' // 消息的纯文本内容为空，则肯定是只发了张图片
      t.updated_at = this.dayjs().format('YYYY-MM-DD HH:mm:ss')

      // 如果当前聊天目标不是消息发送人的，则未读数 +1
      if (!this.target || (newVal.targetId !== this.target.id)) {
        let unread = (t.unread || 0) + 1
        unread = unread > 99 ? 99 : unread
        this.$set(t, 'unread', unread)
      }

      this.recentContacts.splice(i, 1)
      this.recentContacts.unshift(t)
    },
    async target(newVal) {
      if (!newVal) {
        return
      }

      let i = this.recentIds.indexOf(newVal.id)
      if (i === -1) {
        i = 0
        const { data } = await storeRecentContact(newVal.id)
        this.recentContacts.unshift(data)
      }

      await this.$nextTick()
      showIn(
        this.$refs.recentItemsInner,
        this.$refs.recentItemsInner.children[i],
      )
    },
  },
}
</script>

<style scoped lang="scss">
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
