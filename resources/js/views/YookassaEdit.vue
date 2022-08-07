<template>
    <div v-if="shop" class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-6 m-auto">
                <h4 class="text-white text-center">Создание магазина ю-касса</h4>
                <p class="text-white opacity-9 text-center">Заполните поля ниже, что бы создать магазин.</p>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 col-md-8 m-auto">
                <div class="card">
                    <div class="card-body">
                        <h5 class="font-weight-bolder text-center">Информация магазина</h5>
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
                            <div class="col-12 col-sm-8 m-auto position-relative">
                                <label>ID магазина</label>
                                <input
                                    :style="v$.shop_id.$error ? 'border-color: tomato;' : ''"
                                    v-model="shop_id"
                                    class="form-control"
                                    type="text"
                                    placeholder="Введите id магазина"
                                >
                                <p v-if="v$.shop_id.$error" class="invalid-msg">Обязательное поле</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-sm-8 m-auto position-relative">
                                <label>Api token магазина</label>
                                <input
                                    :style="v$.api_token.$error ? 'border-color: tomato;' : ''"
                                    v-model="api_token"
                                    class="form-control"
                                    type="text"
                                    placeholder="Введите api token магазина"
                                >
                                <p v-if="v$.api_token.$error" class="invalid-msg">Обязательное поле</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-sm-8 m-auto position-relative">
                                <label>Города</label>
                                <div v-for="(city, index) in shop.cities" class="form-check">
                                    <input
                                        :id="'city ' + (index + 1)"
                                        v-model="city_ids"
                                        :value="city.id"
                                        class="form-check-input"
                                        type="checkbox"
                                    >
                                    <label
                                        :for="'city ' + (index + 1)"
                                        class="form-check-label">
                                        {{ city.name }}
                                    </label>
                                </div>
                                <p v-if="city_ids.length === 0" class="invalid-msg invalid-msg--cities">Выберите хотя бы один город</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-8 m-auto">
                                <button
                                    @click="editShop"
                                    type="button"
                                    class="btn btn-success m-0"
                                >
                                    Сохранить
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h4 v-else class="text-white text-center">Магазин не найден</h4>
</template>

<script>
import useVuelidate from "@vuelidate/core/dist/index.esm";
import {required} from "@vuelidate/validators";

export default {
    name: "YokassaEdit",
    setup () {
        return { v$: useVuelidate() }
    },
    data() {
        return {
            shop: null,
            name: '',
            shop_id: '',
            api_token: '',
            city_ids: []
        }
    },
    computed: {
        stateCities() {
            return this.$store.getters['serviceCities/stateCities']
        }
    },
    methods: {
        async editShop() {
            const isFormCorrect = await this.v$.$validate()
            // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
            if (!isFormCorrect || this.city_ids.length === 0) return
            this.$store.commit('loaderTrue')

            axios.put(`/api/v1/admin/payment/yookassa_shop/${this.shop.id}`, {
                name: this.name,
                shop_id: this.shop_id,
                api_token: this.api_token,
                city_ids: this.city_ids
            })
                .then(response => {
                    this.$store.dispatch('getToast', {
                        msg: 'Данные обновлены!'
                    })
                    this.$router.push({name: 'yookassa'})
                    this.$store.commit('loaderFalse')
                    console.log(response)
                })
                .catch(error => {
                    this.$store.dispatch('getToast', { msg: 'Что-то пошло не так!', settings: {
                            type: 'error'
                        } })
                    this.$store.commit('loaderFalse')
                    console.log(error)
                })
        }
    },
    validations () {
        return {
            name: { required },
            shop_id: { required },
            api_token: { required }
        }
    },
    async mounted() {
        this.$store.commit('loaderTrue')
        await axios.get(`/api/v1/admin/payment/yookassa_shop/${this.$route.params.id}`)
                .then(response => {
                    this.shop = response.data.data
                    this.name = this.shop.name
                    this.shop_id = this.shop.shop_id
                    this.api_token = this.shop.api_token
                    this.city_ids = this.shop.cities.map(item => {
                        return item.id
                    })
                    console.log(this.shop)
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
.invalid-msg--cities {
    bottom: -18px;
}
</style>
