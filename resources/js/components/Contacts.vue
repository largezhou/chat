<template>
  <div class="contacts">
    <div class="title">联系人</div>
    <div
      class="contact-item"
      v-for="user of friends"
      :key="user.id"
      @click="onSelectContact(user)"
    >
      <avatar class="avatar" :avatar="user.avatar" size="35px">
        <div class="online-indicate" :class="{ off: !user.online }"/>
      </avatar>
      <span class="name ellipsis">{{ user.name }}</span>
    </div>
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
    onSelectContact(user) {
      this.$emit('select-contact', user)
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
@import '~@s/_variables';

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

.contact-item {
  display: flex;
  align-items: center;
  padding: 10px 20px 10px 30px;
  cursor: pointer;
  transition: all .3s;
  border-left: 5px solid transparent;
  color: #5e6487;

  &:hover {
    border-color: #0e8bff;
    background: linear-gradient(to right, #1a40ab, #2b346c);
    color: #fff;
  }
}

.avatar {
  position: relative;
}

.online-indicate {
  right: 0px;
}

.name {
  margin-left: 10px;
  font-weight: bold;
  font-size: 14px;
}
</style>
