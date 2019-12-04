<template>
  <div class="recent-contact-item my-1" :class="cardStyles" @click="select">
    <div class="avatar-item">
      <avatar :avatar="item.avatar">
        <div class="online-indicate" :class="{ off: !item.online }"/>
      </avatar>
    </div>
    <div class="name-msg py-2">
      <div class="name">{{ item.name }}</div>
      <div class="msg">{{ item.recent_content }}</div>
    </div>
    <div class="time-unread pt-1">
      <div class="time">{{ item.created_at }}</div>
      <div class="unread mt-2" v-show="item.unreads_count">{{ item.unreads_count }}</div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RecentContactItem',
  props: {
    item: Object,
  },
  computed: {
    cardStyles() {
      const { active: a } = this.item
      return {
        active: a,
      }
    },
  },
  created() {
    this.$root.$on('recent-selected', (id) => {
      this.$set(this.item, 'active', this.item.id === id)
    })
  },
  methods: {
    select() {
      this.$root.$emit('recent-selected', this.item.id)
    },
  },
}
</script>

<style scoped lang="scss">
$card-radius: 6px;

.recent-contact-item {
  display: flex;
  width: 100%;
  background: #191e3f;
  height: 90px;
  border-radius: $card-radius;
  cursor: pointer;
  box-shadow: 0px 0px 5px 2px #0d0f1d;
  transition: all .2s ease-in-out;

  &:hover:not(.active) {
    transform: scale(1.03);
    box-shadow: 0px 0px 8px 4px rgba(0, 0, 0, 0.4), 0px 0px 15px 15px rgba(0, 0, 0, 0.5);
    background: #20274f;
  }

  &.active {
    width: calc(100% + 20px);
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
}

.name {
  color: #fff;
}

.msg {
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
  width: 60px;
  flex-shrink: 0;
  border-radius: $card-radius;
  display: flex;
  align-items: center;
  flex-direction: column;
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
}
</style>
