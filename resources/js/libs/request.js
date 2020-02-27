import Axios from 'axios'
import { Message } from '@/libs/message'

const request = Axios.create({
  timeout: 60 * 1000,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
  },
})

request.interceptors.response.use(
  res => {
    return res
  },
  err => {
    const { response: res, config } = err

    if (res) {
      const { data } = res
      log(data)

      switch (res.status) {
        case 400:
          data.message && Message.error(data.message)
          break
        case 404:
          Message.error(data.message || '请求的地址不存在。')
          break
        case 419:
          Message.error({
            msg: '页面已过期，需要重新刷新。',
            onHidden() {
              location.reload()
            },
          })
          break
        case 422:
          config.showValidationError && Message.error(Object.values(data.errors)[0][0])
          break
        case 403:
          Message.error(`没有权限：${data.message}`)
          break
        default:
          Message.error(`服务器异常(code: ${res.status})`)
      }
    } else {
      Message.error('请求失败')
    }

    return Promise.reject(err)
  },
)

class Request {
  method
  args = []
  config = {}
  defaultConfig = {
    showValidationError: true,
  }

  /**
   * 请求方法中，有 data 参数的，config 需要放到第三个位置
   * @type {string[]}
   */
  methodsWithData = [
    'put', 'patch', 'post',
  ]

  /**
   * @param {string} method
   * @param {IArguments} args
   */
  constructor(method, args) {
    this.method = method
    this.args = Array.from(args)
  }

  then(resolve, reject) {
    try {
      const configPos = (this.methodsWithData.indexOf(this.method) !== -1) ? 2 : 1

      let args = this.args
      args[configPos] = Object.assign(
        {},
        args[configPos],
        this.defaultConfig,
        this.config,
      )

      resolve(request[this.method](...args))
    } catch (e) {
      reject(e)
    }
  }

  /**
   * @param {AxiosRequestConfig} config
   * @return {Request}
   */
  setConfig(config) {
    this.config = config
    return this
  }

  /**
   * @param {string} url
   * @param {AxiosRequestConfig} [config]
   * @return {Request}
   */
  static get(url, config) {
    return new Request('get', arguments)
  }

  /**
   * @param {string} url
   * @param {*} [data]
   * @param {AxiosRequestConfig} [config]
   * @return {Request}
   */
  static post(url, data, config) {
    return new Request('post', arguments)
  }

  /**
   * @param {string} url
   * @param {*} [data]
   * @param {AxiosRequestConfig} [config]
   * @return {Request}
   */
  static put(url, data, config) {
    return new Request('put', arguments)
  }
}

export default Request
