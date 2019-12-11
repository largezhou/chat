/**
 * 获取元素相对于最左上角的坐标
 *
 * @param el
 * @returns {{x: number, y: number}}
 */
export const getPos = el => {
  let x = 0
  let y = 0

  do {
    x += el.offsetLeft
    y += el.offsetTop
    el = el.offsetParent
  } while (el)

  return {
    x: x - window.scrollX,
    y: y - window.scrollY,
  }
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
