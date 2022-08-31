<template>
    <div v-if="option" class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="text-white">Редактирование опции</h4>
                <p class="text-white opacity-8">Отредактируйте информацию ниже.</p>
            </div>
            <div class="col-lg-6 text-right d-flex flex-column justify-content-center">
                <button
                    @click="editOption"
                    type="button"
                    class="btn btn-outline-white mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2"
                >
                    Сохранить
                </button>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-8 m-auto mt-lg-0 mt-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="font-weight-bolder text-center">Информация опции</h5>
                        <div class="row">

                            <div class="col-12 mb-3 col-sm-8 m-auto">
                                <label>Тип</label>
                                <div class="position-relative">
                                    <select
                                        v-model="type"
                                        class="form-select"
                                    >
                                        <option value="size">Размер</option>
                                        <option value="additional">Дополнительный ингредиент</option>
                                        <option value="other">Другое</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 mb-4 col-sm-8 m-auto">
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

                            <hr>

                            <div class="col-12 col-sm-8 m-auto">
                                <div v-for="(item, index) in items" class="mb-3">
                                    <div class="row flex-nowrap justify-content-between mb-3">
                                        <div class="col-11 position-relative">
                                            <label>Значение</label>
                                            <input
                                                :style="v$.items.$model[index].name === '' && v$.items.$dirty ? 'border-color: tomato;' : ''"
                                                v-model="items[index].name"
                                                class="form-control"
                                                type="text"
                                                placeholder="Введите значение"
                                            >
                                            <p v-if="v$.items.$model[index].name === '' && v$.items.$dirty" class="invalid-msg invalid-msg--item">Заполните поле</p>
                                        </div>
                                        <div v-if="items.length > 1 " @click="deleteItem(index)" class="addItem col-1">
                                            <i class="fa fa-minus-circle text-danger" aria-hidden="true"></i>
                                        </div>
                                    </div>

                                    <div class="option__item__img-wrap">
                                        <label
                                            :class="{'options__item__img--noimg' : !items[index].url}"
                                            class="col-12 cursor-pointer mb-2 options__item__img m-0"
                                        >
                                            <p v-if="!items[index].url" class="options__item__img__text m-0">Выберите изображение при необходимости</p>

                                            <div v-else
                                                 class="options__item__img__preview border-radius-lg shadow-lg"
                                                 :style="{ 'background-image' : `url(${items[index].url})` }"
                                            ></div>
                                            <input ref="fileInputs" type="file" @input="pickFile(index)" style="display: none">
                                        </label>
                                        <div class="col-12">
                                            <div class="d-flex">
                                                <button @click.prevent="selectImage(index)" class="btn btn-primary btn-sm mb-0 w-100">Выбрать</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div @click="addItem" class="addItem addItem--plus m-auto">
                                    <i class="fa fa-plus-circle text-success" aria-hidden="true"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h4 v-else class="text-white text-center ь-4">Опция не найдена</h4>
</template>

<script>
import useVuelidate from '@vuelidate/core'
import { required } from '@vuelidate/validators'

export default {
    name: "OptionsEdit",
    setup () {
        return { v$: useVuelidate() }
    },
    data() {
        return {
            type: null,
            name: '',
            option: null,
            items: [],
            image: [],
        }
    },
    methods: {
        selectImage(index) {
            this.$refs.fileInputs[index].click()
        },
        pickFile(index) {
            const input = this.$refs.fileInputs[index]
            const file = input.files
            if (file && file[0]){
                this.image = file[0]
                console.log(this.image)
                const data = new FormData()
                data.append('image', this.image)

                axios.post('/api/v1/admin/media', data)
                    .then(response => {
                        console.log(response.data)
                        this.items[index].url = response.data.imgUrl
                        this.image = null
                    })
                    .catch(error => {
                        console.log(error)
                        this.$store.dispatch('getToast', { msg: 'Что-то пошло не так!', settings: {
                                type: 'error'
                            } })
                    })

            }
        },
        addItem() {
            const obj = {
                name: '',
                url: null
            }
            this.items.push(obj)
        },
        deleteItem(index) {
            this.items.splice(index, 1)
        },
        async editOption() {
            const isFormCorrect = await this.v$.$validate()
            // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
            if (!isFormCorrect) return
            axios.put(`/api/v1/admin/options/${this.option.id}`, {
                checkout_type: this.type,
                name: this.name,
                type_id: 1,
                items: JSON.stringify(this.items)
            })
                .then(response => {
                    this.$router.push({name: 'Options'})
                    this.$store.commit('loaderFalse')
                    console.log(response)
                })
                .catch(error => {
                    this.$store.dispatch('getToast', { msg: 'Что-то пошло не так!', settings: {
                            type: 'error'
                        } })
                    this.$store.commit('loaderFalse')
                    console.log(error)
                })
        }
    },
    validations () {
        const allInputs = (arr) => {
            for (let i = 0; i < arr.length; i++ ) {
                if(arr[i].name === '') {
                    return false
                }
            }
            return true
        }

        return {
            name: {required},
            items: { allInputs }
        }
    },
    async created() {
        this.$store.commit('loaderTrue')
        await axios.get(`/api/v1/admin/options/${this.$route.params.id}`)
            .then(data => {
                this.option = data.data.data
                console.log(data.data.data)
            })
            .catch( (error) => {
                console.log(error);
            })
            .then(() => {
                this.$store.commit('loaderFalse')
            })
        if (this.option) {
            this.type = this.option.checkout_type
            this.name = this.option.name
            this.items = JSON.parse(JSON.stringify(this.option.items))
        }
    }
}
</script>

<style scoped>
.invalid-msg {
    position: absolute;
    bottom: -28px;
    left: 0;
    transform: translateY(-50%);
    margin: 0;
    font-size: 12px;
    color: tomato;
}
.invalid-msg--item {
    left: 13px;
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
.addItem--plus {
    font-size: 30px;
}
.option__item__img-wrap {
    max-width: 120px;
}
.options__item__img {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 120px;
    height: 90px;
}
.options__item__img__preview {
    width: 100%;
    height: 100%;
    background-repeat: no-repeat;
    background-position: center;
    background-size: contain;
}
.options__item__img--noimg {
    border: 1px solid gray;
    border-radius: 10px;
    padding: 5px;
}
.options__item__img__text {
    font-size: 10px;
    text-align: center;
}
</style>
