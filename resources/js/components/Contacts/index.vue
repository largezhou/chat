<template>
  <div class="contacts">
    <div class="title">联系人</div>
    <contact-item
      v-for="item of friends"
      :key="item.id"
      :contact="item"
    />
  </div>
</template>

<script>
import { getUserFriends } from '@/api'
import { mapState } from 'vuex'

export default {
  name: 'Contacts',
  data: () => ({
    friends: [],
  }),
  computed: {
    ...mapState({
      user: state => state.user,
    }),
  },
  methods: {
    async getUserFriends() {
      const { data } = await getUserFriends()
      this.friends = data || []
    },
  },
  watch: {
    user: {
      handler(user) {
        if (user) {
          this.getUserFriends()
        } else {
          this.friends = []
        }
      },
      immediate: true,
    },
  },
}
</script>

<style scoped lang="scss">
@import '~@/../sass/_variables';

.contacts {
  box-sizing: border-box;
  width: 240px;
  flex-shrink: 0;
  background: #212950;
  border-bottom-left-radius: $chat-radius;
  padding: 20px 0px;
  overflow-y: scroll;
  overflow-x: hidden;
  margin-right: -17px;
}

.title {
  color: #c1c1c1;
  font-size: 18px;
  font-weight: bold;
  padding-bottom: 10px;
  padding-left: 35px;
}
</style>
