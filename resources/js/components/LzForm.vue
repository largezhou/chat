<template>
  <form
    ref="form"
    class="lz-form"
    :class="{ 'label-top': labelPos === 'top' }"
    v-bind="$attrs"
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
}
</script>

<style scoped lang="scss">
.lz-form {
  display: flex;
  flex-direction: column;
  align-items: center;
}
</style>
