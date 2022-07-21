<template>

    <div class="py-sm-4 p-2 p-sm-4 container-fluid">
        <div class="row">
            <div class="col-lg-12 m-auto">
                <div class="card p-2 p-sm-4">
                    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="courier-tab" data-bs-toggle="tab" data-bs-target="#courier" type="button" role="tab" aria-controls="courier" aria-selected="false">Курьер</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pickup-tab" data-bs-toggle="tab" data-bs-target="#pickup" type="button" role="tab" aria-controls="pickup" aria-selected="true">Самовывоз</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="pickup" role="tabpanel" aria-labelledby="pickup-tab">
                            <h5 class="mb-3 max-width-">Добавьте адрес ресторана и привяжите к городу</h5>
                            <div class="row mb-lg-3">
                                <div class="col-12 col-lg-4 mb-3 mb-sm-0">
                                    <label>Адрес ресторана</label>
                                    <textarea
                                        placeholder="Введите адрес"
                                        v-model="address"
                                        class="form-control"
                                        id="exampleFormControlTextarea1"
                                        rows="5"
                                    ></textarea>
                                </div>

                                <div class="col-12 col-lg-4">
                                    <label>Город</label>
                                    <select
                                        v-model="city"
                                        @change="getPointsCity"
                                        class="form-select"
                                    >
                                        <option
                                            v-for="city in stateCities"
                                            :value="city.id"
                                        >{{ city.name }}</option>
                                    </select>
                                </div>

                                <div class=" d-lg-none my-3">
                                    <button
                                        @click="setPointCity"
                                        class="btn btn-primary btn-sm m-0"
                                        type="button"
                                        name="button"
                                    >Добавить</button>
                                </div>

                                <div class="col-12 col-lg-4">
                                    <label>Адреса:</label>
                                    <div class="card overflow-hidden p-2">
                                        <div class="delivery__addresses overflow-auto p-2">
                                            <p v-if="cityAddresses.length == 0" class="m-0">Нет ни одного адреса</p>
                                            <div
                                                v-for="cityAddress in cityAddresses"
                                                class="d-flex align-items-start justify-content-between py-1"
                                            >
                                                <span class="me-2 text-break">{{ cityAddress.address }}</span>
                                                <div class="d-flex align-items-center">
                                                    <router-link class="p-sm-1 cursor-pointer me-2" :to="'/delivery/pickup_point/' + cityAddress.id + '/edit'">
                                                        <i class="fa fa-pencil text-secondary text-sm opacity-10" aria-hidden="true"></i>
                                                    </router-link>
                                                    <div
                                                        @click="deletePointCity(cityAddress.id)"
                                                        class="p-sm-1 cursor-pointer"
                                                    >
                                                        <i class="fas fa-trash text-secondary" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button
                                @click="setPointCity"
                                class="btn btn-primary btn-sm mb-0 d-none d-lg-block"
                                type="button"
                                name="button"
                            >Добавить</button>
                        </div>
                        <div class="tab-pane fade" id="courier" role="tabpanel" aria-labelledby="courier-tab">...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    name: "Delivery",
    data() {
        return {
            address: '',
            city: '',
            cityAddresses: []
        }
    },
    computed: {
        stateCities() {
            return this.$store.getters['serviceCities/stateCities']
        }
    },
    methods: {
        async deletePointCity(id) {
            this.$store.commit('loaderTrue')
            await axios.delete(`/api/v1/admin/delivery/pickup_points/${id}`)
                .then(response => {
                    this.$store.commit('loaderFalse')
                    console.log(response.data.data)
                })
                .catch(error => {
                    this.$store.commit('loaderFalse')
                    console.log(error)
                })
            await this.getPointsCity()
        },
        async getCities() {
            await this.$store.dispatch('serviceCities/getCities')
        },
        async setPointCity() {
            this.$store.commit('loaderTrue')
            await axios.post('/api/v1/admin/delivery/pickup_points', {
                city_id: this.city,
                address: this.address
            })
                .then(response => {
                    this.address = ''
                    this.$store.commit('loaderFalse')
                    console.log(response)
                })
                .catch(error => {
                    this.$store.commit('loaderFalse')
                    console.log(error)
                })
            await this.getPointsCity()
        },
        async getPointsCity() {
            this.$store.commit('loaderTrue')
            await axios.get(`/api/v1/admin/delivery/pickup_points/city/${this.city}`)
                .then(response => {
                    this.cityAddresses = []
                    if (response) {
                        this.cityAddresses = response.data.data
                    }
                    this.$store.commit('loaderFalse')
                    console.log(response.data.data)
                })
                .catch(error => {
                    this.$store.commit('loaderFalse')
                    console.log(error)
                })
        }
    },
    async created() {
        await this.getCities()
        if (this.stateCities) {
            this.city = this.stateCities[0].id
            await this.getPointsCity()
        }
    }
}
</script>

<style scoped>
.delivery__addresses {
    max-height: 300px;
}
</style>
