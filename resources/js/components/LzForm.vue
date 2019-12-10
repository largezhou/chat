<template>
  <form
    ref="form"
    class="lz-form"
    :class="{ 'label-top': labelPos === 'top' }"
    v-bind="$attrs"
    @keydown.enter="onEnter"
  >
    <slot/>
  </form>
</template>

<script>
export default {
  provide() {
    return {
      form: this,
    }
  },
  name: 'LzForm',
  props: {
    errors: Object,
    label: {
      type: Boolean,
      default: true,
    },
    labelPos: String,
  },
  methods: {
    reset() {
      this.clearErrors()
      this.$refs.form.reset()
    },
    clearErrors() {
      this.$emit('update:errors', {})
    },
    onEnter(e) {
      const t = e.target
      window.t = t
      if (
        t.tagName === 'INPUT' &&
        !t.disabled &&
        !t.getAttribute('no-submit')
      ) {
        this.$emit('enter-submit')
      }
    },
  },
}
</script>

<style scoped lang="scss">
.lz-form {
  display: flex;
  flex-direction: column;
  align-items: center;
}
</style>
