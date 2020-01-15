<template>
  <div class="editor-main">
    <div
      ref="editor"
      class="editor input"
      contenteditable="true"
      @input="onInput"
      @paste="onPaste"
      @mousewheel="onMousewheel"
      :innerHTML.prop="value"
      @keydown.enter.ctrl="onSend"
    />
    <div v-if="!value" class="placeholder">开始吧~~</div>
    <lz-button
      class="send"
      title="Ctrl + Enter 发送"
      @click="onSend"
      icon="svg-send"
      icon-size="16px"
    />
  </div>
</template>

<script>
import { imgToBase64 } from '@/libs/utils'

export default {
  name: 'Editor',
  props: {
    value: null,
  },
  methods: {
    onInput(e) {
      this.updateValue(e.target.innerHTML)
    },
    async onPaste(e) {
      e.preventDefault()

      const content = this.getClipboardData(e.clipboardData)
      if (!content) {
        return false
      }

      const editor = this.$refs.editor

      if (typeof content === 'string') {
        document.execCommand('insertText', false, content)
      } else {
        if (content.size > 200 * 1024) {
          this.$message.error('图片太大了，滚粗')
          return false
        }

        const url = await imgToBase64(content)

        document.execCommand(
          'insertHtml',
          false,
          `<img src="${url}"/>`,
        )
        // 在比如 `ab` 两个字符串中间插入图片时，会自动追加一个 `<br>`，手动删掉
        const pos = window.getSelection().getRangeAt(0).startOffset
        const node = editor.childNodes[pos]
        if (node && node.tagName === 'BR') {
          editor.removeChild(node)
        }
      }

      this.updateValue(editor.innerHTML)
    },
    updateValue(html) {
      /**
       * 解决为了实现 v-model，使用 v-html 后，
       * 每次输入时，光标自动定位到行首，并且自带的撤销功能失效的问题
       * @see https://stackoverflow.com/questions/53899676/vue-2-contenteditable-with-v-model#answer-58085557
       */
      this.$vnode.child._vnode.children[0].data.domProps['innerHTML'] = html
      this.$emit('input', html)
    },
    /**
     * @param {DataTransfer} clipboard
     * @return {File|string}
     */
    getClipboardData(clipboard) {
      const text = clipboard.getData('text')
      if (text) {
        return text.trim()
      }

      for (const i of clipboard.items) {
        if (i.kind === 'file' && i.type.indexOf('image') === 0) {
          return i.getAsFile()
        }
      }

      return ''
    },
    // 滚动条隐藏麻烦，直接 overflow:hidden，然后用滚动事件来处理滚动
    onMousewheel(e) {
      this.$refs.editor.scrollTop += e.deltaY / Math.abs(e.deltaY) * 5
    },
    async formatValue() {
      const _handle = nodes => {
        const value = []

        Array.from(nodes).forEach(i => {
          let newValue
          switch (i.tagName) {
            case 'DIV':
              value.push('\n')
              newValue = _handle(i.childNodes)
              // 处理空白行
              newValue = newValue[newValue.length - 1] === '\n'
                ? newValue.slice(0, -1)
                : newValue
              break
            case 'BR':
              newValue = '\n'
              break
            case 'IMG':
              newValue = i.src
              break
            default:
              newValue = i.textContent
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
          }

          if (newValue) {
            Array.isArray(newValue)
              ? value.push(...newValue)
              : value.push(newValue)
          }
        })

        return value
      }

      const value = _handle(this.$refs.editor.childNodes)

      // 拼接连续的字符串值，并把 base64 的单独出来
      const t = []
      let cur = ''
      let pureContent = ''
      for (let i of value) {
        pureContent += i
        if (i.startsWith('data:image')) {
          cur && t.push(cur)
          t.push({
            type: 'image',
            data: i,
          })
          cur = ''
        } else {
          cur += i
        }
      }

      cur && t.push(cur)

      return {
        content: t,
        pureContent,
      }
    },
    async onSend() {
      const { content, pureContent } = await this.formatValue()
      if (pureContent.length > 2 * 1024 * 1024) {
        this.$message.error('消息内容 不能超过 2M 啊。')
        return
      }
      if (!pureContent.trim()) {
        this.$message.error('空的，发啥呢？')
        return
      }
      this.$emit('send', content)
    },
    focus() {
      this.$refs.editor.focus()
    },
  },
}
</script>

<style scoped lang="scss">
@import '~@s/_input';

.editor-main {
  position: relative;
  height: 100%;
  font-size: 14px;
}

.editor {
  height: 100%;
  background: #20274f;
  position: absolute;
  padding: 15px 75px 15px 15px;
  overflow: hidden;
  word-break: break-all;

  ::v-deep img {
    max-width: 200px;
    max-height: 200px;
  }
}

.placeholder {
  position: absolute;
  left: 15px;
  top: 15px;
  color: #6670a2;
  pointer-events: none;
  font-size: inherit;
}

.send {
  position: absolute;
  padding: 0 10px;
  right: 20px;
  top: 8px;
}
</style>
