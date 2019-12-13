<template>
  <el-card>
    <template #header>
      <content-header :name="contentHeader"/>
    </template>

    <el-button-group class="mb-3">
      <search-form :fields="search"/>
    </el-button-group>

    <el-table :data="friends" resource="user-friends">
      <el-table-column prop="id" label="ID" width="100"/>
      <el-table-column prop="user_id" label="邀请人 ID" width="100"/>
      <el-table-column prop="inviter.name" label="邀请人"/>
      <el-table-column prop="friend_id" label="被邀请人 ID" width="100"/>
      <el-table-column prop="invitee.name" label="被邀请人"/>
      <el-table-column prop="accepted" label="是否同意" width="100">
        <template #default="{ row, $index }">
          <el-tag :type="row.accepted ? 'success' : 'danger'">{{ row.accepted ? '是' : '否' }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column prop="created_at" label="添加时间" width="180"/>
      <el-table-column label="操作" width="80">
        <template #default="{ row, $index }">
          <el-button-group>
            <row-destroy/>
          </el-button-group>
        </template>
      </el-table-column>
    </el-table>
    <div class="card-footer">
      <pagination :page="page"/>
    </div>
  </el-card>
</template>

<script>
import SearchForm from '@c/SearchForm'
import Pagination from '@c/Pagination'
import { getUserFriends } from '@/api/user-friends'
import RowDestroy from '@c/LzTable/RowDestroy'

export default {
  name: 'Index',
  components: {
    RowDestroy,
    SearchForm,
    Pagination,
  },
  data() {
    return {
      search: [
        {
          field: 'friends_of',
          label: '谁的朋友',
        },
      ],
      friends: [],
      page: null,
    }
  },
  computed: {
    contentHeader() {
      const t = this.$route.query.friends_of
      return t ? `“${t}” 的好友` : ''
    },
  },
  watch: {
    $route: {
      async handler(newVal) {
        const { data: { data, meta } } = await getUserFriends(newVal.query)
        this.friends = data
        this.page = meta
      },
      immediate: true,
    },
  },
}
</script>
