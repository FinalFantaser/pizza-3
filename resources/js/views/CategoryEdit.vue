<template>
    <div v-if="category" class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="text-white">Редактирование категории</h4>
                <p class="text-white opacity-8">Отредактируйте данные о категории ниже.</p>
            </div>
            <div class="col-lg-6 text-right d-flex flex-column justify-content-center">
                <button
                    @click="editCategory()"
                    type="button"
                    class="btn btn-outline-white mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2"
                >
                    Сохранить
                </button>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="font-weight-bolder">Изображение категории</h5>
                        <div class="row">
                            <div class="col-12 cursor-pointer">
                                <img v-if="!imgPath && !img" @click="selectImage" class="w-100 border-radius-lg shadow-lg mt-3" src="@/assets/img/CategoryDefault.png" alt="category_image">
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
                                    <button class="btn btn-primary btn-sm mb-0 me-2" type="button" name="button" @click.prevent="selectImage">Загрузить</button>
                                    <button
                                        class="btn btn-outline-dark btn-sm mb-0"
                                        type="button"
                                        name="button"
                                        @click.prevent="imgPath=null, previewImage=null, img=null"
                                    >Удалить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 mt-lg-0 mt-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="font-weight-bolder text-center">Информация о категории</h5>
                        <div class="row mb-3">
                            <div class="col-12 col-sm-8 m-auto">
                                <label>Название</label>
                                <div class="position-relative">
                                    <input
                                        :style="v$.name.$error ? 'border-color: tomato;' : ''"
                                        v-model="name"
                                        class="form-control"
                                        type="text"
                                        placeholder="Введите название"
                                    >
                                    <p v-if="v$.name.$error" class="invalid-msg">Обязательное поле</p>
                                </div>
                            </div>
                            <div class="col-12 col-sm-8 m-auto">
                                <label>Порядок сортировки</label>
                                <div class="position-relative">
                                    <input
                                        :style="v$.sort.$error ? 'border-color: tomato;' : ''"
                                        v-model="sort"
                                        class="form-control"
                                        type="number"
                                        min="0"
                                        placeholder="Порядок сортировки"
                                    >
                                    <p v-if="v$.sort.$error" class="invalid-msg">Обязательное поле</p>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-sm-3">
                            <div class="col-12 col-sm-8 m-auto">
                                <label>Описание</label>
                                <textarea
                                    placeholder="Введите описание"
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
    <h4 v-else class="text-white text-center ь-4">Категория не найдена</h4>
</template>

<script>
import useVuelidate from '@vuelidate/core'
import { required } from '@vuelidate/validators'

export default {
    name: "CategoryEdit",
    setup () {
        return { v$: useVuelidate() }
    },
    data() {
        return {
            name: '',
            sort: '',
            description: '',
            category: null,
            img: null,
            previewImage: null,
            imgPath: null
        }
    },
    computed: {
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
            if (file && file[0]) {
                let reader = new FileReader
                reader.onload = e => {
                    this.previewImage = e.target.result
                }
                reader.readAsDataURL(file[0])
                this.img = file[0]
                console.log(this.img)
            }
        },
        async getCategories() {
            await this.$store.dispatch('serviceCategories/getCategories')
        },
        async editCategory() {
            const isFormCorrect = await this.v$.$validate()
            // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
            if (!isFormCorrect) return
            this.$store.commit('loaderTrue')
            const data = new FormData()
            data.append('name', this.name)
            data.append('sort', this.sort)
            if(this.img) {
                data.append('categoryImage', this.img)
            }
            data.append("_method", "put");
            axios.post(`/api/v1/admin/categories/${this.category.id}`, data)
                .then(data => {
                    window.location.href = '/categories'
                    console.log(data)
                })
                .catch( (error) => {
                    this.$store.dispatch('getToast', { msg: 'Что-то пошло не так!', settings: {
                            type: 'error'
                        } })
                    console.log(error);
                })
                .then(() => {
                    this.$store.commit('loaderFalse')
                })
        }
    },
    validations () {
        return {
            name: { required },
            sort: { required }
        }
    },
    async created() {
        this.$store.commit('loaderTrue')
        await axios.get(`/api/v1/admin/categories/${this.$route.params.id}`)
            .then(data => {
                this.category = data.data.data
                console.log(data.data.data)
            })
            .catch( (error) => {
                console.log(error);
            })
            .then(() => {
                this.$store.commit('loaderFalse')
            })
        if (this.category) {
            this.name = this.category.name
            this.sort = this.category.sort
            this.imgPath = this.category.thumbUrl
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
.invalid-msg {
    position: absolute;
    bottom: -28px;
    left: 0;
    transform: translateY(-50%);
    margin: 0;
    font-size: 12px;
    color: tomato;
}
</style>
