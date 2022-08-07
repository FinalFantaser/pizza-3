<template>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="text-white">Создание магазина ю-касса</h4>
                <p class="text-white opacity-8">Заполните поля ниже, что бы создать магазин.</p>
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
                            <div class="col-12 col-sm-8 m-auto">
                                <label>Города</label>
                                <div v-for="(city, index) in stateCities" class="form-check">
                                    <input
                                        :id="'city ' + (index + 1)"
                                        v-model="cities"
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
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-8 m-auto">
                                <button
                                    @click="addShop"
                                    type="button"
                                    class="btn btn-success m-0"
                                >
                                    Создать
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import useVuelidate from '@vuelidate/core'
import { required } from '@vuelidate/validators'

export default {
    name: "YookassaCreate",
    setup () {
        return { v$: useVuelidate() }
    },
    data() {
      return {
          name: '',
          shop_id: '',
          api_token: '',
          cities: []
      }
    },
    computed: {
        stateCities() {
            return this.$store.getters['serviceCities/stateCities']
        }
    },
    methods: {
        async getCities() {
            await this.$store.dispatch('serviceCities/getCities')
        },
        async addShop() {
            const isFormCorrect = await this.v$.$validate()
            // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
            if (!isFormCorrect) return
            this.$store.commit('loaderTrue')

            axios.post('/api/v1/admin/payment/yookassa_shop', {
                name: this.name,
                shop_id: this.shop_id,
                api_token: this.api_token,
                city_ids: this.cities
            })
                .then(response => {
                    this.$store.dispatch('getToast', {
                        msg: 'Магазин создан :)'
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
        await this.getCities()
        if(this.stateCities) {
            this.stateCities.forEach(item => {
                this.cities.push(item.id)
            })
        }
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
</style>
