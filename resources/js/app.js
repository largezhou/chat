import Vue from 'vue'

import '@/bootstrap'
import '@/components'

const app = new Vue({
  el: '#app',
})

if (process.env.NODE_ENV === 'development') {
  window.app = app
}
