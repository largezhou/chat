<template>
  <div class="form-item" :class="{ 'has-error': error }">
    <label>
      <span class="label" v-if="showLabel && label">{{ label }}</span>
      <span class="content">
        <slot/>
        <transition name="error-zoom-in">
          <span v-if="error" class="error ellipsis" :title="error">{{ error }}</span>
        </transition>
      </span>
    </label>
  </div>
</template>

<script>
export default {
  provide() {
    return {
      formItem: this,
    }
  },
  inject: ['form'],
  name: 'FormItem',
  data: () => ({
    input: null,
  }),
  props: {
    prop: String,
    label: String,
  },
  computed: {
    showLabel() {
      return !!this.form.label
    },
    error() {
      return this.form.errors[this.prop] || ''
    },
  },
  methods: {
    registerInput(input) {
      this.input = input
      this.input.$on('input', () => {
        this.prop && (this.form.errors[this.prop] = '')
      })
    },
  },
}
</script>

<style scoped lang="scss">
.form-item {
  margin-bottom: 25px;
}

.content {
  position: relative;
}

.error {
  font-size: 12px;
  position: absolute;
  color: #f56c6c;
  padding-top: 3px;
  left: 0;
  top: 100%;
  width: 100%;
}

.error-zoom-in-enter-active,
.error-zoom-in-leave-active {
  opacity: 1;
  transform: scaleY(1);
  transition: all .2s;
  transform-origin: center top;
}

.error-zoom-in-enter,
.error-zoom-in-leave-active {
  opacity: 0;
  transform: scaleY(0);
}

label {
  display: flex;

  .label {
    flex-shrink: 0;
    font-size: 14px;
    line-height: 35px;
    color: #b8c3eb;
    text-align: right;
    margin-right: 16px;
    cursor: pointer;
  }
}

.label-top {
  label {
    flex-direction: column;

    .label {
      width: 100%;
      text-align: left;
      margin-right: 0px;
    }
  }
}
</style>
