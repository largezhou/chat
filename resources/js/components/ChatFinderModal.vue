<template>
  <modal
    v-bind="$attrs"
    v-on="$listeners"
    class="chat-finder-modal"
    width="550px"
  >
    <template #title>查找用户</template>
    <lz-input v-model="q" placeholder="搜索 用户名 或 昵称..." autofocus/>
    <div class="list-main">
      <div v-if="items.length" class="list">
        <div
          v-for="item of items"
          :key="item.id"
          class="item"
        >
          <avatar :avatar="item.avatar"/>
          <div class="info">
            <div class="ellipsis name" :title="item.name">{{ item.name }}</div>
            <div class="ellipsis username" :title="item.username">{{ item.username }}</div>
          </div>
          <div v-if="!item.applied" class="add">
            <lz-button
              icon="svg-user-plus"
              icon-size="24px"
              :action="() => onAdd(item)"
            />
          </div>
        </div>
      </div>
      <div v-show="showSearching" class="searching">
        <svg-ripple/>
      </div>
      <div v-show="showEmpty" class="empty">空空如也~</div>
    </div>
  </modal>
</template>

<script>
import { getUsers, storeUserFriend } from '@/api'
import _debounce from 'lodash/debounce'

export default {
  name: 'ChatFinderModal',
  data: () => ({
    q: '',
    items: [],
    searching: false,
  }),
  created() {
    this.debounceGetUsers = _debounce(async () => {
      try {
        const { data } = await getUsers(this.q.trim())
        this.items = data
      } finally {
        this.searching = false
      }
    }, 300)
  },
  computed: {
    /**
     * 正在搜索，且列表为空
     */
    showSearching() {
      return this.searching && (this.items.length === 0)
    },
    /**
     * 有搜索关键词，但是没有搜到任何东西
     */
    showEmpty() {
      return Boolean(
        this.q.trim() &&
        (this.items.length === 0) &&
        !this.searching,
      )
    },
  },
  methods: {
    async onAdd(item) {
      await storeUserFriend(item.id)
      this.$set(item, 'applied', true)
      alert('已申请。')
    },
  },
  watch: {
    q(newVal) {
      if (newVal.trim()) {
        this.searching = true
        this.debounceGetUsers()
      } else {
        this.searching = false
        this.debounceGetUsers.cancel()
      }
    },
  },
}
</script>

<style scoped lang="scss">
.list-main {
  margin-top: 20px;
  overflow: hidden;
  border-radius: 10px;
  background: #12152f;
}

.list {
  margin-right: -17px;
  max-height: 400px;
  min-height: 80px;
  overflow-y: scroll;
  display: flex;
  flex-wrap: wrap;
}

.item {
  box-sizing: border-box;
  padding: 15px;
  width: 49%;
  display: flex;

  > * {
    flex-shrink: 0;
  }
}

.info {
  overflow: hidden;
  color: #c1c1c1;
  margin: 0 10px;
  flex-shrink: 1;
  flex-grow: 1;
}

.add {
  display: flex;
  align-items: center;
}

.empty {
  color: #c1c1c1;
  text-align: center;
  padding: 29.5px 0;
}

.searching {
  height: 80px;

  ::v-deep svg {
    height: 100%;
  }
}
</style>
