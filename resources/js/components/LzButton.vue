<template>
  <button
    class="btn"
    :class="{ loading, disabled, icon }"
    v-bind="$attrs"
    v-on="$listeners"
    :disabled="!active"
    :type="type"
    @click="onClick"
  >
    <svg-ripple class="loading" v-if="loading"/>
    <template v-else>
      <component v-if="icon" :is="icon" :style="{ width: iconSize }"/>
      <slot/>
    </template>
  </button>
</template>

<script>
export default {
  name: 'LzButton',
  data: () => ({
    loading: false,
    disabled: false,
  }),
  props: {
    type: {
      type: String,
      default: 'button',
    },
    action: Function,
    disableOnSuccess: {
      type: [String, Number],
      default: 500,
    },
    icon: String,
    iconSize: {
      type: String,
      default: '35px',
    },
  },
  computed: {
    active() {
      return !this.loading && !this.disabled
    },
  },
  beforeDestroy() {
    this.clearRecoverDisabledTimeout()
  },
  methods: {
    async onClick() {
      if (!this.action || !this.active) {
        return
      }
      this.loading = true
      try {
        await this.action()
        this.handleDisableOnSuccess()
      } finally {
        this.loading = false
      }
    },
    handleDisableOnSuccess() {
      if (this.disableOnSuccess > 0) {
        this.disabled = true
        this.clearRecoverDisabledTimeout()
        this.recoverDisabledTimeout = setTimeout(() => {
          this.disabled = false
        }, this.disableOnSuccess)
      }
    },
    clearRecoverDisabledTimeout() {
      this.recoverDisabledTimeout && clearTimeout(this.recoverDisabledTimeout)
    },
  },
}
</script>

<style scoped lang="scss">
.btn {
  font-size: 12px;
  border: none;
  height: 35px;
  border-radius: 18px;
  background: linear-gradient(to right, #00a1ff, #0066ff);
  color: #fff;
  padding: 0 15px;
  cursor: pointer;
  outline: none;
  transition: .3s;
  display: flex;
  align-items: center;
  justify-content: center;

  &:hover {
    background: linear-gradient(to right, #00a1ff, #00a1ff);
  }

  &:active {
    background: linear-gradient(to right, #0e6efd, #0066ff);
  }

  &:focus {
    box-shadow: 0px 0px 0px 3px rgba(0, 102, 255, 0.4);
  }

  &[disabled] {
    cursor: default;
    background: #00a1ff;
    transition: none;
  }

  &.disabled {
    cursor: not-allowed;
  }

  &.icon {
    width: 35px;
    padding: 0;
  }

  .loading {
    width: 35px;
  }
}
</style>
