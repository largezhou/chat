<template>
  <span
    :title="this.time"
    :key="ticker2"
    v-text="dayjs(this.time).fromNow()"
  />
</template>

<script>
import { mapState } from 'vuex'

export default {
  name: 'FromNow',
  props: {
    time: String,
  },
  computed: {
    ...mapState({
      ticker2: state => state.ticker2,
    }),
  },
}
</script>
