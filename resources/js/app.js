import Vue from 'vue'

import '@/bootstrap'
import '@/components'
import '@/directives'
import store from '@/store'
import '@/error-handle'

Vue.use(require('@/libs/utils').default)

const app = new Vue({
  store,
  el: '#app',
})

if (process.env.NODE_ENV === 'development') {
  window.app = app
}
