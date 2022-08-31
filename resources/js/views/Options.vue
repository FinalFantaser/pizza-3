<template>
    <div class="py-4 container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Все опции</h5>
                                <p class="text-sm mb-0">
                                    A lightweight, extendable, dependency-free javascript HTML table plugin.
                                </p>
                            </div>
                            <div class="ms-auto my-auto mt-lg-0 mt-4">
                                <div class="ms-auto my-auto">
                                    <router-link to="/options/create" class="btn bg-gradient-primary btn-sm mb-0">Добавить опцию</router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-0">
                        <div class="table-responsive">
                            <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
                                <div class="dataTable-top">
                                    <div class="dataTable-dropdown">
                                        <label>
                                            <select class="dataTable-selector">
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="15">15</option>
                                                <option value="20">20</option>
                                                <option value="25">25</option>
                                            </select>
                                            entries per page
                                        </label>
                                    </div>
                                    <div class="dataTable-search">
                                        <input class="dataTable-input" placeholder="Search..." type="text">
                                    </div>
                                </div>
                                <div class="dataTable-container">
                                    <table class="table table-flush dataTable-table" id="products-list">
                                        <thead class="thead-light">
                                        <tr>
                                            <th data-sortable="" style="width: 31.7521%;"><a href="#" class="dataTable-sorter">Product</a></th>
                                            <th data-sortable="" style="width: 12.3972%;" class=""><a href="#" class="dataTable-sorter">Category</a></th>
                                            <th data-sortable="" style="width: 9.55092%;" class=""><a href="#" class="dataTable-sorter">Price</a></th>
                                            <th data-sortable="" style="width: 12.5237%;" class=""><a href="#" class="dataTable-sorter">SKU</a></th>
                                            <th data-sortable="" style="width: 10.3099%;" class=""><a href="#" class="dataTable-sorter">Quantity</a></th>
                                            <th data-sortable="" style="width: 14.5478%;" class=""><a href="#" class="dataTable-sorter">Status</a></th>
                                            <th data-sortable="" style="width: 8.91841%;" class="desc"><a href="#" class="dataTable-sorter">Action</a></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(option, index) in stateOptions">
                                            <td>
                                                <div class="d-flex">
                                                    <div class="form-check my-auto">
                                                        <input class="form-check-input" type="checkbox" id="customCheck5">
                                                    </div>
<!--                                                    <img-->
<!--                                                        v-if="category.thumbUrl"-->
<!--                                                        class="w-10 ms-3"-->
<!--                                                        :src="category.thumbUrl"-->
<!--                                                        alt="category"-->
<!--                                                    >-->
<!--                                                    <img-->
<!--                                                        v-else-->
<!--                                                        class="w-10 ms-3"-->
<!--                                                        src="@/assets/img/CategoryDefault.png"-->
<!--                                                        alt="category"-->
<!--                                                    >-->
                                                    <h6 class="ms-3 my-auto">{{ option.name }}</h6>
                                                </div>
                                            </td>
                                            <td class="text-sm">Clothing</td>
                                            <td class="text-sm">{{ 132 }}</td>
                                            <td class="text-sm">634729</td>
                                            <td class="text-sm">725</td>
                                            <td>
                                                <span class="badge badge-success badge-sm">in Stock</span>
                                            </td>
                                            <td class="text-sm">
                                                <a href="javascript:;" data-bs-toggle="tooltip" data-bs-original-title="Preview product">
                                                    <i class="fas fa-eye text-secondary" aria-hidden="true"></i>
                                                </a>
                                                <router-link :to="'/options/' + option.id + '/edit'" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit product">
                                                    <i class="fas fa-user-edit text-secondary" aria-hidden="true"></i>
                                                </router-link>
                                                <a
                                                    href="javascript:;"
                                                    @click="deleteOption(option.id)"
                                                >
                                                    <i class="fas fa-trash text-secondary" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="dataTable-bottom">
                                    <div class="dataTable-info">Showing 1 to 7 of 15 entries</div>
                                    <nav class="dataTable-pagination">
                                        <ul class="dataTable-pagination-list">
                                            <li class="pager"><a href="#" data-page="1">‹</a></li>
                                            <li class="active"><a href="#" data-page="1">1</a></li>
                                            <li class=""><a href="#" data-page="2">2</a></li>
                                            <li class=""><a href="#" data-page="3">3</a></li>
                                            <li class="pager"><a href="#" data-page="2">›</a></li>
                                        </ul>
                                    </nav>
                                </div>
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
    name: "Options",
    computed: {
        stateOptions() {
            return this.$store.getters['serviceOptions/stateOptions']
        }
    },
    methods: {
        deleteOption(id) {
            this.$store.commit('loaderTrue')
            axios.delete(`/api/v1/admin/options/${id}`)
                .then( (response) => {
                    this.$store.commit('loaderFalse')
                    this.$store.dispatch('serviceOptions/getOptions')
                })
                .catch(function (error) {
                    this.$store.commit('loaderFalse')
                    console.log(error);
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
