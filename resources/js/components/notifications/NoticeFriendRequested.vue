<template>
  <span>
    来自
    <span v-if="inviterName" v-text="inviterName"/>
    <span v-else class="invalid-user">[无效用户]</span>
    的好友申请
  </span>
</template>

<script>
export default {
  name: 'NoticeFriendRequested',
  props: {
    data: Object,
  },
  computed: {
    inviterName() {
      return this.data.data.inviter_name
    },
  },
}
</script>

<style scoped lang="scss">
.invalid-user {
  color: #c1c1c1;
}
</style>
