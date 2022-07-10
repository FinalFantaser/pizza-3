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
    <h4 v-else class="text-white text-center ь-4">Категория не найдена</h4>
</template>

<script>
export default {
    name: "CategoryEdit",
    data() {
        return {
            name: '',
            description: '',
            category: null
        }
    },
    computed: {
        stateCategories() {
            return this.$store.state.serviceCategories.categories
        }
    },
    methods: {
        async getCategories() {
            await this.$store.dispatch('getCategories')
        },
        editCategory() {
            this.$store.state.argon.loader = true
            axios.put(`/api/v1/admin/categories/${this.category.id}`, {
                name: this.name
            })
                .then(data => {
                    console.log(data)
                })
                .catch( (error) => {
                    console.log(error);
                })
                .then(() => {
                    this.$store.state.argon.loader = false
                })
        }
    },
    async created() {
        await this.getCategories()
        if(this.stateCategories) {
            this.category = this.stateCategories.find((elem) => {
                return elem.id == this.$route.params.id
            })
            if(this.category) {
                this.name = this.category.name
            }
        }
    }
}
</script>

<style scoped>

</style>
