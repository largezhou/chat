<template>
  <modal
    v-bind="$attrs"
    v-on="$listeners"
    @hidden="onHidden"
    width="185px"
  >
    <template #title>登录</template>
    <template #sub-title>新用户自动注册</template>

    <lz-form
      ref="form"
      autocomplete="off"
      :errors.sync="errors"
      @enter-submit="onSubmit"
    >
      <form-item prop="username">
        <lz-input
          v-model="form.username"
          placeholder="用户名"
          autofocus
        />
      </form-item>
      <form-item prop="password">
        <lz-input
          v-model="form.password"
          type="password"
          placeholder="密码"
        />
      </form-item>
      <lz-button
        ref="loginBtn"
        class="w-100 login-btn"
        :action="onLogin"
      >
        登录
      </lz-button>
    </lz-form>
  </modal>
</template>

<script>
import { postLogin } from '@/api'
import { handleValidateErrors } from '@/libs/utils'

export default {
  name: 'LoginModal',
  data: () => ({
    form: {
      username: '',
      password: '',
    },
    errors: {},
  }),
  methods: {
    async onLogin() {
      this.errors = {}
      try {
        const { data } = await postLogin(this.form)
        if (data.password) {
          this.$message.success(`注册成功，密码为：[ ${data.password} ]`)
        }
        this.$emit('input', false)
        location.reload()
      } catch (e) {
        const res = e.response
        if (res.status === 422 || res.status === 429) {
          this.errors = handleValidateErrors(res)
        } else {
          throw e
        }
      }
    },
    onHidden() {
      this.form = {}
      this.$refs.form.reset()
    },
    onSubmit() {
      this.$refs.loginBtn.onClick()
    },
  },
}
</script>

<style scoped lang="scss">
.login-btn {
  margin-top: 16px;
}
</style>
