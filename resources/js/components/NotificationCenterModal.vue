<template>
  <modal
    v-bind="$attrs"
    v-on="$listeners"
    width="375px"
    custom-class="notification-center-modal"
  >
    <div class="notification-center">
      <div v-if="loading" class="loading">
        <svg-ripple class="h-100"/>
      </div>
      <div v-else-if="items.length" class="notifications">
        <component
          v-for="item of items"
          :key="item.id"
          :is="`notice-${item.type}`"
          :data="item"
        />
      </div>
      <div v-else class="empty">空空如也~</div>
      <div class="flex-spacer"/>
      <div class="actions">
        <div class="flex-spacer"/>
        <lz-button>全部标记已读</lz-button>
      </div>
    </div>
  </modal>
</template>

<script>
import { getNotifications } from '@/api'

export default {
  name: 'NotificationCenterModal',
  data: () => ({
    loading: false,
    items: [],
    page: null,
  }),
  created() {
    this.getItems()
  },
  methods: {
    async getItems() {
      try {
        this.loading = true
        const { data } = await getNotifications()
        this.items = data.data
        this.page = data.meta
      } finally {
        this.loading = false
      }
    },
  },
}
</script>

<style scoped lang="scss">
.notification-center {
  margin-top: 20px;
  border-top: 1px solid #12152f;
  display: flex;
  flex-direction: column;
  min-height: 130px;
  overflow: hidden;
}

.notifications {
  max-height: 300px;
  overflow-x: hidden;
  overflow-y: scroll;
  margin-right: -17px;
}

.empty {
  text-align: center;
  line-height: 75px;
  color: #c1c1c1;
}

.actions {
  display: flex;
  padding: 10px;
  border-top: 1px solid #12152f;
}

.loading {
  height: 75px;
}
</style>

<style lang="scss">
.notification-center-modal {
  padding: 0px !important;
}
</style>
