<template>
  <div class="card" :class="cardStyles" @click="select">
    <div class="avatar-item">
      <div class="avatar">
        <img :src="item.avatar">
        <div class="online" :class="{ off: !item.online }"/>
      </div>
    </div>
    <div class="content">
      <div class="name">{{ item.name }}</div>
      <div class="msg">{{ item.recent_content }}</div>
    </div>
    <div class="indicate">
      <div class="time">{{ item.created_at }}</div>
      <div class="unread" v-show="item.unreads_count">{{ item.unreads_count }}</div>
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

.card {
  display: flex;
  width: 100%;
  background: #191e3f;
  height: 90px;
  margin: 8px 0;
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
    background: linear-gradient(to bottom right, #036fff, #16a1ff);

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
}

.avatar {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 50px;
  height: 50px;
  position: relative;

  img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
  }
}

.online {
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

$content-padding: 16px;
.content {
  padding: $content-padding 0px;
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

.indicate {
  padding-top: 12px;
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
  margin-top: 15px;
  color: #fff;
  width: 19px;
  height: 19px;
  border-radius: 50%;
  background: #0980fe;
  text-align: center;
  font-size: 14px;
}
</style>
