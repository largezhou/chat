import Vue from 'vue'

import '@/bootstrap'
import '@/components'
import '@/directives'
import store from '@/store'

Vue.use(require('@/libs/utils').default)

const app = new Vue({
  store,
  el: '#app',
})

if (process.env.NODE_ENV === 'development') {
  window.app = app
}
