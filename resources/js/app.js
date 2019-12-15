import Vue from 'vue'

import '@/bootstrap'
import '@/components'
import '@/directives'
import store from '@/store'
import { jsonParse } from '@/libs/utils'

const app = new Vue({
  store,
  el: '#app',
  mounted() {
    this.initUser()
  },
  methods: {
    initUser() {
      this.$store.commit(
        'SET_USER',
        jsonParse(this.$el.dataset.user, null),
      )
    },
  },
})

if (process.env.NODE_ENV === 'development') {
  window.app = app
}
