<template>
    <transition>
        <div class="loader__wrap">
            <span class="loader"></span>
        </div>
    </transition>
</template>

<script>
export default {
    name: "Loader"
}
</script>

<style scoped>
.v-enter-active, .v-leave-active {
    transition: opacity .5s;
}
.v-enter, .v-leave-to /* .fade-leave-active до версии 2.1.8 */ {
    opacity: 0;
}
</style>
