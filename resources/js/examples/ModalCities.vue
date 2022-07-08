<template>
    <!-- Modal -->
    <div class="modal fade" id="ModalCities" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form @submit="changeOrAddCity($event)" action="#" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        {{ editCity ? 'Измените город' : 'Добавьте город' }}
                    </h5>
                    <div data-bs-dismiss="modal" aria-label="Close" class="cursor-pointer icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="fa fa-times text-secondary text-lg opacity-10" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <input
                        type="text"
                        v-model="city"
                        class="m-0"
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
import ArgonInput from ".././components/ArgonInput.vue";

export default {
    name: "ModalCities",
    components: {
        ArgonInput
    },
    data() {
        return {
            city: ''
        }
    },
    methods: {
        changeOrAddCity(event) {
            const city = this.city
            event.preventDefault()
            axios.post(`'api/v1/admin/cities/${this.stateCity.slug}'`, {
                name: city
            })
                .then(function (response) {
                console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    },
    computed: {
        editCity() {
            if (this.$store.state.editCity) {
                this.city = this.stateCity.name
            } else {
                this.city = ''
            }
            return this.$store.state.editCity
        },
        stateCity() {
            return this.$store.state.city
        }
    }
}
</script>

<style scoped>
</style>
