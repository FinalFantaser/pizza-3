<template>
    <div class="py-4 container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Список городов</h5>
                                <p class="text-sm mb-0">
                                    Ниже представлен список всех имеющихся городов.
                                </p>
                            </div>
                            <div class="ms-auto my-auto mt-lg-0 mt-4">
                                <div class="ms-auto my-auto">
                                    <router-link to="/cities/create" class="btn bg-gradient-primary btn-sm mb-0">Создать город</router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-0">
                        <div class="table-responsive">
                            <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
                                <div class="dataTable-top">
                                    <div class="dataTable-dropdown">
                                        <label>
                                            <select class="dataTable-selector">
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="15">15</option>
                                                <option value="20">20</option>
                                                <option value="25">25</option>
                                            </select>
                                            entries per page
                                        </label>
                                    </div>
                                    <div class="dataTable-search">
                                        <input class="dataTable-input" placeholder="Search..." type="text">
                                    </div>
                                </div>
                                <div class="dataTable-container">
                                    <table class="table table-flush dataTable-table" id="products-list">
                                        <thead class="thead-light">
                                        <tr>
                                            <th data-sortable="" style="width: 31.7521%;"><a href="#" class="dataTable-sorter">Город</a></th>
                                            <th data-sortable="" style="width: 12.3972%;" class=""><a href="#" class="dataTable-sorter">Адрес</a></th>
                                            <th data-sortable="" style="width: 9.55092%;" class=""><a href="#" class="dataTable-sorter">Телефон</a></th>
                                            <th data-sortable="" style="width: 9.55092%;" class=""><a href="#" class="dataTable-sorter">Действия</a></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(city, index) in stateCities">
                                            <td>
                                                <h6>{{ city.name }}</h6>
                                            </td>
                                            <td class="text-sm">{{ city.address || 'адрес' }}</td>
                                            <td class="text-sm">{{ city.phone || 'телефон' }}</td>
                                            <td class="text-sm">
                                                <router-link :to="'/cities/' + city.id + '/edit'" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit product">
                                                    <i class="fa fa-pencil text-secondary" aria-hidden="true"></i>
                                                </router-link>
                                                <a
                                                    href="javascript:;"
                                                    @click="deleteCity(city.id)"
                                                >
                                                    <i class="fas fa-trash text-secondary" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="dataTable-bottom">
                                    <div class="dataTable-info">Showing 1 to 7 of 15 entries</div>
                                    <nav class="dataTable-pagination">
                                        <ul class="dataTable-pagination-list">
                                            <li class="pager"><a href="#" data-page="1">‹</a></li>
                                            <li class="active"><a href="#" data-page="1">1</a></li>
                                            <li class=""><a href="#" data-page="2">2</a></li>
                                            <li class=""><a href="#" data-page="3">3</a></li>
                                            <li class="pager"><a href="#" data-page="2">›</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
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
        deleteCity(id) {
            this.$store.commit('loaderTrue')
            axios.delete(`/api/v1/admin/cities/${id}`)
                .then( (response) => {
                    this.$store.commit('loaderFalse')
                    this.$store.dispatch('serviceCities/getCities')
                })
                .catch((error) => {
                    this.$store.commit('loaderFalse')
                    console.log(error);
                })
        }

    },
    async created() {
        await this.getCities()
    }
}
</script>

<style scoped>

</style>
