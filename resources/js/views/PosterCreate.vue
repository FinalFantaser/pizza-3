<template>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="text-white">Создание постера</h4>
                <p class="text-white opacity-8">Заполните поля ниже, что бы создать постер.</p>
            </div>
            <div class="col-lg-6 text-right d-flex flex-column justify-content-center">
                <button
                    @click="addPoster()"
                    type="button"
                    class="btn btn-outline-white mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2"
                >Добавить</button>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="font-weight-bolder">Изображение постера</h5>
                        <div class="row">
                            <div class="col-12 cursor-pointer">
                                <img v-if="!image" @click="selectImage" class="w-100 border-radius-lg shadow-lg mt-3" src="@/assets/img/PosterDefault.png" alt="product_image">

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
                                <p>{{enabled}}</p>
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
    name: "PosterCreate",
    data() {
        return {
            name: '',
            enabled: true,
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
        addPoster() {
            this.$store.commit('loaderTrue')

            const data = new FormData()
            data.append('name',this.name)
            data.append('city_id', JSON.stringify(this.cities))
            data.append('enabled', this.enabled)
            data.append('description', this.description)
            if(this.image) {
                data.append('posterImage', this.image)
            }
            console.log(JSON.parse(data.get('enabled')))
            axios.post('/api/v1/admin/posters', data)
                .then((data) => {
                    // window.location.href = '/posters'
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
</style>
