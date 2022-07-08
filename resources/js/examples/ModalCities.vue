<template>
    <!-- Modal -->
    <div class="modal fade" id="ModalCities" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form @submit="changeOrAddCity($event)" action="#" class="modal-content">
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
                    <input
                        type="text"
                        v-model="city"
                        class="form-control m-0"
                        placeholder="Введите город"
                    >
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

export default {
    name: "ModalCities",
    components: {
    },
    data() {
        return {
            city: ''
        }
    },
    methods: {
        changeOrAddCity(event) {
            event.preventDefault()
            this.$refs.close.click()
            this.$store.state.argon.loader = true
            axios[this.editCity ? 'put' : 'post'](`api/v1/admin/cities/${this.editCity ? this.stateCity.id : ''}`, {
                name: this.city
            })
                .then((response) => {
                    this.$store.state.argon.loader = false
                    this.$store.dispatch('getCities')
                    console.log(response);
                })
                .catch((error) => {
                    this.$store.state.argon.loader = false
                    console.log(error);
                })
                .then(() => {
                    this.city = '';
                })
        }
    },
    computed: {
        editCity() {
            if (this.$store.state.argon.editCity) {
                this.city = this.stateCity.name
            } else {
                this.city = ''
            }
            return this.$store.state.argon.editCity
        },
        stateCity() {
            return this.$store.state.argon.city
        }
    }
}
</script>

<style scoped>
.dark-version .modal-content {
    background: #111C44;
}
</style>
