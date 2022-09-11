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
                        </div>
                    </div>
                    <div class="card-body px-0 pb-0">
                        <div class="table-responsive">
                            <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
<!--                                <div class="dataTable-top">-->
<!--                                    <div class="dataTable-dropdown">-->
<!--                                        <label>-->
<!--                                            <select class="dataTable-selector">-->
<!--                                                <option value="5">5</option>-->
<!--                                                <option value="10">10</option>-->
<!--                                                <option value="15">15</option>-->
<!--                                                <option value="20">20</option>-->
<!--                                                <option value="25">25</option>-->
<!--                                            </select>-->
<!--                                            entries per page-->
<!--                                        </label>-->
<!--                                    </div>-->
<!--                                    <div class="dataTable-search">-->
<!--                                        <input class="dataTable-input" placeholder="Поиск..." type="text">-->
<!--                                    </div>-->
<!--                                </div>-->
                                <div class="dataTable-container">
                                    <table class="table table-flush dataTable-table" id="products-list">
                                        <thead class="thead-light">
                                        <tr>
<!--                                            <th class="p-2"></th>-->
                                            <th><a href="#" class="dataTable-sorter">№ заказа</a></th>
                                            <th><a href="#" class="dataTable-sorter">Статус</a></th>
                                            <th><a href="#" class="dataTable-sorter">Имя клиента</a></th>
                                            <th><a href="#" class="dataTable-sorter">Метод оплаты / доставка</a></th>
                                            <th><a href="#" class="dataTable-sorter">Сумма</a></th>
                                            <th><a href="#" class="dataTable-sorter">Дата добавления</a></th>
                                            <th><a href="#" class="dataTable-sorter">Дата изменения</a></th>
                                            <th><a href="#" class="dataTable-sorter">Действия</a></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr
                                            v-for="(order, index) in stateOrders"
                                            :key="index"
                                            :class="{'test' : !order.viewed}"
                                        >
<!--                                            <td class="p-2">-->
<!--                                                <div class="form-check my-auto">-->
<!--                                                    <input class="form-check-input" type="checkbox" id="customCheck5">-->
<!--                                                </div>-->
<!--                                            </td>-->
                                            <td>
                                                <router-link :to="'/orders/' + order.id + '/show'" class="px-2 no-hover">
                                                    <span class="text-sm">{{ order.id }}</span>
                                                </router-link>

                                            </td>
                                            <td>
                                                <router-link :to="'/orders/' + order.id + '/show'" class="px-2 no-hover">
                                                    <span class="badge badge-success badge-sm">{{ order.current_status }}</span>
                                                </router-link>
                                            </td>
                                            <td class="text-sm">
                                                <router-link :to="'/orders/' + order.id + '/show'" class="px-2 no-hover">
                                                    {{ order.customer_data.name }}
                                                </router-link>
                                            </td>
                                            <td class="text-sm">
                                                <router-link :to="'/orders/' + order.id + '/show'" class="px-2 no-hover">
                                                    {{ order.payment_method_name }}
                                                </router-link>
                                            </td>
                                            <td class="text-sm">
                                                <router-link :to="'/orders/' + order.id + '/show'" class="px-2 no-hover">
                                                    {{ order.cost }} ₽
                                                </router-link>
                                            </td>
                                            <td class="text-sm">
                                                <router-link :to="'/orders/' + order.id + '/show'" class="px-2 no-hover">
                                                    {{ order.created_at_format }}
                                                </router-link>
                                            </td>
                                            <td class="text-sm">
                                                <router-link :to="'/orders/' + order.id + '/show'" class="px-2 no-hover">
                                                    {{ order.updated_at_format }}
                                                </router-link>
                                            </td>
                                            <td class="text-sm">
                                                <div class="d-flex">
                                                    <router-link :to="'/orders/' + order.id + '/show'" class="px-2">
                                                        <i class="fas fa-eye text-secondary" aria-hidden="true"></i>
                                                    </router-link>

                                                    <div class="position-relative d-flex align-items-center">
                                                        <a
                                                            @click.prevent="dropdownOn(index)"
                                                            class="px-2"
                                                            href="#"
                                                        >
                                                            <i class="fas fa-trash text-secondary" aria-hidden="true"></i>
                                                        </a>

                                                        <transition name="dropdown">
                                                            <div v-if="order.deleteFlag" class="dropdown--custom card p-3">
                                                                <p class="text-center text-danger">Удалить заказ?</p>
                                                                <div class="d-flex">
                                                                    <button @click="deleteOrder(order.id)" class="btn m-0 me-2 btn-danger">Да</button>
                                                                    <button @click="order.deleteFlag = false" class="btn m-0 btn-primary">Нет</button>
                                                                </div>
                                                            </div>
                                                        </transition>

                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div v-if="stateMeta" class="dataTable-bottom">
                                    <div class="dataTable-info">Показано с {{stateMeta.from}} по {{stateMeta.to}} из {{stateMeta.total}} заказов</div>
                                    <nav class="dataTable-pagination">
                                        <ul class="dataTable-pagination-list">
                                            <li
                                                v-for="(link, linkIndex) in stateMeta.links"
                                                :class="{ 'active' : link.active }"
                                                class="pager"
                                            >
                                                <a
                                                    @click.prevent="getOrdersPag(linkIndex)"
                                                    href="#"
                                                >{{ linkIndex === 0 ? '«' : linkIndex === stateMeta.links.length - 1 ? '»' : link.label }}</a>
                                            </li>
<!--                                            <li class="active"><a href="#" data-page="1">1</a></li>-->
<!--                                            <li class=""><a href="#" data-page="2">2</a></li>-->
<!--                                            <li class=""><a href="#" data-page="3">3</a></li>-->
<!--                                            <li class="pager"><a href="#" data-page="2">›</a></li>-->
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
        stateMeta() {
          return this.$store.getters['serviceOrders/stateMeta']
        },
        stateOrders() {
          return this.$store.getters['serviceOrders/stateOrders']
        }
    },
    methods: {
        getOrdersPag(index) {
            if (index === 0 && this.stateMeta.current_page === 1) {
                return
            }
            if (index === 0) {
                this.getOrders(this.stateMeta.current_page - 1)
                return
            }
            if (index === this.stateMeta.links.length - 1 && this.stateMeta.current_page === this.stateMeta.links.length - 2) {
                return
            }
            if (index === this.stateMeta.links.length - 1) {
                this.getOrders(this.stateMeta.current_page + 1)
                return
            }
            this.getOrders(index)
        },
        dropdownOn(index) {
          this.stateOrders[index].deleteFlag = true
          document.addEventListener('mousedown', () => {
              this.stateOrders.forEach((el, index2) => {
                  el.deleteFlag = false
              })
          }, {once: true})

        },
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
        async getOrders(page) {
          await this.$store.dispatch('serviceOrders/getOrders', page)
        }
    },
    async created() {
      await this.getOrders()
        console.log(this.$store.getters['serviceOrders/stateMeta'])
    }
};
</script>

<style scoped>
.dropdown-enter-active,
.dropdown-leave-active {
    transition: opacity 0.5s ease;
}

.dropdown-enter-from,
.dropdown-leave-to {
    opacity: 0;
}
.test * {
    font-weight: bold;
}

.dropdown--custom {
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    z-index: 2;
    box-shadow: 0 0 5px rgba(0,0,0, 0.3);
}
.no-hover:hover {
    color: #344767;
}
</style>
