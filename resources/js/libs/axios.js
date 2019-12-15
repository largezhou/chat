import Axios from 'axios'

const axios = Axios.create({
  timeout: 60 * 1000,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
  },
})

axios.interceptors.response.use(
  res => {
    return res
  },
  err => {
    return Promise.reject(err)
  },
)

export default axios
