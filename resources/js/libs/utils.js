import _trim from 'lodash/trim'
import store from '@/store'
import _dayjs from 'dayjs'
import 'dayjs/locale/zh-cn'

_dayjs.extend(require('dayjs/plugin/relativeTime'))
  .locale('zh-cn')

export default (Vue, options) => {
  // 需要放到 vue 实例中的方法名
  const methods = {
    url,
    dayjs,
  }

  Object.keys(methods).forEach(key => {
    const m = methods[key]
    Vue[key] = m
    Vue.prototype[key] = m
  })
}

/**
 * 把值转成带像素单位的值
 *
 * @param num
 * @param unit
 * @returns {string|null}
 */
export const numToPixel = (num, unit = 'px') => {
  if ([null, undefined, ''].indexOf(num) !== -1) {
    return null
  }

  num = String(num)
  const units = ['px', '%']
  if (units.some(i => num.endsWith(i))) {
    return num
  }

  return num + unit
}

/**
 * 把 laravel 返回的错误消息，处理成每个字段只有一条
 *
 * @param res 响应
 * @return {{}}
 */
export const handleValidateErrors = (res) => {
  let errors = {}
  if (res && (res.status === 422 || res.status === 429)) {
    ({ errors } = res.data)
    if (!errors) {
      return {}
    }
    Object.keys(errors).forEach((k) => {
      errors[k] = errors[k][0]
    })
  }

  return errors
}

/**
 * 避免 json 解析报错
 * @param {string} s
 * @param defaultVal
 * @returns
 */
export const jsonParse = (s, defaultVal = null) => {
  try {
    return JSON.parse(s)
  } catch (e) {
    return defaultVal
  }
}

let scrollToRequestId = {}
/**
 * 把 el 滚动到 top 的位置
 *
 * @param {Element} el
 * @param {int} top
 * @param {int} speed 正整数
 */
export const scrollTo = (el, top, speed = 30) => {
  // 先取消之前的，避免乱点导致混乱
  scrollToRequestId[el] && cancelAnimationFrame(scrollToRequestId[el])

  const offset = top - el.scrollTop
  if (!offset) {
    return
  }

  const sign = offset / Math.abs(offset)
  speed *= sign

  const step = () => {
    el.scrollTop += speed

    if (sign * el.scrollTop < sign * top) {
      scrollToRequestId[el] = requestAnimationFrame(step)
    }
  }

  requestAnimationFrame(step)
}

/**
 * 滚动 wrap 到正好显示 tar
 *
 * @param {Element} wrap
 * @param {Element} tar
 */
export const showIn = (wrap, tar) => {
  const tarRect = tar.getBoundingClientRect()
  const wrapRect = wrap.getBoundingClientRect()
  const { marginTop: mt, marginBottom: mb } = getComputedStyle(tar)
  const tarTop = tarRect.top - wrapRect.top - parseInt(mt)

  let top
  if (tarTop < 0) {
    top = 0
  }

  if ((tarTop + tarRect.height + parseInt(mt) + parseInt(mb)) > wrapRect.height) {
    top = wrap.scrollHeight - wrapRect.height
  }

  if (top === undefined) {
    return
  }

  scrollTo(wrap, top)
}

/**
 * 返回 path 的全地址
 *
 * @param {string} path
 * @returns {string}
 */
export const url = path => {
  if (!path) {
    return ''
  }

  if (path.startsWith('http')) {
    return path
  }

  return _trim(store.getters.getConfig('cdn_domain'), '/') + '/' + _trim(path)
}

/**
 * 产生随机字符串
 * @return {string}
 */
export const randomChars = () => Math.random().toString(36).substring(7)

/**
 * 图片转 base64
 * @param {File} image
 * @return {Promise<string>}
 */
export const imgToBase64 = async image => {
  const reader = new FileReader()
  return new Promise((resolve, reject) => {
    reader.onload = e => {
      resolve(e.target.result)
    }

    reader.onerror = e => {
      reject(e)
    }

    reader.readAsDataURL(image)
  })
}

export const dayjs = function () {
  return _dayjs.apply(dayjs, arguments)
}

/**
 * vuex 中存储两人的对话键，按两人 id 顺序加 短横线 连接
 * @param {int} id1
 * @param {int} id2
 * @return {string}
 */
export const makeDialogKey = (id1, id2) => {
  if (id1 > id2) {
    [id1, id2] = [id2, id1]
  }

  return id1 + '-' + id2
}

/**
 * 获取消息内容的纯文本
 *
 * @param {array} content 原始的消息内容数组
 * @return {string}
 */
export const getContentText = content => {
  return content.reduce((carry, i) => {
    return typeof i === 'string'
      ? carry + i
      : carry
  }, '').trim()
}
