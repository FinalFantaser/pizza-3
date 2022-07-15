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
                            <div class="col-12 cursor-pointer">
                                <img v-if="!image" @click="selectImage" class="w-100 border-radius-lg shadow-lg mt-3" src="@/assets/img/ProductDefault.png" alt="product_image">

                                <div v-else
                                     class="imagePreviewWrapper w-100 border-radius-lg shadow-lg mt-3"
                                     :style="{ 'background-image' : `url(${previewImage})` }"
                                     @click="selectImage"
                                ></div>
                                <input ref="fileInput" type="file" @input="pickFile" style="display: none">
                            </div>
                            <div class="col-12 mt-5">
                                <div class="d-flex">
                                    <button class="btn btn-primary btn-sm mb-0 me-2" type="button" name="button" @click.prevent="selectImage">Выбрать</button>
<!--                                    <button class="btn btn-outline-dark btn-sm mb-0" type="button" name="button" @click.prevent="previewImage=null,image=null">Удалить</button>-->
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
                        <ul class="nav nav-pills my-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button
                                    ref="pillsBasic"
                                    class="nav-link active"
                                    id="pills-home-tab"
                                    data-bs-toggle="pill"
                                    data-bs-target="#pills-basic"
                                    type="button"
                                    role="tab"
                                    aria-controls="pills-home" aria-selected="true"
                                >Основное</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-options" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Опции</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-basic" role="tabpanel" aria-labelledby="pills-home-tab">
                                <div class="row mb-3">
                                    <div class="col-12 mb-3 mb-sm-0 col-sm-6">
                                        <label>Название</label>
                                        <div class="position-relative">
                                            <input
                                                :style="v$.name.$error ? 'border-color: tomato;' : ''"
                                                v-model="name"
                                                class="form-control"
                                                type="text"
                                                placeholder="Название продукта"
                                            >
                                            <p v-if="v$.name.$error" class="invalid-msg">Обязательное поле</p>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <label>Категория</label>
                                        <div class="position-relative">
                                            <select
                                                :style="v$.category.$error ? 'border-color: tomato;' : ''"
                                                v-model="category"
                                                class="form-select"
                                            >
                                                <option
                                                    v-for="category in stateCategories"
                                                    :value="category.id"
                                                >{{ category.name }}</option>
                                            </select>
                                            <p v-if="v$.category.$error" class="invalid-msg">Обязательное поле</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 mb-3 mb-sm-0 col-sm-6">
                                        <label>Цена</label>
                                        <div class="position-relative mb-3 mb-sm-0">
                                            <input
                                                :style="v$.price.$error ? 'border-color: tomato;' : ''"
                                                placeholder="Введите цену"
                                                v-model="price"
                                                class="form-control mb-sm-3"
                                                type="text"
                                            >
                                            <p v-if="v$.price.$dirty && v$.price.required.$invalid" class="invalid-msg">Обязательное поле</p>
                                            <p v-if="v$.price.numeric.$invalid" class="invalid-msg">Введите число</p>
                                        </div>
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
<!--                                    <div class="col-12 mb-3 mb-sm-0 col-sm-6">-->
<!--                                        <label>Параметры</label>-->
<!--                                        <input-->
<!--                                            v-model="sizes"-->
<!--                                            class="form-control"-->
<!--                                            type="text"-->
<!--                                            placeholder="Введите размеры через запятую"-->
<!--                                        >-->
<!--                                    </div>-->
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
                            <div class="tab-pane fade" id="pills-options" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <product-create-options ref="options"></product-create-options>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import useVuelidate from '@vuelidate/core'
import { required, numeric } from '@vuelidate/validators'
import ProductCreateOptions from './ProductCreateOptions.vue'

export default {
    name: 'ProductInfo',
    setup () {
        return { v$: useVuelidate() }
    },
    components: {
        ProductCreateOptions
    },
    data() {
        return {
            name: '',
            category: '',
            cities: [],
            price: '',
            price_sale: '',
            description: '',
            // sizes: '',
            previewImage: null,
            image: null
        }
    },
    computed: {
        stateCities() {
            return this.$store.getters['serviceCities/stateCities']
        },
        stateCategories() {
            return this.$store.getters['serviceCategories/stateCategories']
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
                this.image = file[0]
            }
        },
        async getCities() {
            await this.$store.dispatch('serviceCities/getCities')
        },
        async getCategories() {
            await this.$store.dispatch('serviceCategories/getCategories')
        },
        async addProduct() {
            const isFormCorrect = await this.v$.$validate()
            // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
            if (!isFormCorrect) {
                this.$refs.pillsBasic.click()
                return
            }
            this.$store.commit('loaderTrue')
            const options = this.$refs.options.readyData()
            console.log(options)
            const data = new FormData()
            data.append('name',this.name)
            data.append('category_id', this.category)
            data.append('city_id', JSON.stringify(this.cities))
            data.append('price', this.price)
            data.append('price_sale', this.price_sale ? this.price_sale: 0)
            data.append('description', this.description)
            if(options.length > 0) {
                data.append('options', JSON.stringify(options))
            }
            if(this.image) {
                data.append('productImage', this.image)
            }
            // if (this.sizes) {
            //
            //     const arr = this.sizes.split(', ')
            //     const properties = { size:  arr  }
            //
            //     data.append('properties', JSON.stringify(properties))
            // }

            axios.post('/api/v1/admin/products', data)
                .then((data) => {
                    this.$router.push({name: 'Products'})
                    console.log(data)
                })
                .catch((error) => {
                    console.log(error)
                })
                .then(() => {
                    this.$store.commit('loaderFalse')
                })
        }
    },
    validations () {
        return {
            name: { required },
            price: { required, numeric },
            category: { required },
        }
    },
    async created() {
        await this.getCities()
        await this.getCategories()
        if(this.stateCities) {
            this.stateCities.forEach(item => {
                this.cities.push(item.id)
            })
        }
    }
}

</script>

<style scoped>
.imagePreviewWrapper{
    width: 250px;
    height: 250px;
    display: block;
    cursor: pointer;
    margin: 0 auto 30px;
    background-size: cover;
    background-position: center center;
}
.invalid-msg {
    position: absolute;
    bottom: -28px;
    left: 0;
    transform: translateY(-50%);
    margin: 0;
    font-size: 12px;
    color: tomato;
}

.nav-pills .nav-link.active, .nav-pills .show > .nav-link {
    color: #fff;
    background-color: #0d6efd;
}
</style>
