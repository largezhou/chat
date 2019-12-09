<template>
  <modal
    v-bind="$attrs"
    v-on="$listeners"
  >
    <lz-form
      class="login-form"
      autocomplete="off"
      :errors.sync="errors"
    >
      <div class="title">登录</div>
      <form-item prop="email">
        <lz-input
          v-model="form.email"
          placeholder="帐号"
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
      <button
        type="button"
        class="btn w-100 login-btn"
        @click="onLogin"
      >
        登录
      </button>
    </lz-form>
  </modal>
</template>

<script>
import { postLogin } from '@/api'
import { handleValidateErrors } from '@/utils'

export default {
  name: 'LoginModal',
  data: () => ({
    form: {
      email: '',
      password: '',
    },
    errors: {},
  }),
  methods: {
    async onLogin() {
      this.errors = {}
      try {
        const res = await postLogin(this.form)
        log(res)
      } catch (e) {
        const res = e.response
        if (res.status === 422) {
          this.errors = handleValidateErrors(res)
        } else {
          throw e
        }
      }
    },
  },
}
</script>

<style scoped lang="scss">
.login-form {
  width: 185px;
  padding: 20px;
}

.title {
  color: #fff;
  font-size: 18px;
  padding-bottom: 26px;
  text-align: left;
  width: 100%;
  font-weight: 700;
}

.login-btn {
  margin-top: 16px;
}
</style>
