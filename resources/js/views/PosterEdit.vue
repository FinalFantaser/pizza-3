<template>
    <div v-if="poster" class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="text-white">Редактирование постера</h4>
                <p class="text-white opacity-8">Отредактируйте информацию о постере ниже.</p>
            </div>
            <div class="col-lg-6 text-right d-flex flex-column justify-content-center">
                <button
                    @click="editPoster()"
                    type="button"
                    class="btn btn-outline-white mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2">Сохранить</button>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="font-weight-bolder">Изображение постера</h5>
                        <div class="row">
                            <div class="col-12 cursor-pointer">
                                <img v-if="!imgPath && !img" @click="selectImage" class="w-100 border-radius-lg shadow-lg mt-3" src="@/assets/img/PosterDefault.png" alt="category_image">
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
                        <h5 class="font-weight-bolder">Информация о постере</h5>
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
                        <div class="row mb-sm-3">
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
    <h4 v-else class="text-white text-center ь-4">Постер не найден</h4>
</template>

<script>
export default {
    name: "ProductEdit",
    data() {
        return {
            poster: null,
            name: '',
            enabled: null,
            cities: [],
            description: '',
            imgPath: null,
            previewImage: null,
            img: null,
        }
    },
    computed: {
        stateCities() {
            return this.$store.getters['serviceCities/stateCities']
        },
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
        editPoster() {
            this.$store.commit('loaderTrue')

            const data = new FormData()
            data.append('name',this.name)
            data.append('city_id', JSON.stringify(this.cities))
            data.append('description', this.description)
            data.append('enabled', this.enabled)
            if(this.img) {
                data.append('productImage', this.img)
            }
            data.append("_method", "put");
            axios.post(`/api/v1/admin/posters/${this.poster.id}`, data)
                .then((data) => {
                    window.location.href = '/posters'
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
        }
    },
    async created() {
        this.$store.commit('loaderTrue')
        await axios.get(`/api/v1/admin/posters/${this.$route.params.id}`)
            .then(data => {
                this.poster = data.data.data
                console.log(data.data.data)
            })
            .catch( (error) => {
                console.log(error);
            })
            .then(() => {
                this.$store.commit('loaderFalse')
            })
        await this.getCities()
        if (this.poster) {
            this.name = this.poster.name
            this.description = this.poster.description
            this.imgPath = this.poster.thumbUrl
            this.enabled = this.poster.enabled == 1
            if(this.poster.cities.length > 0) {
                this.poster.cities.forEach(item => {
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
