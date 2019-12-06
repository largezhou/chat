<template>
  <transition
    name="modal-scale"
    @enter="onEnter"
    @after-enter="onAfterEnter"
    @after-leave="onAfterLeave"
  >
    <div
      class="modal"
      :class="[customClass]"
      :style="styles"
      v-show="value"
      v-click-outside="onClickOutside"
    >
      <slot/>
    </div>
  </transition>
</template>

<script>
import { getPos, numToPixel } from '@/utils'

export default {
  name: 'Modal',
  data: () => ({
    top: null,
    left: null,
  }),
  props: {
    customClass: String,
    width: String,
    height: String,
    value: Boolean,
    attach: [String, Object],
    align: {
      validator(val) {
        return [
          'left-top',
          'right-top',
          'left-bottom',
          'right-bottom',
        ].indexOf(val) !== -1
      },
    },
  },
  computed: {
    styles() {
      return {
        width: numToPixel(this.width),
        height: numToPixel(this.height),
        top: numToPixel(this.top),
        left: numToPixel(this.left),
      }
    },
    alignPos() {
      return this.align.split('-')
    },
  },
  methods: {
    async setPosFromAttach() {
      await this.$nextTick()
      const node = typeof this.attach === 'string'
        ? document.querySelector(this.attach)
        : this.attach
      const pos = getPos(node)

      for (const a of this.alignPos) {
        switch (a) {
          case 'right':
            this.left = pos.x - this.$el.offsetWidth + node.offsetWidth
            break
          case 'top':
            this.top = pos.y
            break
          case 'left':
            this.left = pos.x
            break
          case 'bottom':
            this.top = pos.y - this.$el.offsetHeight + node.offsetHeight
            break
          default:
          // do nothing
        }
      }
      // TODO 处理超出屏幕的情况
    },
    clearPos() {
      this.top = null
      this.left = null
    },
    onEnter(el) {
      el.style.transformOrigin = this.alignPos.join(' ')
    },
    onAfterEnter(el) {
      el.style.transformOrigin = ''
    },
    onClickOutside() {
      this.$emit('input', false)
    },
    onAfterLeave() {
      this.clearPos()
    },
  },
  watch: {
    value(newVal) {
      if (newVal) {
        this.setPosFromAttach()
      }
    },
  },
}
</script>

<style scoped lang="scss">
.modal {
  position: fixed;
  z-index: 1000;
  color: #fff;
  background: #20274f;
  border-radius: 10px;
  box-shadow: 0 0 20px 0 #000;
}

.modal-scale-enter-active,
.modal-scale-leave-active {
  transition: all .3s ease-in-out;
}

.modal-scale-enter {
  transform: scale(0);
}

.modal-scale-leave-to {
  opacity: 0;
}
</style>
