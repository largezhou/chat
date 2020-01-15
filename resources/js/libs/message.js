import Vue from 'vue'

const MessageComponent = Vue.extend(require('@c/Message').default)

export class Message {
  static new(type, msg) {
    let propsData
    if (typeof msg === 'string') {
      propsData = {
        msg,
      }
    } else {
      propsData = msg
    }
    propsData.type = type

    const mount = () => {
      const vm = new MessageComponent({
        propsData,
      })
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

  static success(msg) {
    return Message.new('success', msg)
  }

  static error(msg) {
    return Message.new('error', msg)
  }

  static show(msg) {
    return Message.new('show', msg)
  }
}

export default (Vue, options) => {
  Vue.$message = Message
  Vue.prototype.$message = Message
}
