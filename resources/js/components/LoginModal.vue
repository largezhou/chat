<template>
  <modal
    v-bind="$attrs"
    v-on="$listeners"
    @hidden="onHidden"
  >
    <lz-form
      ref="form"
      class="login-form"
      autocomplete="off"
      :errors.sync="errors"
    >
      <div class="title">登录</div>
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
        this.$store.commit('SET_USER', data)
        this.$emit('input', false)
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
      this.$refs.form.reset()
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
