let startEl = null

export const ClickOutside = {
  inserted(el, { value }, vnode) {
    // 记录鼠标按下时，是否在元素（弹框）内部,
    // 避免在元素（弹框）内部选择内容时，鼠标很容易移出元素，
    // 而导致弹框消失，或其他情况
    const onRecordStart = e => {
      if (el.contains(e.target)) {
        startEl = el
      }
    }
    document.addEventListener('mousedown', onRecordStart, true)
    el.onRecordStart = onRecordStart

    const onClickOutside = e => {
      !startEl && value && !el.contains(e.target) && value(e)
      startEl = null
    }
    document.addEventListener('mouseup', onClickOutside, true)
    el.onClickOutside = onClickOutside
  },

  unbind(el) {
    el.onRecordStart && document.removeEventListener('mousedown', el.onRecordStart)
    el.onClickOutside && document.removeEventListener('mouseup', el.onClickOutside)
  },
}
