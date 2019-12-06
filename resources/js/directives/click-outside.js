export const ClickOutside = {
  inserted(el, { value }, vnode) {
    const onClickOutside = e => {
      if (!el.contains(e.target)) {
        value && value()
      }
    }
    document.addEventListener('click', onClickOutside, true)
    el.onClickOutside = onClickOutside
  },

  unbind(el) {
    el.onClickOutside && document.removeEventListener('click', el.onClickOutside)
  },
}
