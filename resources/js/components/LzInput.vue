<template>
  <input
    class="input"
    v-bind="$attrs"
    @input="onInput"
    :type="type"
    :no-submit="noSubmit"
  >
</template>

<script>
export default {
  inject: {
    formItem: {
      default: null,
    },
  },
  name: 'LzInput',
  props: {
    value: [String, Number],
    type: String,
    /**
     * 在 LzForm 表单中，并且聚焦时，按下回车，是否触发表单的 enter-submit 事件
     */
    noSubmit: Boolean,
  },
  mounted() {
    this.formItem && this.formItem.registerInput(this)
  },
  methods: {
    onInput(e) {
      this.$emit('input', e.target.value)
    },
  },
}
</script>

<style scoped lang="scss">
@import '~@s/_input';
</style>
