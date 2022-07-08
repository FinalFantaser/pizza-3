<template>
    <div class="py-4 container-fluid">
        <div class="row">
            <div class="col-lg-7 m-auto">
                <div class="card">
                    <div class="card-header pb-0 d-flex justify-content-between">
                        <h6>Cities table</h6>

                        <button
                            data-bs-toggle="modal" data-bs-target="#ModalCities"
                            class="btn btn-success btn-sm ms-auto"
                            @click="this.$store.state.editCity = false"
                        >Add city</button>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">City</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="city in cities">
                                    <td>
                                        <p class="m-0 p-2 py-1 text-xl">{{ city.name }}</p>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex justify-content-end pe-2">
                                            <div
                                                class="me-2 cursor-pointer icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center"
                                                data-bs-toggle="modal" data-bs-target="#ModalCities"
                                                @click="editCity(city)"
                                            >
                                                <i class="fa fa-pencil text-secondary text-sm opacity-10" aria-hidden="true"></i>
                                            </div>
                                            <div class="cursor-pointer icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                                                <i class="fa fa-times text-secondary text-sm opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "Cities",
    data() {
        return {
            cities: null
        }
    },
    methods: {
        editCity(city) {
            this.$store.state.editCity = true
            this.$store.state.city = city
        }

    },
    created() {
        axios.get('api/v1/admin/cities')
            .then(data => {
                this.cities = data.data.data
                console.log(data.data.data)
            })
            .catch( (error) => {
                console.log(error);
            })
    },
    components: {
    }
}
</script>

<style scoped>

</style>
