<template>

    <div class="py-4 container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Все заказы</h5>
                                <p class="text-sm mb-0">
                                    Ниже представлен список всех имеющихся заказов.
                                </p>
                            </div>
                            <div class="ms-auto my-auto mt-lg-0 mt-4">
                                <div class="ms-auto my-auto">
                                    <router-link to="#" class="btn bg-gradient-primary btn-sm mb-0">Создать заказ</router-link>
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
                                        <input class="dataTable-input" placeholder="Поиск..." type="text">
                                    </div>
                                </div>
                                <div class="dataTable-container">
                                    <table class="table table-flush dataTable-table" id="products-list">
                                        <thead class="thead-light">
                                        <tr>
                                            <th class="p-2"></th>
                                            <th><a href="#" class="dataTable-sorter">№ заказа</a></th>
                                            <th><a href="#" class="dataTable-sorter">Клиент</a></th>
                                            <th><a href="#" class="dataTable-sorter">Сумма</a></th>
                                            <th><a href="#" class="dataTable-sorter">Дата добавления</a></th>
                                            <th><a href="#" class="dataTable-sorter">Дата изменения</a></th>
                                            <th><a href="#" class="dataTable-sorter">Статус</a></th>
                                            <th><a href="#" class="dataTable-sorter">Действия</a></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(order, index) in stateOrders">
                                            <td class="p-2">
                                                <div class="form-check my-auto">
                                                    <input class="form-check-input" type="checkbox" id="customCheck5">
                                                </div>
                                            </td>
                                            <td>

                                                    <span class="text-sm">{{ order.id }}</span>
                                            </td>
                                            <td class="text-sm">Имя клиента</td>
                                            <td class="text-sm">{{ order.cost }} ₽</td>
                                            <td class="text-sm">{{ order.created_at_format }}</td>
                                            <td class="text-sm">{{ order.updated_at_format }}</td>
                                            <td>
                                                <span class="badge badge-success badge-sm">in Stock</span>
                                            </td>
                                            <td class="text-sm">
                                                <router-link :to="'/orders/' + order.id + '/show'" class="p-1">
                                                    <i class="fas fa-eye text-secondary" aria-hidden="true"></i>
                                                </router-link>
                                                <a
                                                    class="p-1"
                                                    href="javascript:;"
                                                    @click="deleteOrder(order.id)"
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
  name: "Orders",
    data() {
      return {
      }
    },
    computed: {
      stateOrders() {
          return this.$store.getters['serviceOrders/stateOrders']
      }
    },
    methods: {
      deleteOrder(id) {
          this.$store.commit('loaderTrue')
          axios.delete(`api/v1/admin/orders/${id}`)
              .then((data) => {
                  console.log(data)
                  this.$store.dispatch('serviceOrders/getOrders')
              })
              .catch((error) => {
                  console.log(error)
              })
              .then(() => {
                  this.$store.commit('loaderFalse')
              })
      },
      async getOrders() {
          await this.$store.dispatch('serviceOrders/getOrders')
      }
    },
    async created() {
      await this.getOrders()
    }
};
</script>
