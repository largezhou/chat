<template>
  <div v-if="target" class="dialog-main">
    <div class="dialog-header">
      <avatar :avatar="target.avatar" size="60px"/>
      <div class="target-name">
        <span>{{ target.name }}</span>
        <online-indicate :id="target.id"/>
      </div>
    </div>
    <div class="dialogs-main" ref="dialogsMain">
      <dialog-item
        v-for="msg of dialogs"
        :key="msg.id || msg.key"
        :msg="msg"
      />
    </div>
    <div class="msg-input-main">
      <editor
        ref="editor"
        class="msg-input"
        v-model="msg"
        @send="onSend"
      />
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import { getContentText, makeDialogKey, randomChars } from '@/libs/utils'
import { MSG_STATUS } from '@/libs/constants'
import { getFriendsMsgs } from '@/api'

export default {
  provide() {
    return {
      chatMain: this,
    }
  },
  name: 'ChatMain',
  data: () => ({
    msg: '',
  }),
  computed: {
    ...mapState({
      user: state => state.user,
      target: state => state.target,
    }),
    dialogKey() {
      return (this.user && this.target)
        ? makeDialogKey(this.user.id, this.target.id)
        : null
    },
    dialogs() {
      return this.$store.getters.getDialogsByKey(this.dialogKey)
    },
    recentMsg() {
      return this.dialogs.length > 0
        ? this.dialogs[this.dialogs.length - 1]
        : null
    },
  },
  methods: {
    async onSend(content) {
      const data = {
        key: randomChars(),
        target: this.target.id,
        content,
      }
      chat.send(ChatEventEnum.MSG, data)

      const msg = {
        user_id: this.user.id,
        target_id: this.target.id,
        content,
        created_at: this.dayjs().format('YYYY-MM-DD HH:mm:ss'),
        status: MSG_STATUS.PENDING,
        key: data.key,
      }

      this.$store.commit('PUSH_DIALOG', {
        key: this.dialogKey,
        msg,
      })

      this.$store.commit('SET_KEY_MSG_MAP', msg)
      this.$store.commit('SET_RECENT_CONTACT', {
        targetId: this.target.id,
        msg: getContentText(content),
      })

      this.msg = ''
      this.$refs.editor.focus()
    },
    async scrollToBottom() {
      await this.$nextTick()
      const el = this.$refs.dialogsMain
      el.scrollTop = el.scrollHeight - el.offsetHeight
    },
    atBottom() {
      const el = this.$refs.dialogsMain
      return el && (el.scrollTop === el.scrollHeight - el.offsetHeight)
    },
  },
  watch: {
    dialogs(newVal, oldVal) {
      // 实现如果自己往上滚动了滚动条，查看历史消息时，
      // 有新消息进来，不会被滚动到最底部，
      // 但是如果新消息是自己发送的，则依然滚动到最底部
      if (
        (newVal === oldVal) &&
        (this.atBottom() || (this.recentMsg.user_id === this.user.id))
      ) {
        this.scrollToBottom()
      }
    },
    async target(newVal) {
      const key = this.dialogKey
      if (newVal && !this.$store.state.dialogs[key]) {
        const { data } = await getFriendsMsgs(this.target.id)
        this.$store.commit('SET_DIALOGS', {
          key,
          dialogs: data.data.reverse(),
        })
      }

      // 切换聊天目标时，总是滚到最底下
      this.scrollToBottom()
    },
  },
}
</script>

<style scoped lang="scss">
.dialog-main {
  width: 100%;
  height: 100%;
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
}

.dialog-header {
  display: flex;
  height: 90px;
  border-bottom: 1px solid #293055;
  align-items: center;
  flex-shrink: 0;
}

.dialogs-main {
  overflow-x: hidden;
  overflow-y: scroll;
  height: calc(100% - 90px - 100px);
  margin-right: -17px;
  padding-right: 30px;
}

.msg-input-main {
  height: 100px;
  margin: 20px 30px 0px 0px;
}

.target-name {
  color: #fff;
  position: relative;
  padding: 0px 0px 16px 8px;

  ::v-deep .online-indicate {
    right: -20px;
    width: 10px;
    height: 10px;
  }
}
</style>
