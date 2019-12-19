<template>
  <span class="online-indicate" :class="{ off }"/>
</template>

<script>
import { mapState } from 'vuex'

export default {
  name: 'OnlineIndicate',
  props: {
    id: [String, Number],
  },
  computed: {
    ...mapState({
      onlineFriendIds: state => state.onlineFriendIds,
    }),
    off() {
      return this.onlineFriendIds.indexOf(this.id) === -1
    },
  },
}
</script>

<style scoped lang="scss">
.online-indicate {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #00ff88;
  position: absolute;
  right: 3px;
  top: 3px;

  &.off {
    background: #c4c4c4;
  }
}
</style>
