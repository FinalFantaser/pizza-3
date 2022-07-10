<template>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="text-white">Создание категории</h4>
                <p class="text-white opacity-8">Заполните поля ниже, что бы создать категорию.</p>
            </div>
            <div class="col-lg-6 text-right d-flex flex-column justify-content-center">
                <button
                    @click="addCategory"
                    type="button"
                    class="btn btn-outline-white mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2"
                >
                    Добавить
                </button>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="font-weight-bolder">Изображение категории</h5>
                        <div class="row">
                            <div class="col-12">
                                <img v-if="!image" @click="selectImage" class="w-100 border-radius-lg shadow-lg mt-3" src="https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/product-page.jpg" alt="product_image">

                                <div v-else
                                    class="imagePreviewWrapper w-100 border-radius-lg shadow-lg mt-3"
                                    :style="{ 'background-image' : `url(${previewImage})` }"
                                    @click="selectImage"
                                ></div>
                                <input ref="fileInput" type="file" @input="pickFile" style="display: none">
                            </div>
                            <div class="col-12 mt-5">
                                <div class="d-flex">
                                    <button class="btn btn-primary btn-sm mb-0 me-2" type="button" name="button" @click.prevent="selectImage">Edit</button>
                                    <button class="btn btn-outline-dark btn-sm mb-0" type="button" name="button" @click.prevent="previewImage=null,image=null">Remove</button>
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
                        <div class="row mb-sm-3">
                            <div class="col-12 col-sm-8 m-auto">
                                <label>Название</label>
                                <input
                                    v-model="name"
                                    class="form-control"
                                    type="text"
                                >
                            </div>
                        </div>
                        <div class="row mb-sm-3">
                            <div class="col-12 col-sm-8 m-auto">
                                <label>Описание</label>
                                <textarea
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
    name: "CategoryInfo",
    data() {
        return {
            name: '',
            description: '',
            previewImage: null,
            image: null
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
        addCategory() {
            this.$store.state.argon.loader = true

            const data = new FormData()
            data.append('name',this.name)
            data.append('categoryImage', this.image)

            axios.post('/api/v1/admin/categories', data)
                .then((data) => {
                    this.name = ''
                    console.log(data)
                })
                .catch((error) => {
                    console.log(error)
                })
                .then(() => {
                    this.$store.state.argon.loader = false
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
