<template>
  <div class="dialog-item" :class="{ me }">
    <avatar :avatar="(me ? user : target).avatar"/>
    <div class="content-main">
      <div ref="content" class="content" v-html="contentHTML"/>
      <from-now class="time" :time="this.msg.created_at"/>
      <transition name="status-out">
        <span
          v-if="msg.status"
          class="status"
          :title="error"
        >
          <component :is="statusIcon"/>
        </span>
      </transition>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import { MSG_STATUS } from '@/libs/constants'

export default {
  inject: ['chatMain'],
  name: 'DialogItem',
  props: {
    msg: Object,
  },
  computed: {
    ...mapState({
      user: state => state.user,
      target: state => state.target,
    }),
    me() {
      return this.msg.user_id === this.user.id
    },
    contentHTML() {
      return this.msg.content.map(i => {
        if (typeof i === 'string') {
          return i.replace(/\n/g, '<br>')
        } else if (i.type === 'image') {
          return `<img src="${this.url(i.data)}"/>`
        } else {
          return ''
        }
      }).join('')
    },
    statusIcon() {
      return `svg-msg-${this.msg.status}`
    },
    error() {
      return this.msg.status === MSG_STATUS.FAILED
        ? this.msg.error
        : ''
    },
  },
  mounted() {
    setTimeout(() => {
      if (this.msg.status === MSG_STATUS.PENDING) {
        this.msg.status = MSG_STATUS.FAILED
        this.$set(this.msg, 'error', '发送失败。')
      }
    }, 5 * 1000)

    this.initImgLoadedEvent()
  },
  methods: {
    initImgLoadedEvent() {
      this.$refs.content.querySelectorAll('img').forEach(i => {
        i.addEventListener('load', () => {
          this.chatMain.scrollToBottom()
        })
      })
    },
  },
  watch: {
    'msg.status'(newVal) {
      // 如果消息的状态变为发送成功，则在一小段时间后，让图标消失
      if (newVal === MSG_STATUS.OK) {
        setTimeout(() => {
          this.msg.status = null
          this.$store.commit('REMOVE_KEY_MSG_MAP', this.msg.key)
        }, 1000)
      }
    },
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
  position: relative;
}

.content {
  color: #fff;
  font-size: 14px;
  width: 350px;
  background: linear-gradient(to right, #af53f9, #4c35ff);
  border-radius: $dialog-radius;
  box-shadow: 0 0 20px 8px #000;
  padding: 16px;
  word-break: break-all;

  ::v-deep {
    img {
      max-width: 200px;
      max-height: 200px;
    }
  }
}

.time {
  font-size: 12px;
  color: #5e6e86;
  margin-top: 10px;
}

.status {
  position: absolute;
  top: 5px;
  right: -25px;
  width: 20px;
  height: 20px;
}

.me {
  .status {
    left: -25px;
    right: initial;
  }
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

.status-out-leave-active {
  transition: opacity .5s;
}

.status-out-leave-to {
  opacity: 0;
}
</style>
