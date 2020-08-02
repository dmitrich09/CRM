export default {
  methods: {
    can (val) {
      var rl = this.$store.state.auth.user.roles
      if (rl == null) {
        return false
      }
      for (let i = 0; i < rl.length; i++) {
        if (rl[i] == val) {
          return true
        }
      }
    }
  }
}