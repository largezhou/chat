<template>
  <modal
    v-bind="$attrs"
    v-on="$listeners"
    width="360px"
  >
    <div class="notification-center-modal">
      <div class="loading" v-show="loading">
        <svg-ripple class="h-100"/>
      </div>
      <div class="notifications">
        <div v-for="item of items" :key="item.id">
          <component :is="`notice-${item.type}`" :data="item"/>
        </div>
      </div>
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
      const { data } = await getNotifications()
      this.items = data.data
      this.page = data.meta
    },
  },
}
</script>

<style scoped lang="scss">
.notification-center-modal {
  min-height: 110px;
  max-height: 300px;
  display: flex;
  flex-direction: column;
}

.actions {
  display: flex;
}

.loading {
  height: 75px;
}
</style>
