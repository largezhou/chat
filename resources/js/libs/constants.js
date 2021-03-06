module.exports.MSG_STATUS = {
  PENDING: 'pending',
  OK: 'ok',
  FAILED: 'failed',
}

module.exports.MAX_MSGS_COUNT = 50

const freeze = obj => {
  Object.freeze(obj)
  Object.getOwnPropertyNames(obj).forEach(key => {
    const t = obj[key]
    if (t && typeof t === 'object') {
      freeze(t)
    }
  })
}

freeze(module.exports)
