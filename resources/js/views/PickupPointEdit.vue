<template>

    <div class="py-sm-4 p-2 p-sm-4 container-fluid">
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
                    <div class="col-12">
                        <label>Адрес</label>
                        <textarea
                            placeholder="Введите адрес"
                            v-model="address"
                            class="form-control"
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
                </div>
            </div>
        </div>
    </div>

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
        async getCities() {
            await this.$store.dispatch('serviceCities/getCities')
        },
    },
    async created() {
        await this.getCities()
        if (this.stateCities) {
            this.city = this.stateCities[0].id
        }
    }
}
</script>

<style scoped>

</style>
