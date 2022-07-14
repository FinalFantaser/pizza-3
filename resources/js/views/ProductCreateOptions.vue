<template>
    <div class="row">
        <div class="col-4 pe-4">
            <h6 class="font-weight-bolder">Выберите опцию</h6>
            <hr>
            <select v-model="option" class="form-select mb-3" aria-label="Default select example">
                <option v-for="option in stateOptions" :value="option">{{ option.name }}</option>
            </select>

            <div v-if="option" class="d-flex justify-content-between align-items-center">
                <p class="m-0 lh-1">{{ option.name }}</p>
                <button
                    @click="addOption(option.id)"
                    class="btn m-0 btn-success text-lg"
                >
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <div class="col-8">
            <h6 class="font-weight-bolder">Добавленные опции:</h6>
            <hr>
            <div v-for="(option, index) in addedOption" class="col-12">
                <h6 class="font-weight-bolder">{{ addedOption[index].name }}</h6>
                <div v-for="(item, index2) in finishOptions[index].items" class="d-flex justify-content-between">
                    <div class="col-5">
                        <label>Выберите значение</label>
                        <select v-model="finishOptions[index].items[index2].name" class="form-select mb-3" aria-label="Default select example">
                            <option v-for="item in addedOption[index].items" :value="item">{{ item }}</option>
                        </select>
                    </div>
                    <div class="col-5">
                        <label>Цена</label>
                        <input
                            v-model="finishOptions[index].items[index2].price"
                            class="form-control"
                            type="text"
                            placeholder="Укажите цену"
                        >
                    </div>
                </div>
                <hr>
            </div>
        </div>
        {{finishOptions}}
    </div>
</template>

<script>
export default {
    name: "ProductCreateOptions",
    data() {
        return {
            option: '',
            addedOption: [],
            finishOptions: []
        }
    },
    computed: {
        stateOptions() {
            return this.$store.getters['serviceOptions/stateOptions']
        }
    },
    methods: {
        addOption(id) {
            this.$store.commit('loaderTrue')
            axios.get(`/api/v1/admin/options/${id}`)
                .then(data => {
                    this.option = ''
                    this.$store.commit('loaderFalse')
                    this.addedOption.push(JSON.parse(JSON.stringify(data.data.data)))
                    data.data.data.items = [{
                        name: '',
                        price: ''
                    }]
                    this.finishOptions.push(JSON.parse(JSON.stringify(data.data.data)))
                    console.log(this.addedOption)
                })
                .catch(error => {
                    this.$store.dispatch('getToast', { msg: 'Что-то пошло не так!', settings: {
                            type: 'error'
                        } })
                    this.$store.commit('loaderFalse')
                    console.log(error)
                })
        },
        async getOptions() {
            await this.$store.dispatch('serviceOptions/getOptions')
        }
    },
    async created() {
        await this.getOptions()
    }
}
</script>

<style scoped>

</style>
