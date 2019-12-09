<template>
  <input
    v-bind="$attrs"
    @input="onInput"
    :type="type"
  >
</template>

<script>
export default {
  inject: ['formItem'],
  name: 'LzInput',
  props: {
    value: [String, Number],
    type: String,
  },
  mounted() {
    this.formItem.registerInput(this)
  },
  methods: {
    onInput(e) {
      this.$emit('input', e.target.value)
    },
  },
}
</script>

<style scoped lang="scss">
$input-height: 35px;
input {
  border: none;
  border-radius: 10px;
  height: $input-height;
  line-height: $input-height;
  padding: 10px;
  box-sizing: border-box;
  background: #12152f;
  color: #b8c3eb;
  display: inline-block;
  width: 100%;
  outline: none;
  transition: all .3s;
  font-size: 14px;

  &:focus {
    box-shadow: 0px 0px 0px 3px rgba(0, 102, 255, 0.4);
  }
}

.has-error {
  input:focus {
    box-shadow: 0px 0px 0px 3px #f56c6c;
  }
}
</style>
