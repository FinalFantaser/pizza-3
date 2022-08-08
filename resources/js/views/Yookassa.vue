<template>
    <div class="py-4 container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Список ваших магазинов</h5>
                                <p class="text-sm mb-0">
                                    Ниже представлен список ваших магазинов "Ю-кассы".
                                </p>
                            </div>
                            <div class="ms-auto my-auto mt-lg-0 mt-4">
                                <div class="ms-auto my-auto">
                                    <router-link to="/yookassa/create" class="btn bg-gradient-primary btn-sm mb-0">Создать магазин</router-link>
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
                                            <th data-sortable="" style="width: 31.7521%;"><a href="#" class="dataTable-sorter">Магазин</a></th>
                                            <th data-sortable="" style="width: 12.3972%;" class=""><a href="#" class="dataTable-sorter">ID магазина</a></th>
                                            <th data-sortable="" style="width: 12.3972%;" class=""><a href="#" class="dataTable-sorter">Токен</a></th>
                                            <th data-sortable="" style="width: 9.55092%;" class=""><a href="#" class="dataTable-sorter">Действия</a></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(shop, index) in stateShops" :key="index">
                                            <td>
                                                <h6>{{ shop.name }}</h6>
                                            </td>
                                            <td class="text-sm">{{ shop.shop_id }}</td>
                                            <td class="text-sm">{{ shop.api_token }}</td>
                                            <td class="text-sm">
                                                <router-link :to="'/yookassa/' + shop.id + '/edit'" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit product">
                                                    <i class="fa fa-pencil text-secondary" aria-hidden="true"></i>
                                                </router-link>
                                                <a
                                                    href="javascript:;"
                                                    @click="deleteShop(shop.id)"
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
    name: "Yookassa",
    computed: {
        stateShops() {
            return this.$store.getters['serviceYookassa/stateShops']
        }
    },
    methods: {
        deleteShop(id) {
            this.$store.commit('loaderTrue')
            axios.delete(`/api/v1/admin/payment/yookassa_shop/${id}`)
                .then( (response) => {
                    this.$store.commit('loaderFalse')
                    this.getShops()
                })
                .catch(function (error) {
                    this.$store.commit('loaderFalse')
                    console.log(error);
                })
        },
        async getShops() {
            await this.$store.dispatch('serviceYookassa/getShops')
        }
    },
    async mounted() {
        await this.getShops()
    }
}
</script>

<style scoped>

</style>
