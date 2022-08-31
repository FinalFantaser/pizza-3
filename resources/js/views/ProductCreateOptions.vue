<template>
    <div class="row">
        <div class="col-4 pe-4">
            <h6 class="font-weight-bolder">Выберите опцию</h6>
            <hr>
            <select v-model="option" class="form-select mb-3" aria-label="Default select example">
                <option value="null" disabled>Выберите опцию</option>
                <option v-for="option in stateOptions" :value="option">{{ option.name }}</option>
            </select>

            <div v-if="option" class="d-flex justify-content-between align-items-center">
                <p class="m-0 lh-1">{{ option.name }}</p>
                <button
                    @click="addOption(option)"
                    class="btn m-0 btn-success text-lg px-3"
                >
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <div class="col-8">
            <h6 class="font-weight-bolder">Добавленные опции:</h6>
            <hr>
            <div v-for="(option, index) in finishOptions" class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="font-weight-bolder">{{ addedOption[index].name }}</h6>
                    <div @click="deleteOption(index)" class="addItem addItem--plus mt-2">
                        <i class="fa fa-minus-circle text-danger" aria-hidden="true"></i>
                    </div>
                </div>
                <div v-for="(item, index2) in finishOptions[index].items" class="d-flex justify-content-between option__item p-2 mb-2">
                    <div class="col-5">
                        <label>Выберите значение</label>
                        <select
                            @change="addImgUrlInItem(index, index2)"
                            v-model="finishOptions[index].items[index2].name"
                            class="form-select mb-3" aria-label="Default select example"
                        >
                            <option v-for="item in addedOption[index].items" :value="item.name">{{ item.name }}</option>
                        </select>
                    </div>
                    <div class="col-5">
                        <label>Цена</label>
                        <input
                            v-model="finishOptions[index].items[index2].price"
                            class="form-control"
                            type="number"
                            placeholder="Укажите цену"
                            min="0"
                        >
                    </div>
                    <div v-if="finishOptions[index].items[index2].imgUrl" class="product__options__item__img">
                        <img :src="finishOptions[index].items[index2].imgUrl" alt="img">
                    </div>
                    <div v-if="finishOptions[index].items.length !== 1" @click="deleteItem(index, index2)" class="cursor-pointer">
                        <i class="fa fa-minus-circle text-danger" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="col-12 option__item p-2">
                    <label class="mx-auto d-block text-center m-0">Добавьте значение</label>
                    <div @click="addItem(index)" class="addItem addItem--plus mx-auto">
                        <i class="fa fa-plus-circle text-success" aria-hidden="true"></i>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ProductCreateOptions",
    data() {
        return {
            option: null,
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
        addImgUrlInItem(index, index2) {
            const imgUrl = this.addedOption[index].items.find(el => {
                return el.name === this.finishOptions[index].items[index2].name
            }).url
            this.finishOptions[index].items[index2].imgUrl = imgUrl
        },
        readyData() {
            const arr = JSON.parse(JSON.stringify(this.finishOptions))
            if(arr.length > 0) {
                arr.forEach(item => {
                    item.items.forEach((item2, index2, array2) => {
                        if(item2.name === '' || item2.price === '') {
                            console.log('hello')
                            array2.splice(index2,1)
                        }
                    })
                })
                arr.forEach((item, index, array) => {
                    if(item.items.length == 0) {
                        array.splice(index, 1)
                    }
                })
                arr.forEach((item) => {
                    item.items = JSON.stringify(item.items)
                })
            }

            return arr
        },
        addItem(index) {
            const obj = {
                name: '',
                price: '',
                imgUrl: null
            }
            this.finishOptions[index].items.push(obj)
        },
        deleteItem(index, index2) {
            this.finishOptions[index].items.splice(index2, 1)
        },
        deleteOption(index) {
            this.addedOption.splice(index, 1)
            this.finishOptions.splice(index, 1)
        },
        addOption(option) {
            this.option = null
            this.addedOption.push(JSON.parse(JSON.stringify(option)))
            const obj = {}
            obj.items = [{
                name: '',
                price: '',
                imgUrl: null
            }]
            obj.option_id = option.id
            this.finishOptions.push(obj)
            // this.$store.commit('loaderTrue')
            // axios.get(`/api/v1/admin/options/${id}`)
            //     .then(data => {
            //         this.option = ''
            //         this.$store.commit('loaderFalse')
            //         this.addedOption.push(JSON.parse(JSON.stringify(data.data.data)))
            //         data.data.data.items = [{
            //             name: '',
            //             price: ''
            //         }]
            //         this.finishOptions.push(JSON.parse(JSON.stringify(data.data.data)))
            //         console.log(this.addedOption)
            //     })
            //     .catch(error => {
            //         this.$store.dispatch('getToast', { msg: 'Что-то пошло не так!', settings: {
            //                 type: 'error'
            //             } })
            //         this.$store.commit('loaderFalse')
            //         console.log(error)
            //     })
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
.option__item {
    border: 1px solid #6c757d;
    border-radius: 5px;
}
.addItem {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    padding: 0;
    cursor: pointer;
    font-size: 22px;
}
.product__options__item__img {
    width: 70px;
    height: 52px;
    display: flex;
    justify-content: center;
    align-items: center;
    align-self: center;
    margin: 5px;
}
.product__options__item__img img {
    max-width: 100%;
    max-height: 100%;
}
</style>
