<template>
  <div class="recent-contact-item" :class="cardStyles" @click="select">
    <div class="avatar-item">
      <avatar :avatar="item.target.avatar">
        <online-indicate :id="item.target_id"/>
      </avatar>
    </div>
    <div class="name-msg">
      <div class="name ellipsis">{{ item.target.name }}</div>
      <div class="msg">{{ item.msg }}</div>
    </div>
    <div class="time-unread">
      <from-now class="time" :time="this.item.updated_at"/>
      <div class="unread" v-show="item.unread">{{ item.unread }}</div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  name: 'RecentContactItem',
  props: {
    item: Object,
  },
  computed: {
    cardStyles() {
      return {
        active: this.target && (this.target.id === this.item.target_id),
      }
    },
    ...mapState({
      target: state => state.target,
    }),
  },
  methods: {
    select() {
      this.$set(this.item, 'unread', 0)
      this.$store.commit('SET_TARGET', this.item.target)
    },
  },
}
</script>

<style scoped lang="scss">
$card-radius: 6px;

.recent-contact-item {
  display: flex;
  width: calc(100% - 20px);
  background: #191e3f;
  height: 96px;
  border-radius: $card-radius;
  cursor: pointer;
  box-shadow: 0px 0px 5px 2px #0d0f1d;
  transition: all .2s ease-in-out;
  margin: 10px 0px;

  &:hover:not(.active) {
    transform: scale(1.03);
    box-shadow: 0px 0px 8px 4px rgba(0, 0, 0, 0.4), 0px 0px 15px 15px rgba(0, 0, 0, 0.5);
    background: #20274f;
  }

  &.active {
    width: 100%;
    background: linear-gradient(to right, #036fff, #16a1ff);

    .msg {
      color: #475180;
    }
  }
}

.avatar-item {
  width: 70px;
  flex-shrink: 0;
  border-radius: $card-radius;
  display: flex;
  justify-content: center;
  align-items: center;

  ::v-deep .avatar {
    position: relative;
  }
}

.name-msg {
  width: 100%;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  padding: 20px 0px;
}

.name {
  color: #fff;
  flex-shrink: 0;
}

.msg {
  word-break: break-all;
  color: #5f6688;
  font-size: 13px;
  height: 100%;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

.time-unread {
  width: 70px;
  flex-shrink: 0;
  border-radius: $card-radius;
  display: flex;
  align-items: center;
  flex-direction: column;
  padding-top: 10px;
}

.time {
  font-size: 12px;
  color: #fff;
}

.unread {
  color: #fff;
  width: 19px;
  height: 19px;
  border-radius: 50%;
  background: #0980fe;
  text-align: center;
  font-size: 14px;
  margin-top: 16px;
}
</style>
