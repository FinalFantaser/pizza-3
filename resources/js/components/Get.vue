<template>
    <div>
        GET
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "Get",

    mounted() {
        this.getData()
    },

    methods: {
        getData() {
            axios.get('/sanctum/csrf-cookie').then(response => {

                axios.get('/api/get')
                    .then(res => {
                        console.log(res)
                    })

            });
        }
    }
}
</script>

<style scoped>

</style>
