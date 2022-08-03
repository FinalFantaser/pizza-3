<template>
    <div class="container-fluid py-2 p-sm-4">
        <div class="row m-2 mb-sm-3">
            <div class="col-lg-6">
                <h4 class="text-white">Создание постера</h4>
                <p class="text-white opacity-8 m-0">Заполните поля ниже, что бы создать постер.</p>
            </div>
            <div class="col-lg-6 text-right d-flex flex-column justify-content-center">
                <button
                    @click="addPoster()"
                    type="button"
                    class="btn btn-outline-white mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2"
                >Создать</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="font-weight-bolder">Изображение постера</h5>
                        <div class="row">
                            <div class="col-12 cursor-pointer">
                                <div class="position-relative">
                                    <img
                                        :style="v$.image.$error ? 'border: 1px solid tomato;' : ''"
                                        v-if="!image"
                                        @click="selectImage"
                                        class="w-100 border-radius-lg shadow-lg mt-3"
                                        src="@/assets/img/PosterDefault.png"
                                        alt="product_image"
                                    >

                                    <div v-else
                                         class="imagePreviewWrapper w-100 border-radius-lg shadow-lg mt-3"
                                         :style="{ 'background-image' : `url(${previewImage})` }"
                                         @click="selectImage"
                                    ></div>
                                    <input ref="fileInput" type="file" @input="pickFile" style="display: none">
                                    <p v-if="v$.image.$error" class="invalid-msg invalid-msg--img">Выберите изображение</p>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="d-flex">
                                    <button class="btn btn-primary btn-sm mb-0 me-2" type="button" name="button" @click.prevent="selectImage">Выбрать</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 mt-lg-0 mt-4">
                <div class="card">
                    <div class="card-body p-2">
                        <h5 class="font-weight-bolder">Информация о постере</h5>
                        <div class="row mb-3">
                            <div class="col-12 mb-3 mb-sm-0 col-sm-6">
                                <label>Название</label>
                                <div class="position-relative">
                                    <input
                                        :style="v$.name.$error ? 'border-color: tomato;' : ''"
                                        v-model="name"
                                        class="form-control"
                                        type="text"
                                        placeholder="Название постера"
                                    >
                                    <p v-if="v$.name.$error" class="invalid-msg">Обязательное поле</p>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6" >
                                <label>{{ enabled ? 'Включен' : 'Выключен' }}</label>
                                <div class="form-check form-switch ps-6">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        v-model="enabled"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-sm-6">
                                <label>Ссылка</label>
                                <div class="position-relative">
                                    <input
                                        v-model="anchor"
                                        class="form-control"
                                        type="text"
                                        placeholder="Ссылка"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-sm-6">
                                <label>Описание</label>
                                <div class="position-relative">
                                    <textarea
                                        :style="v$.description.$error ? 'border-color: tomato;' : ''"
                                        placeholder="Описание постера"
                                        v-model="description"
                                        class="form-control"
                                        id="exampleFormControlTextarea1"
                                        rows="5"
                                    ></textarea>
                                    <p v-if="v$.description.$error" class="invalid-msg">Обязательное поле</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <label>Города</label>
                                <div v-for="(city, index) in stateCities" class="form-check">
                                    <input
                                        :id="'city ' + (index + 1)"
                                        v-model="cities"
                                        :value="city.id"
                                        class="form-check-input"
                                        type="checkbox"
                                    >
                                    <label
                                        :for="'city ' + (index + 1)"
                                        class="form-check-label">
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
import useVuelidate from '@vuelidate/core'
import { required } from '@vuelidate/validators'
import router from "../router";

export default {
    name: "PosterCreate",
    setup () {
        return { v$: useVuelidate() }
    },
    data() {
        return {
            name: '',
            enabled: true,
            anchor: '',
            description: '',
            cities: [],
            previewImage: null,
            image: null
        }
    },
    computed: {
        stateCities() {
            return this.$store.getters['serviceCities/stateCities']
        }
    },
    methods: {
        async addPoster() {
            const isFormCorrect = await this.v$.$validate()
            // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
            if (!isFormCorrect) return
            this.$store.commit('loaderTrue')

            const data = new FormData()
            data.append('name',this.name)
            data.append('city_id', JSON.stringify(this.cities))
            data.append('enabled', this.enabled)
            data.append('anchor', this.anchor)
            data.append('description', this.description)
            data.append('posterImage', this.image)
            console.log(data)
            axios.post('/api/v1/admin/posters', data)
                .then((data) => {
                    this.$router.push({name: "posters"})
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
    },
    validations () {
        return {
            name: { required },
            description: { required },
            image: { required },
        }
    },
    async created() {
        await this.getCities()
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
.invalid-msg--img {
    text-align: center;
    bottom: auto;
    width: 100%;
    left: 0;
    top: 5px;
}
</style>
