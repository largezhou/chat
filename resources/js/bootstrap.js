window._ = require('lodash')
window.log = console.log.bind(console)

window.axios = require('axios')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
