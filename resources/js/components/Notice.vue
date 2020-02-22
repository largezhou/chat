<template>
  <transition name="slide-in" @after-leave="onAfterLeave">
    <div
      v-if="show"
      class="notice"
      :style="{ top: `${top}px` }"
      :class="{ error }"
      @mouseenter="onStopCountDown"
      @mouseleave="stopCountDown = false"
    >
      <div v-if="html" v-html="msg"/>
      <span v-else>{{ msg }}</span>
      <span class="close" @click="onClose">×</span>
    </div>
  </transition>
</template>

<script>
import Vue from 'vue'

const noticeQueue = new Vue({
  data: {
    queue: [],
  },
})

export default {
  name: 'Notice',
  data() {
    return {
      show: false,
      countDownLeft: this.timeout,
      stopCountDown: false,
    }
  },
  props: {
    error: Boolean,
    timeout: {
      type: Number,
      default: 3000,
    },
    msg: String,
    /**
     * 消失之后的回调
     */
    onHidden: Function,
    html: Boolean,
  },
  computed: {
    queueIndex() {
      return noticeQueue.queue.indexOf(this)
    },
    top() {
      const i = this.queueIndex
      let offset

      if (i === 0) {
        offset = 0
      } else {
        const pre = noticeQueue.queue[i - 1]
        offset = pre.top + pre.$el.offsetHeight
      }

      return offset + 10
    },
  },
  mounted() {
    this.show = true
    noticeQueue.queue.push(this)
    this.countDown()
  },
  beforeDestroy() {
    this.$el.parentNode && this.$el.parentNode.removeChild(this.$el)
  },
  destroyed() {
    const a = noticeQueue.queue
    a.splice(a.indexOf(this), 1)
  },
  methods: {
    onAfterLeave() {
      try {
        this.onHidden && this.onHidden()
      } finally {
        this.$destroy()
      }
    },
    onClose() {
      this.show = false
    },
    countDown() {
      if (!this.timeout || this.stopCountDown) {
        return
      }

      if (this.countDownLeft > 0) {
        this.countDownLeft -= 50
        this.countDownTimeout = setTimeout(this.countDown, 50)
      } else {
        this.show = false
      }
    },
    onStopCountDown() {
      this.stopCountDown = true
      this.countDownLeft = this.countDownLeft > 1000
        ? this.countDownLeft
        : 1000
    },
  },
  watch: {
    stopCountDown(newVal) {
      if (newVal) {
        clearTimeout(this.countDownTimeout)
      } else {
        this.countDown()
      }
    },
  },
}
</script>

<style scoped lang="scss">
@import "~@s/_variables.scss";

.notice {
  background: $color-info;
  position: fixed;
  right: 20px;
  z-index: 2000;
  width: 250px;
  border-radius: 10px;
  padding: 20px;
  color: white;
  display: flex;
  align-items: center;
  box-shadow: 0 0 20px 0px rgba(0, 0, 0, 0.3);
  word-break: break-all;
  transition: top .3s;

  &.error {
    background: $color-error;
  }
}

.close {
  position: absolute;
  right: 2px;
  top: 2px;
  font-size: 24px;
  width: 20px;
  height: 20px;
  line-height: 20px;
  text-align: center;
  cursor: pointer;
  transition: all .3s;

  &:hover {
    color: #c1c1c1;
  }
}

.slide-in-enter {
  right: -310px;
}

.slide-in-enter-active,
.slide-in-leave-active {
  transition: all .3s;
}

.slide-in-leave-to {
  opacity: 0;
}

</style>
