import Vue from 'vue'
import { BaseMessage } from '@/libs/base-message'

const MessageComponent = Vue.extend(require('@c/Message').default)

export class Message extends BaseMessage {
  createVM() {
    return new MessageComponent({ propsData: this.propsData })
  }

  handlePropsData(propsData, type) {
    this.propsData.type = this.type
  }

  static success(msg) {
    return new this('success', msg)
  }
}

export default (Vue, options) => {
  Vue.$message = Message
  Vue.prototype.$message = Message
}
