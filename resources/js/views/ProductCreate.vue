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
</template>

<script>

export default {
    name: 'ProductInfo',
    data() {
        return {
            name: '',
            category: 'Выберите категорию',
            cities: [],
            price: '',
            price_sale: '',
            description: '',
            sizes: ''
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
            const properties = {size: []}
            const arr = this.sizes.split(', ')
            arr.forEach(item => {
                properties.size.push(item)
            })
            axios.post('/api/v1/admin/products', {
                name: this.name,
                category_id: this.category,
                city_id: 1,
                price: this.price,
                price_sale: this.price_sale ? this.price_sale: 0,
                properties: JSON.stringify(properties),
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
        this.stateCities.forEach(item => {
            this.cities.push(item.id)
        })
    }
}

</script>
