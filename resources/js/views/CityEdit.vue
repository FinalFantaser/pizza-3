<template>
    <div v-if="city" class="container-fluid py-4">
        <div class="row mb-4">
            <h4 class="text-white text-center">Создание города</h4>
            <p class="text-white opacity-9 text-center">Заполните поля ниже, что бы создать город.</p>
        </div>
        <div class="col-lg-8 m-auto mt-lg-0 mt-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="font-weight-bolder text-center">Информация города</h5>
                    <div class="row mb-3">
                        <div class="col-12 col-sm-8 m-auto position-relative">
                            <label>Название</label>
                            <input
                                :style="v$.name.$error ? 'border-color: tomato;' : ''"
                                v-model="name"
                                class="form-control"
                                type="text"
                                placeholder="Введите название"
                            >
                            <p v-if="v$.name.$error" class="invalid-msg">Обязательное поле</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 col-sm-8 m-auto">
                            <label>Адрес</label>
                            <input
                                v-model="address"
                                class="form-control"
                                type="text"
                                placeholder="Введите адрес"
                            >
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-12 col-sm-8 m-auto position-relative">
                            <label>Телефон</label>
                            <input
                                :style="v$.phone.$error ? 'border-color: tomato;' : ''"
                                v-phone
                                v-model="phone"
                                maxlength="16"
                                class="form-control"
                                type="text"
                                placeholder="Введите телефон"
                            >
                            <p v-if="v$.phone.$error" class="invalid-msg">Не верный формат</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-5 m-auto">
                            <button
                                @click="editCity"
                                type="button"
                                class="btn btn-primary col-12"
                            >
                                Обновить
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h4 v-else class="text-white text-center">Город не найден</h4>
</template>

<script>
import useVuelidate from '@vuelidate/core'
import { required } from '@vuelidate/validators'

export default {
    name: "CityEdit",
    setup () {
        return { v$: useVuelidate() }
    },
    data() {
        return {
            city: null,
            name: '',
            address: '',
            phone: ''
        }
    },
    methods: {
        async editCity() {
            const isFormCorrect = await this.v$.$validate()
            // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
            if (!isFormCorrect) return
            this.$store.commit('loaderTrue')
            axios.put(`/api/v1/admin/cities/${this.city.id}`, {
                name: this.name,
                address: this.address,
                phone: this.phone
            })
                .then((response) => {
                    this.$store.commit('loaderFalse')
                    this.$store.dispatch('getToast', { msg: 'Информация обновлена!' })
                    this.$router.push({name: 'Cities'})
                })
                .catch((error) => {
                    this.$store.commit('loaderFalse')
                    this.$store.dispatch('getToast', { msg: 'Что-то пошло не так!', settings: {
                            type: 'error'
                        } })
                    console.log(error);
                })
                .then(() => {
                })
        }
    },
    validations () {
        return {
            name: { required },
            phone: {
                isPhone: (phone)=> {
                    const phoneRe = /^(8|7)[\d]{10}$/;
                    if (phone){
                        const digits = phone.replace(/\D/g, "");
                        return phoneRe.test(digits)
                    }
                    return true
                }
            },
        }
    },
    async mounted() {
        this.$store.commit('loaderTrue')
        await axios.get(`/api/v1/admin/cities/${this.$route.params.id}`)
            .then(response => {
                this.city = response.data.data
                this.name = this.city.name || ''
                this.address = this.city.address || ''
                this.phone = this.city.phone || ''
            })
            .catch( (error) => {
                console.log(error);
            })
            .then(() => {
                this.$store.commit('loaderFalse')
            })
    }
}
</script>

<style scoped>
.invalid-msg {
    position: absolute;
    bottom: -28px;
    left: 15px;
    transform: translateY(-50%);
    margin: 0;
    font-size: 12px;
    color: tomato;
}
.invalid-msg--item {
    left: 13px;
}
</style>
