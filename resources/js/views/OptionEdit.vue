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
                                <label>Значение</label>
                                <div v-for="(item, index) in items" class="row flex-nowrap justify-content-between mb-3">
                                    <div class="col-12 position-relative">
                                        <input
                                            :style="v$.items.$model[index] === '' && v$.items.$dirty ? 'border-color: tomato;' : ''"
                                            v-model="items[index]"
                                            class="form-control"
                                            type="text"
                                            placeholder="Введите значение"
                                        >
                                        <p v-if="v$.items.$model[index] === '' && v$.items.$dirty" class="invalid-msg invalid-msg--item">Заполните поле</p>
                                    </div>
                                    <div v-if="items.length > 1 " @click="deleteItem(index)" class="addItem">
                                        <i class="fa fa-minus-circle text-danger" aria-hidden="true"></i>
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
            option: null,
            name: '',
            items: []
        }
    },
    methods: {
        addItem() {
            this.items.push('')
        },
        deleteItem(index) {
            this.items.splice(index, 1)
        },
        async editOption() {
            const isFormCorrect = await this.v$.$validate()
            // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
            if (!isFormCorrect) return
            axios.put(`/api/v1/admin/options/${this.option.id}`, {
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
                if(arr[i] === '') {
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
            this.name = this.option.name
            this.items = this.option.items
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
</style>
