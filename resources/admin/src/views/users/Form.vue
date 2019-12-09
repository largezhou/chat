<template>
  <el-card>
    <template #header>
      <content-header/>
    </template>
    <el-row type="flex" justify="center">
      <lz-form
        ref="form"
        :get-data="getData"
        :submit="onSubmit"
        :errors.sync="errors"
        :form.sync="form"
        :edit-mode="editMode"
      >
        <el-form-item label="账号" required prop="username">
          <el-input v-model="form.username"/>
        </el-form-item>
        <el-form-item label="昵称" required prop="name">
          <el-input v-model="form.name"/>
        </el-form-item>
        <el-form-item label="头像" prop="avatar">
          <file-picker
            v-model="form.avatar"
            ext="jpg,gif,png,jpeg"
          />
        </el-form-item>
        <el-form-item label="密码" :required="!editMode" prop="password">
          <el-input
            v-model="form.password"
            type="password"
            autocomplete="new-password"
          />
        </el-form-item>
        <el-form-item label="确认密码" :required="!editMode" prop="password_confirmation">
          <el-input
            v-model="form.password_confirmation"
            type="password"
            autocomplete="new-password"
          />
        </el-form-item>
      </lz-form>
    </el-row>
  </el-card>
</template>

<script>
import LzForm from '@c/LzForm'
import FormHelper from '@c/LzForm/FormHelper'
import FilePicker from '@c/FilePicker'
import { storeUser, editUser, updateUser } from '@/api/users'

export default {
  name: 'Form',
  components: {
    FilePicker,
    LzForm,
  },
  mixins: [
    FormHelper,
  ],
  data() {
    return {
      form: {
        username: '',
        name: '',
        password: '',
        password_confirmation: '',
        avatar: '',
      },
      errors: {},
    }
  },
  methods: {
    async onSubmit() {
      if (this.editMode) {
        await updateUser(this.resourceId, this.form)
      } else {
        await storeUser(this.form)
      }
    },
    async getData() {
      let data

      if (this.editMode) {
        ({ data } = await editUser(this.resourceId))
        this.fillForm(data)
      }
    },
  },
}
</script>
