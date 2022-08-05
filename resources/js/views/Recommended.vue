<template>
    <div v-if="stateRecommendedProducts" class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-md-flex justify-content-between">
                            <div class="col-12 col-md-7 mb-2 mb-md-0">
                                <router-link to="/popular" class="d-block text-success mb-3 text-decoration-underline">
                                    <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                                    назад к статистике
                                </router-link>
                                <h5 class="mb-0">Список рекомендуемых товаров</h5>
                                <p class="text-sm mb-0">Ниже представлен список ваших рекомендуемых товаров, которые будут отображаться на сайте в разделе "часто заказывают"</p>
                            </div>
                            <div class="col-9 col-sm-5 col-md-4 col-lg-3 col-xxl-2 d-flex ms-auto ms-md-0">
                                <button
                                    @click="clearRecommended"
                                    :disabled="stateRecommendedProducts.length == 0 ? true : false"
                                    class="btn bg-gradient-danger btn-sm m-0 mt-auto col-12"
                                >Очистить список</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-0">
                        <div class="table-responsive">
                            <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
                                <div class="dataTable-container">
                                    <table v-if="stateRecommendedProducts.length > 0" class="table table-flush dataTable-table" id="products-list">
                                        <thead class="thead-light">
                                        <tr>
                                            <th data-sortable="" style="width: 31.7521%;"><a href="#" class="dataTable-sorter">Товар</a></th>
                                            <th data-sortable="" style="width: 9.55092%;" class=""><a href="#" class="dataTable-sorter">Цена</a></th>
                                            <th data-sortable="" style="width: 12.3972%;" class=""><a href="#" class="dataTable-sorter">Категория</a></th>
                                            <th data-sortable="" style="width: 14.5478%;" class=""><a href="#" class="dataTable-sorter">Статус</a></th>
                                            <th data-sortable="" style="width: 8.91841%;" class="desc"><a href="#" class="dataTable-sorter">Действия</a></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(recommendedProduct, index) in stateRecommendedProducts">
                                            <td>
                                                <div class="d-flex">
<!--                                                    <div class="form-check my-auto">-->
<!--                                                        <input-->
<!--                                                            class="form-check-input"-->
<!--                                                            type="checkbox"-->
<!--                                                            :id="index"-->
<!--                                                        >-->
<!--                                                    </div>-->
                                                    <img
                                                        v-if="recommendedProduct.thumbUrl"
                                                        class="w-10 ms-3"
                                                        :src="recommendedProduct.thumbUrl"
                                                        alt="category"
                                                    >
                                                    <img
                                                        v-else
                                                        class="w-10 ms-3"
                                                        src="@/assets/img/ProductDefault.png"
                                                        alt="category"
                                                    >
                                                    <h6 class="ms-3 my-auto">{{ recommendedProduct.name }}</h6>
                                                </div>
                                            </td>
                                            <td class="text-sm">{{ recommendedProduct.price + ' ₽' }}</td>
                                            <td class="text-sm">{{ recommendedProduct.category.name }}</td>
                                            <td>
                                                <span class="badge badge-success badge-sm">in Stock</span>
                                            </td>
                                            <td class="text-sm">
                                                <a
                                                    href="javascript:;"
                                                    @click="deleteRecommendedProduct(recommendedProduct.id)"
                                                >
                                                    <i class="fas fa-trash text-secondary" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <h5 v-else class="text-center text-warning p-5">СПИСОК ПУСТ</h5>
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
    name: "Recommended",
    computed: {
        stateRecommendedProducts() {
            return this.$store.getters['serviceProducts/stateRecommendedProducts']
        }
    },
    methods: {
        clearRecommended() {
            this.$store.commit('loaderTrue')
            axios.post('api/v1/admin/products.recommended.clear')
                .then((response) => {
                    this.getRecommendedProducts()
                    this.$store.dispatch('getToast', {
                        msg: 'Список полностью очищен.',
                        settings: {
                            type: 'success'
                        }
                    })
                })
                .catch((error) => {
                    console.log(error)
                })
                .then(() => {
                    this.$store.commit('loaderFalse')
                })
        },
        deleteRecommendedProduct(id) {
            this.$store.commit('loaderTrue')
            axios.post('api/v1/admin/products.recommended.remove', {
                product_id: id
            })
                .then((response) => {
                    this.getRecommendedProducts()
                    this.$store.dispatch('getToast', {
                        msg: 'Товар удалён из рекомендуемых.',
                        settings: {
                            type: 'success'
                        }
                    })
                })
                .catch((error) => {
                    console.log(error)
                })
                .then(() => {
                    this.$store.commit('loaderFalse')
                })
        },
        async getRecommendedProducts() {
            await this.$store.dispatch('serviceProducts/getRecommendedProducts')
        },
    },
    async created () {
        await this.getRecommendedProducts()
    }
}
</script>

<style scoped>

</style>
