<template>

    <div v-if="address" class="py-sm-4 p-2 p-sm-4 container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="text-white">Редактирование адреса</h4>
                <p class="text-white opacity-8">Отредактируйте адрес ниже.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 m-auto">
                <div class="card p-2 p-sm-4">
                    <h5 class="font-weight-bolder">Информация адреса</h5>
                    <div class="col-12 mb-3">
                        <label>Адрес</label>
                        <textarea
                            placeholder="Введите адрес"
                            v-model="address"
                            class="form-control mb-3"
                            id="exampleFormControlTextarea1"
                            rows="5"
                        ></textarea>

                        <label>Город</label>
                        <select
                            v-model="city"
                            class="form-select"
                        >
                            <option
                                v-for="city in stateCities"
                                :value="city.id"
                            >{{ city.name }}</option>
                        </select>
                    </div>
                    <div>
                        <button
                            @click="editPoint"
                            class="btn btn-primary btn-sm mb-0"
                            type="button"
                            name="button"
                        >Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h4 v-else class="text-white text-center ь-4">Адрес не найден</h4>

</template>

<script>
export default {
    name: "PickupPointEdit",
    data() {
        return {
            city: '',
            address: ''
        }
    },
    computed: {
        stateCities() {
            return this.$store.getters['serviceCities/stateCities']
        }
    },
    methods: {
        editPoint() {
            this.$store.commit('loaderTrue')
            axios.put(`/api/v1/admin/delivery/pickup_points/${this.$route.params.id}`, {
                city_id: this.city,
                address: this.address
            })
                .then(response => {
                    this.$store.commit('loaderFalse')
                    this.$router.push({ name: 'Delivery', params: { cityId: this.city } })
                })
                .catch(error => {
                    this.$store.commit('loaderFalse')
                    console.log(error)
                })
        },
        async getCities() {
            await this.$store.dispatch('serviceCities/getCities')
        },
    },
    async created() {
        await this.getCities()
        if (this.stateCities) {
            this.city = this.stateCities[0].id
        }

        this.$store.commit('loaderTrue')
        await axios.get(`/api/v1/admin/delivery/pickup_points/${this.$route.params.id}`)
            .then(response => {
                this.addressInfo = response.data.data
                this.address = response.data.data.address
                this.city = response.data.data.city.id
                this.$store.commit('loaderFalse')
            })
            .catch(error => {
                this.$store.commit('loaderFalse')
                console.log(error)
            })
    }
}
</script>

<style scoped>

</style>
