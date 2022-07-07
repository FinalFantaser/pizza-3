<template>
</template>

<script>

export default {
  name: "Logout",
  methods: {
      logout(){

              axios.post('/logout')
                  .then( r => {
                      localStorage.removeItem('x_xsrf_token')
                      this.$router.push({name: 'Signin'})
                  })
      }
    },
  created() {
    this.logout()
  }
};
</script>
