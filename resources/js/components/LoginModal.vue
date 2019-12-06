<template>
  <modal
    v-bind="$attrs"
    v-on="$listeners"
  >
    <form class="login-form" autocomplete="off">
      <div class="title">登录</div>
      <label>
        <span v-show="false"/>
        <input
          v-model="form.email"
          placeholder="帐号"
          autofocus
        >
      </label>
      <label>
        <span v-show="false"/>
        <input
          v-model="form.password"
          type="password"
          placeholder="密码"
        >
      </label>
      <button
        type="button"
        class="btn w-100 login-btn"
        @click="onLogin"
      >
        登录
      </button>
    </form>
  </modal>
</template>

<script>
import { postLogin } from '@/api'

export default {
  name: 'LoginModal',
  data: () => ({
    form: {
      email: '',
      password: '',
    },
  }),
  methods: {
    async onLogin() {
      const res = await postLogin(this.form)
    },
  },
}
</script>

<style scoped lang="scss">
.login-form {
  width: 185px;
  display: flex;
  flex-direction: column;
  align-items: center;
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

label {
  display: flex;
  margin-bottom: 25px;

  & > span {
    flex-shrink: 0;
    font-size: 14px;
    line-height: 35px;
    color: #b8c3eb;
    text-align: right;
    margin-right: 16px;
  }
}

$input-height: 35px;
input {
  border: none;
  border-radius: 10px;
  height: $input-height;
  line-height: $input-height;
  padding: 10px;
  box-sizing: border-box;
  background: #12152f;
  color: #b8c3eb;
  display: inline-block;
  width: 100%;
  outline: none;
  transition: all .3s;
  font-size: 14px;

  &:focus {
    box-shadow: 0px 0px 0px 3px rgba(0, 102, 255, 0.4);
  }
}

.login-btn {
  margin-top: 16px;
}
</style>
