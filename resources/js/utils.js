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
