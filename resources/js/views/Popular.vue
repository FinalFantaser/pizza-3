<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-md-flex justify-content-between">
                            <div class="col-12 col-md-7 mb-2 mb-md-0">
                                <h5 class="mb-0">Статистика популярных товаров</h5>
                                <p class="text-sm mb-0">
                                    Ниже представлена статистика частозаказываемых товаров.
                                    Отметьте нужные и нажмите на кнопку <span class="text-primary">"обновить список"</span>,
                                    что бы обновить список ваших рекомендуемых товаров,
                                    которые будут отображаться на сайте в разделе "часто заказывают".
                                </p>
                            </div>
                            <div class="col-12 col-sm-5 col-md-4 col-lg-3 col-xxl-2">
                                <router-link to="/recommended" class="d-block btn bg-gradient-success btn-sm mb-2">Посмотреть список</router-link>
                                <button
                                    @click="addRecommended"
                                    :disabled="checkedProducts.length == 0 ? true : false"
                                    class="btn bg-gradient-primary btn-sm mb-0 col-12"
                                >Обновить список</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-0">
                        <div class="table-responsive">
                            <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
                                <div class="dataTable-container">
                                    <table class="table table-flush dataTable-table" id="products-list">
                                        <thead class="thead-light">
                                        <tr>
                                            <th data-sortable="" style="width: 31.7521%;"><a href="#" class="dataTable-sorter">Товар</a></th>
                                            <th data-sortable="" style="width: 10.3099%;" class=""><a href="#" class="dataTable-sorter">Количество заказов</a></th>
                                            <th data-sortable="" style="width: 12.3972%;" class=""><a href="#" class="dataTable-sorter">Категория</a></th>
                                            <th data-sortable="" style="width: 9.55092%;" class=""><a href="#" class="dataTable-sorter">Цена</a></th>
                                            <th data-sortable="" style="width: 14.5478%;" class=""><a href="#" class="dataTable-sorter">Статус</a></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(popProduct, index) in statePopularProducts">
                                            <td>
                                                <div class="d-flex">
                                                    <div class="form-check my-auto">
                                                        <input
                                                            v-model="checkedProducts"
                                                            :value="popProduct.id"
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            :id="index"
                                                        >
                                                    </div>
                                                    <img
                                                        v-if="popProduct.thumbUrl"
                                                        class="w-10 ms-3"
                                                        :src="popProduct.thumbUrl"
                                                        alt="category"
                                                    >
                                                    <img
                                                        v-else
                                                        class="w-10 ms-3"
                                                        src="@/assets/img/ProductDefault.png"
                                                        alt="category"
                                                    >
                                                    <h6 class="ms-3 my-auto">{{ popProduct.name }}</h6>
                                                </div>
                                            </td>
                                            <td class="text-sm text-center">725</td>
                                            <td class="text-sm">{{ popProduct.category.name }}</td>
                                            <td class="text-sm">{{ popProduct.price + ' ₽' }}</td>
                                            <td>
                                                <span class="badge badge-success badge-sm">in Stock</span>
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
    name: "Popular",
    data() {
        return {
            checkedProducts: []
        };
    },
    computed: {
        statePopularProducts() {
            return this.$store.getters['serviceProducts/statePopularProducts']
        },
        stateRecommendedProducts() {
            return this.$store.getters['serviceProducts/stateRecommendedProducts']
        }
    },
    methods: {
        addRecommended() {
            const arr = this.checkedProducts.map((item) => {
                return {
                    product_id: item,
                    sort: 0
                }
            })
            console.log(arr)
            this.$store.commit('loaderTrue')
            axios.post('/api/v1/admin/products.recommended.add', {
                data: arr
            })
                .then((data) => {
                    this.$store.dispatch('getToast', {
                        msg: 'Список обновлён.',
                        settings: {
                            type: 'success'
                        }
                    })
                    this.$router.push({name: 'Recommended'})
                })
                .catch((error) => {
                    console.log(error)
                })
                .then(() => {
                    this.$store.commit('loaderFalse')
                })
        },
        async getPopularProducts() {
            await this.$store.dispatch('serviceProducts/getPopularProducts')
        },
        async getRecommendedProducts() {
            await this.$store.dispatch('serviceProducts/getRecommendedProducts')
        }
    },
    async created () {
        await this.getPopularProducts()
        await this.getRecommendedProducts()
        this.checkedProducts = this.stateRecommendedProducts.map(item => {
            return item.id
        })

    }
}
</script>

<style scoped>

</style>
