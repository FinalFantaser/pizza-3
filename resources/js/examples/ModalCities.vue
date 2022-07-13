<template>
    <!-- Modal -->
    <div class="modal fade" id="ModalCities" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form @submit.prevent="changeOrAddCity()" action="#" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        {{ editCity ? 'Измените город' : 'Добавьте город' }}
                    </h5>
                    <div
                        data-bs-dismiss="modal" aria-label="Close"
                        class="cursor-pointer icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center"
                        ref="close"
                    >
                        <i class="fa fa-times text-secondary text-lg opacity-10" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="position-relative">
                        <input
                            :style="v$.city.$error ? 'border-color: tomato;' : ''"
                            type="text"
                            v-model="city"
                            class="form-control m-0"
                            placeholder="Введите город"
                        >
                        <p v-if="v$.city.$error" class="invalid-msg">Обязательное поле</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        {{ editCity ? 'Изменить' : 'Добавить' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import useVuelidate from '@vuelidate/core'
import { required } from '@vuelidate/validators'

export default {
    name: "ModalCities",
    setup () {
        return { v$: useVuelidate() }
    },
    components: {
    },
    data() {
        return {
            city: ''
        }
    },
    methods: {
        async changeOrAddCity() {
            const isFormCorrect = await this.v$.$validate()
            // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
            if (!isFormCorrect) return
            this.$refs.close.click()
            this.$store.commit('loaderTrue')
            axios[this.editCity ? 'put' : 'post'](`/api/v1/admin/cities/${this.editCity ? this.stateCity.id : ''}`, {
                name: this.city
            })
                .then((response) => {
                    this.$store.commit('loaderFalse')
                    this.$store.dispatch('serviceCities/getCities')
                    this.$store.dispatch('getToast', { msg: 'Город добавлен!' })
                    this.v$.$reset()
                    console.log(response);
                })
                .catch((error) => {
                    this.$store.commit('loaderFalse')
                    this.v$.$reset()
                    this.$store.dispatch('getToast', { msg: 'Что-то пошло не так!', settings: {
                        type: 'error'
                    } })
                    console.log(error);
                })
                .then(() => {
                    this.city = '';
                })
        }
    },
    computed: {
        editCity() {
            if (this.$store.getters['serviceCities/stateEditCity']) {
                this.city = this.stateCity.name
            } else {
                this.city = ''
            }
            return this.$store.getters['serviceCities/stateEditCity']
        },
        stateCity() {
            return this.$store.getters['serviceCities/stateCity']
        }
    },
    validations () {
        return {
            city: { required }
        }
    }
}
</script>

<style scoped>
.dark-version .modal-content {
    background: #111C44;
}
.invalid-msg {
    position: absolute;
    left: 0;
    top: -18px;
    margin: 0;
    font-size: 12px;
    color: tomato;
}
</style>
