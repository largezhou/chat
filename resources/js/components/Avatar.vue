<template>
  <div class="avatar" :style="styles">
    <img v-if="avatar" :src="url(avatar)">
    <svg-avatar v-else/>
    <slot/>
  </div>
</template>

<script>
export default {
  name: 'Avatar',
  props: {
    avatar: String,
    size: String,
  },
  computed: {
    styles() {
      const s = this.size
      return {
        width: s,
        height: s,
        minWidth: s,
        minHeight: s,
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
