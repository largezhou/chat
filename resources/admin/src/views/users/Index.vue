<template>
  <el-card>
    <template #header>
      <content-header/>
    </template>

    <el-button-group class="mb-3">
      <search-form :fields="search"/>
    </el-button-group>

    <el-table :data="users" resource="users">
      <el-table-column prop="id" label="ID" width="60"/>
      <el-table-column prop="username" label="账号"/>
      <el-table-column prop="name" label="昵称">
        <template #default="{ row }">
          <input-edit
            :id="row.id"
            field="name"
            :update="updateUser"
            v-model="row.name"
          />
        </template>
      </el-table-column>
      <el-table-column prop="created_at" label="添加时间" width="180"/>
      <el-table-column prop="updated_at" label="修改时间" width="180"/>
      <el-table-column label="操作" width="220">
        <template #default="{ row, $index }">
          <el-button-group>
            <button-link size="small" :to="`/user-friends?friends_of=${row.name}`">查看好友</button-link>
            <row-to-edit/>
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
import { getUsers, updateUser } from '@/api/users'
import RowDestroy from '@c/LzTable/RowDestroy'
import RowToEdit from '@c/LzTable/RowToEdit'
import InputEdit from '@c/quick-edit/InputEdit'
import ButtonLink from '@c/ButtonLink'

export default {
  name: 'Index',
  components: {
    InputEdit,
    RowToEdit,
    RowDestroy,
    SearchForm,
    Pagination,
    ButtonLink,
  },
  data() {
    return {
      search: [
        {
          field: 'id',
          label: 'ID',
        },
        {
          field: 'username',
          label: '账号',
        },
        {
          field: 'name',
          label: '昵称',
        },
      ],
      users: [],
      page: null,
    }
  },
  methods: {
    updateUser,
  },
  watch: {
    $route: {
      async handler(newVal) {
        const { data: { data, meta } } = await getUsers(newVal.query)
        this.users = data
        this.page = meta
      },
      immediate: true,
    },
  },
}
</script>
