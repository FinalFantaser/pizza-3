<template>
    <div v-if="product" class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="text-white">Редактирование продукта</h4>
                <p class="text-white opacity-8">Отредактируйте информацию о продукте ниже.</p>
            </div>
            <div class="col-lg-6 text-right d-flex flex-column justify-content-center">
                <button
                    @click="editProduct()"
                    type="button"
                    class="btn btn-outline-white mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2">Сохранить</button>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="font-weight-bolder">Изображение продукта</h5>
                        <div class="row">
                            <div class="col-12 cursor-pointer">
                                <img v-if="!imgPath && !img" @click="selectImage" class="w-100 border-radius-lg shadow-lg mt-3" src="@/assets/img/ProductDefault.png" alt="category_image">
                                <img v-else-if="imgPath && !img"
                                     @click="selectImage"
                                     class="w-100 border-radius-lg shadow-lg mt-3"
                                     :src="imgPath"
                                     alt="category_image"
                                >
                                <div v-else
                                     class="imagePreviewWrapper w-100 border-radius-lg shadow-lg mt-3"
                                     :style="{ 'background-image' : `url(${previewImage})` }"
                                     @click="selectImage"
                                ></div>
                                <input ref="fileInput" type="file" @input.prevent="pickFile" style="display: none">
                            </div>
                            <div class="col-12 mt-5">
                                <div class="d-flex">
                                    <button class="btn btn-primary btn-sm mb-0 me-2" type="button" name="button" @click.prevent="selectImage">Выбрать</button>
<!--                                    <button-->
<!--                                        class="btn btn-outline-dark btn-sm mb-0"-->
<!--                                        type="button"-->
<!--                                        name="button"-->
<!--                                        @click.prevent="imgPath=null, previewImage=null, img=null"-->
<!--                                    >Удалить</button>-->
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
                                    v-model="category_id"
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
            category_id: '',
            sizes: '',
            imgPath: null,
            previewImage: null,
            img: null
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
        selectImage() {
            this.$refs.fileInput.click()
        },
        pickFile() {
            let input = this.$refs.fileInput
            let file = input.files
            if (file && file[0]){
                let reader = new FileReader
                reader.onload = e => {
                    this.previewImage = e.target.result
                }
                reader.readAsDataURL(file[0])
                this.img = file[0]
            }
        },
        editProduct() {
            this.$store.commit('loaderTrue')

            const data = new FormData()
            data.append('name',this.name)
            data.append('category_id', this.category_id)
            data.append('city_id', JSON.stringify(this.cities))
            data.append('price', this.price)
            data.append('price_sale', this.price_sale ? this.price_sale: 0)
            data.append('description', this.description)
            if(this.img) {
                data.append('productImage', this.img)
            }
            if (this.sizes) {

                const arr = this.sizes.split(', ')
                const properties = {size: arr }

                data.append('properties', JSON.stringify(properties))
            }
            data.append("_method", "put");
            axios.post(`/api/v1/admin/products/${this.product.id}`, data)
                .then((data) => {
                    window.location.href = '/products'
                    console.log(data)
                })
                .catch((error) => {
                    console.log(error)
                })
                .then(() => {
                    this.$store.commit('loaderFalse')
                })
        },
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
        await this.getCities()
        await this.getCategories()
        if (this.product) {
            this.name = this.product.name
            this.category_id = this.product.category.id
            this.price = this.product.price
            this.price_sale = this.product.price_sale !== 0 ? this.product.price_sale : ''
            this.description = this.product.description ? this.product.description : ''
            if(this.product.properties) {
                this.sizes = this.product.properties.size.join(', ')
            }
            this.imgPath = this.product.thumbUrl
            if(this.product.cities.length > 0) {
                this.product.cities.forEach(item => {
                    this.cities.push(item.id)
                })
            }
        }
    }
}
</script>

<style scoped>
.imagePreviewWrapper{
    height: 250px;
    display: block;
    cursor: pointer;
    margin: 0 auto 30px;
    background-size: cover;
    background-position: center center;
}
</style>
