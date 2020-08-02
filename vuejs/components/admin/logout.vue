<template>
    <div id="logout">
    </div>
</template>

<script>
  export default {
    name: 'logout',
    created() {
      this.$store.dispatch('auth/logout').then(() => {
          this.$router.push('/login')
        })
    },
  }
</script>
