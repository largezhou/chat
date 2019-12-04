<template>
  <div class="avatar" :style="styles">
    <img :src="avatar">
    <slot/>
  </div>
</template>

<script>
export default {
  name: 'Avatar',
  props: {
    avatar: String,
    width: [String, Number],
    height: [String, Number],
  },
  computed: {
    styles() {
      return {
        width: `${this.width}px`,
        height: `${this.height}px`,
      }
    },
  },
}
</script>

<style scoped lang="scss">
.avatar {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 50px;
  height: 50px;

  img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
  }
}
</style>
