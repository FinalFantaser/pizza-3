<template>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="text-white">Создание продукта</h4>
                <p class="text-white opacity-8">Заполните поля ниже, что бы создать продукт.</p>
            </div>
            <div class="col-lg-6 text-right d-flex flex-column justify-content-center">
                <button
                    @click="addProduct()"
                    type="button"
                    class="btn btn-outline-white mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2"
                >Добавить</button>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="font-weight-bolder">Изображение продукта</h5>
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
                                <label>Город</label>
                                <select
                                    v-model="city"
                                    class="form-select"
                                    aria-label="Default select example"
                                >
                                    <option
                                        v-for="city in stateCities"
                                        :value="city.id"
                                    >{{ city.name }}</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label>Цена</label>
                                <input
                                    placeholder="Введите цену"
                                    v-model="price"
                                    class="form-control"
                                    type="text"
                                >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label>Описание</label>
                                <textarea
                                    placeholder="Опишите продукт"
                                    v-model="description"
                                    class="form-control"
                                    id="exampleFormControlTextarea1"
                                    rows="3"
                                ></textarea>
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
    name: 'ProductInfo',
    data() {
        return {
            name: '',
            category: 'Выберите категорию',
            city: 'Выберите город',
            price: '',
            description: ''
        }
    },
    computed: {
        stateCities() {
            return this.$store.state.serviceCities.cities
        },
        stateCategories() {
            return this.$store.state.serviceCategories.categories
        }
    },
    methods: {
        async getCities() {
            await this.$store.dispatch('getCities')
        },
        async getCategories() {
            await this.$store.dispatch('getCategories')
        },
        addProduct() {
            this.$store.state.argon.loader = true
            console.log(this.city, this.category)
            const arr = {size: 123}
            axios.post('/api/v1/admin/products', {
                name: this.name,
                category_id: this.category,
                city_id: this.city,
                price: this.price,
                price_sell: 0,
                properties: JSON.stringify(arr),
                description: this.description,
            })
                .then((data) => {
                    console.log(data)
                })
                .catch((error) => {
                    console.log(error)
                })
                .then(() => {
                    this.$store.state.argon.loader = false
                })
        }
    },
    async created() {
        await this.getCities()
        await this.getCategories()
    }
}

</script>
