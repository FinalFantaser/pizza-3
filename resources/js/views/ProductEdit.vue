<template>
    <div v-if="product" class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="text-white">Make the changes below</h4>
                <p class="text-white opacity-8">We’re constantly trying to express ourselves and actualize our dreams. If you have the opportunity to play.</p>
            </div>
            <div class="col-lg-6 text-right d-flex flex-column justify-content-center">
                <button type="button" class="btn btn-outline-white mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2">Save</button>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="font-weight-bolder">Product Image</h5>
                        <div class="row">
                            <div class="col-12">
                                <img class="w-100 border-radius-lg shadow-lg mt-3" src="https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/product-page.jpg" alt="product_image">
                            </div>
                            <div class="col-12 mt-5">
                                <div class="d-flex">
                                    <button class="btn btn-primary btn-sm mb-0 me-2" type="button" name="button">Edit</button>
                                    <button class="btn btn-outline-dark btn-sm mb-0" type="button" name="button">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 mt-lg-0 mt-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="font-weight-bolder">Информация о продукте</h5>
                        <div class="row mb-sm-3">
                            <div class="col-12 col-sm-6">
                                <label>Название</label>
                                <input
                                    v-model="name"
                                    class="form-control"
                                    type="text"
                                    placeholder="Название продукта"
                                >
                            </div>
                            <div class="col-12 col-sm-6">
                                <label>Категория</label>
                                <select
                                    v-model="category"
                                    class="form-select"
                                >
                                    <option
                                        v-for="category in stateCategories"
                                        :value="category.id"
                                    >{{ category.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-sm-3">
                            <div class="col-12 col-sm-6">
                                <label>Цена</label>
                                <input
                                    placeholder="Введите цену"
                                    v-model="price"
                                    class="form-control mb-sm-3"
                                    type="text"
                                >
                                <label>Цена со скидкой</label>
                                <input
                                    placeholder="Введите цену со скидкой"
                                    v-model="price_sale"
                                    class="form-control"
                                    type="text"
                                >
                            </div>
                            <div class="col-12 col-sm-6">
                                <label>Описание</label>
                                <textarea
                                    placeholder="Опишите продукт"
                                    v-model="description"
                                    class="form-control"
                                    id="exampleFormControlTextarea1"
                                    rows="5"
                                ></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <label>Параметры</label>
                                <input
                                    v-model="sizes"
                                    class="form-control"
                                    type="text"
                                    placeholder="Введите размеры через запятую"
                                >
                            </div>
                            <div class="col-12 col-sm-6">
                                <label>Города</label>
                                <div v-for="(city, index) in stateCities" class="form-check">
                                    <input
                                        :id="'city ' + (index + 1)"
                                        v-model="cities"
                                        :value="city.id"
                                        class="form-check-input" type="checkbox" id="flexCheckChecked">
                                    <label class="form-check-label" :for="'city ' + (index + 1)">
                                        {{ city.name }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h4 v-else class="text-white text-center ь-4">Продукт не найден</h4>
</template>

<script>
export default {
    name: "ProductEdit",
    data() {
        return {
            product: null,
            name: '',
            price: '',
            price_sale: '',
            cities: [],
            description: '',
            category: '',
            sizes: ''
        }
    },
    computed: {
        stateCities() {
            return this.$store.getters['serviceCities/stateCities']
        },
        stateCategories() {
            return this.$store.getters['serviceCategories/stateCategories']
        },
        stateProducts() {
            return this.$store.getters['serviceProducts/stateProducts']
        }
    },
    methods: {
        async getCities() {
            await this.$store.dispatch('serviceCities/getCities')
        },
        async getCategories() {
            await this.$store.dispatch('serviceCategories/getCategories')
        },
        async getProducts() {
            await this.$store.dispatch('serviceProducts/getProducts')
        }
    },
    async created() {
        this.$store.commit('loaderTrue')
        await axios.get(`/api/v1/admin/products/${this.$route.params.id}`)
            .then(data => {
                this.product = data.data.data
                console.log(data.data.data)
            })
            .catch( (error) => {
                console.log(error);
            })
            .then(() => {
                this.$store.commit('loaderFalse')
            })
        if (this.product) {
            this.name = this.product.name
        }
        await this.getCities()
        await this.getCategories()
    }
}
</script>

<style scoped>

</style>
