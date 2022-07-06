<template>
    <div>
        <router-link :to="{ name: 'get.index'}">Get  </router-link>
        <router-link :to="{ name: 'user.login'}">Login  </router-link>
        <router-link :to="{ name: 'user.registration'}">Registration</router-link>
        <router-view />
    </div>
</template>

<script>
export default {
    name: "App"
}
</script>

<style scoped>

</style>
