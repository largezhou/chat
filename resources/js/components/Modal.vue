<template>
  <div class="modal" :class="[customClass]" :style="styles">
    <slot/>
  </div>
</template>

<script>
export default {
  name: 'Modal',
  props: {
    customClass: String,
    width: String,
    height: String,
  },
  computed: {
    styles() {
      return {
        width: this.width,
        height: this.height,
      }
    },
  },
}
</script>

<style scoped lang="scss">
.modal {
  color: #fff;
  background: #20274f;
  border-radius: 10px;
  box-shadow: 0 0 20px 0 #000;
}
</style>
