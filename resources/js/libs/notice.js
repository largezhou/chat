import Vue from 'vue'
import { BaseMessage } from '@/libs/base-message'

const NoticeComponent = Vue.extend(require('@c/Notice').default)

export class Notice extends BaseMessage {
  createVM() {
    return new NoticeComponent({ propsData: this.propsData })
  }

  handlePropsData() {
    this.propsData.error = this.type === 'error'
  }
}

export default (Vue, options) => {
  Vue.$notice = Notice
  Vue.prototype.$notice = Notice
}
