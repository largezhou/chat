export class BaseMessage {
  propsData
  type

  constructor(type, msg) {
    let propsData
    if (typeof msg === 'string') {
      propsData = {
        msg,
      }
    } else {
      propsData = msg
    }

    this.propsData = propsData
    this.type = type

    this.handlePropsData()

    const mount = () => {
      const vm = this.createVM()
      document.body.appendChild(vm.$mount().$el)
    }

    if (propsData.onHidden) {
      mount()
    } else {
      return new Promise(resolve => {
        propsData.onHidden = resolve
        mount()
      })
    }
  }

  handlePropsData() {
    throw new Error('必须继承该方法啊')
  }

  createVM() {
    throw new Error('必须继承该方法啊')
  }

  static error(msg) {
    return new this('error', msg)
  }

  static show(msg) {
    return new this('show', msg)
  }
}
