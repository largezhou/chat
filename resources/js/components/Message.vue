<template>
  <transition
    name="slide-in"
    @after-leave="onAfterLeave"
  >
    <div
      v-if="show"
      class="message"
      :class="{ [type]: true }"
    >
      <div v-if="html" v-html="msg"/>
      <span v-else>{{ msg }}</span>
      <span class="close" @click="onClose" v-if="!timeout">×</span>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'Message',
  data: () => ({
    show: false,
  }),
  props: {
    type: {
      type: String,
      default: '',
    },
    timeout: {
      type: [Number, String],
      default: 3000,
    },
    msg: String,
    /**
     * 消失之后的回调
     */
    onHidden: Function,
    html: Boolean,
  },
  mounted() {
    this.show = true
    if (this.timeout > 0) {
      this.hideSetTimout = setTimeout(() => {
        this.show = false
      }, this.timeout)
    }
  },
  beforeDestroy() {
    clearTimeout(this.hideSetTimout)
    this.$el.parentNode && this.$el.parentNode.removeChild(this.$el)
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
  },
}
</script>

<style scoped lang="scss">
.message {
  background: #019fff;
  position: fixed;
  border-radius: 10px;
  padding: 20px;
  max-width: 800px;
  min-width: 400px;
  min-height: 21px;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 1500;
  display: flex;
  align-items: center;
  box-shadow: 0 0 20px 0px rgba(0, 0, 0, 0.3);
  color: white;
  word-break: break-all;

  &.success {
    background: #67c23a;
  }

  &.error {
    background: #f56c6c;
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

.slide-in-enter-active,
.slide-in-leave-active {
  transition: all .3s;
}

.slide-in-enter,
.slide-in-leave-to {
  top: 0;
  opacity: 0;
}
</style>
