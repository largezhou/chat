<template>
  <div
    class="modal-shade"
    v-show="shadeShow"
    @click.self="onClickShade"
  >
    <transition
      name="modal-scale"
      @enter="onEnter"
      @after-enter="onAfterEnter"
      @after-leave="onAfterLeave"
    >
      <div
        ref="modal"
        class="modal"
        :class="[customClass]"
        :style="styles"
        v-show="value"
        v-click-outside="onClickOutside"
      >
        <lz-button
          class="close"
          v-if="persistent"
          icon="svg-close"
          icon-size="16px"
          @click="onClose"
        />
        <div v-if="$slots.title" class="title" :class="{ 'has-sub': $slots['sub-title'] }">
          <slot name="title"/>
          <div v-if="$slots['sub-title']" class="sub-title">
            <slot name="sub-title"/>
          </div>
        </div>
        <slot/>
      </div>
    </transition>
  </div>
</template>

<script>
import { numToPixel } from '@/libs/utils'

export default {
  name: 'Modal',
  data() {
    return {
      top: null,
      left: null,
      shadeShow: this.value,
    }
  },
  props: {
    customClass: String,
    width: String,
    height: String,
    value: Boolean,
    attach: [String, Object],
    // top, left, right, bottom 两两组合，用段横线连接
    align: String,
    // top 偏移
    topOffset: String,
    persistent: Boolean,
  },
  computed: {
    styles() {
      return {
        width: numToPixel(this.width),
        height: numToPixel(this.height),
        top: this.realTop,
        left: numToPixel(this.left),
      }
    },
    alignPos() {
      return this.align ? this.align.split('-') : []
    },
    realTop() {
      const t = numToPixel(this.top)
      return this.topOffset ? `calc(${t} + ${this.topOffset})` : t
    },
  },
  methods: {
    async setPosFromAttach() {
      await this.$nextTick()

      if (this.topOffset && !this.attach) {
        this.top = this.topOffset
        return
      }

      const node = typeof this.attach === 'string'
        ? document.querySelector(this.attach)
        : this.attach
      const pos = node.getBoundingClientRect()

      for (const a of this.alignPos) {
        switch (a) {
          case 'right':
            this.left = pos.left - this.$refs.modal.offsetWidth + node.offsetWidth
            break
          case 'top':
            this.top = pos.top
            break
          case 'left':
            this.left = pos.left
            break
          case 'bottom':
            this.top = pos.top - this.$refs.modal.offsetHeight + node.offsetHeight
            break
          default:
          // do nothing
        }
      }

      // TODO 处理超出屏幕的情况

      // 如果没有设置左边位置，则居中
      if (this.left === null) {
        this.left = document.body.offsetWidth / 2 - this.$refs.modal.offsetWidth / 2
      }
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
      this.setAutoFocus()
      this.$emit('shown')
    },
    close() {
      this.value && this.$emit('input', false)
    },
    onClose() {
      this.close()
    },
    onClickOutside() {
      !this.persistent && this.close()
    },
    onClickShade() {
      this.onClickOutside()
    },
    onAfterLeave() {
      this.clearPos()
      this.shadeShow = false
      this.$emit('hidden')
    },
    setAutoFocus() {
      const el = this.$refs.modal.querySelector('[autofocus]')
      el && el.focus && el.focus()
    },
  },
  watch: {
    value: {
      handler(newVal) {
        if (newVal) {
          this.shadeShow = true
          this.setPosFromAttach()
        }
      },
      immediate: true,
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
  padding: 20px;
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

.title {
  color: #fff;
  font-size: 18px;
  padding-bottom: 26px;
  text-align: left;
  width: 100%;
  font-weight: 700;

  &.has-sub {
    padding-bottom: 10px;
  }

  .sub-title {
    font-size: 12px;
    color: #c1c1c1;
    font-weight: initial;
  }
}

.modal-shade {
  position: fixed;
  left: 0;
  top: 0;
  right: 0;
  bottom: 0;
  overflow: auto;
  z-index: 1000;
}

.close {
  cursor: pointer;
  width: 24px !important;
  height: 24px !important;
  position: absolute;
  right: -10px;
  top: -10px;
}
</style>
