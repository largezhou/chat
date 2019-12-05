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

  return { x, y }
}
