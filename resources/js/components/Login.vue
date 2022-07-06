<template>
<div>
    <input v-model="email" type="email" placeholder="email" class="form-control">
    <input v-model="password" type="password" placeholder="password" class="form-control">
    <input @click.prevent="login" type="submit" value="Login" class="btn btn-primary">
</div>
</template>

<script>
import axios from "axios";

export default {
    name: "Login",
    data: () =>  ({

        email: null,
        password: null
}),
    methods: {

        login(){
            //[ email, password ] = this
            axios.get('/sanctum/csrf-cookie').then(response => {

                axios.post('/login', {email: this.email,password: this.password})
                .then( r => {
                    console.log(r)
                })

            });
        }
    }
}
</script>

<style scoped>

</style>
