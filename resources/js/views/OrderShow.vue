<template>
    <div v-if="order" class="py-sm-4 p-2 p-sm-4 container-fluid">
        <div class="col-lg-6 m-auto text-center">
            <h4 class="text-white">Просмотр заказа</h4>
            <p class="text-white">Ниже представлена инфориация о заказе.</p>
        </div>
        <div>
            <div class="mx-auto col-12 col-lg-9 col-xl-8 mb-3">
                <div class="card p-2 p-sm-4">
                    <table class="table caption-top">
                        <caption>
                            <div class="d-flex align-items-center text-primary">
                                <div class="px-2">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                </div>
                                <span>Данные клиента</span>
                            </div>
                        </caption>
                        <tbody>
                        <tr>
                            <th scope="row">Имя</th>
                            <th scope="row" class="text-end">{{ order.customer_data.name }}</th>
                        </tr>
                        <tr>
                            <th scope="row">Телефон</th>
                            <th scope="row" class="text-end"><a :href="'tel:+' + order.customer_data.phone">{{ phoneFormat(order.customer_data.phone) }}</a></th>
                        </tr>
                        <tr>
                            <th scope="row">Почта</th>
                            <th scope="row" class="text-end"><a :href="'mailto:' + order.customer_data.email">{{ order.customer_data.email }}</a></th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mx-auto col-12 col-lg-9 col-xl-8 mb-3">
                <div class="card p-2 p-sm-4">
                    <table class="table caption-top">
                        <caption>
                            <div class="d-flex align-items-center text-success">
                                <div class="px-2">
                                    <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                                </div>
                                <span>Информация заказа</span>
                            </div>
                        </caption>
                        <tbody>
                        <tr>
                            <th scope="row">Город</th>
                            <th scope="row" class="text-end">{{ order.customer_data.city.name }}</th>
                        </tr>
                        <tr>
                            <th scope="row">Номер заказа</th>
                            <th scope="row" class="text-end">#{{ order.id }}</th>
                        </tr>
                        <tr>
                            <th scope="row">Статус заказа</th>
                            <th scope="row" class="text-end">{{ order.current_status }}</th>
                        </tr>
                        <tr>
                            <th scope="row">Дата</th>
                            <th scope="row" class="text-end">{{ dateFormat(order.created_at) }}</th>
                        </tr>
                        <tr>
                            <th scope="row">Вид оплаты</th>
                            <th scope="row" class="text-end">вид оплаты</th>
                        </tr>
                        <tr>
                            <th scope="row">Вид доставки</th>
                            <th scope="row" class="text-end">{{ order.delivery_method_name }}</th>
                        </tr>
                        <tr>
                            <th scope="row">Адрес {{ order.pickup_point ? 'самовывоза' : 'доставки' }}</th>
                            <th scope="row" class="text-end">{{ order.pickup_point ? order.pickup_point_address : order.customer_data.address }}</th>
                        </tr>
                        <tr>
                            <th scope="row">Время {{ order.pickup_point ? 'самовывоза' : 'доставки' }}</th>
                            <th scope="row" class="text-end">{{ order.time }}</th>
                        </tr>
                        <tr>
                            <th scope="row">Комментарий:</th>
                            <th scope="row" class="text-end"><span>{{ order.note }}</span></th>
                        </tr>
                        <tr>
                            <th scope="row">Сумма заказа:</th>
                            <th scope="row" class="text-end text-danger">{{ order.cost }} ₽</th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mx-auto col-12 col-lg-9 col-xl-8 mb-3">
                <div class="card p-2 p-sm-4">
                    <table class="table caption-top">
                        <caption>
                            <div class="d-flex align-items-center text-warning">
                                <div class="px-2">
                                    <i class="fa fa-pizza-slice"></i>
                                </div>
                                <span>Состав заказа</span>
                            </div>
                        </caption>
                        <tbody>
                        <tr v-for="product in order.items">
                            <th scope="row">
                                <p class="m-0">{{ product.product_name }} - {{ product.product_quantity }}шт.</p>
                                <p v-for="option in product.product_options" class="m-0 text-sm"><span v-for="select in option.selected">({{ select }}) </span></p>
                            </th>
                            <th scope="row" class="text-end text-danger">{{ product.total_price }} ₽</th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <h4 v-else class="text-white text-center ь-4">Адрес не найден</h4>
</template>

<script>
import phoneFormat from "../assets/js/phoneFormat";
import dateFormat from "../assets/js/dateFormat.js";

export default {
    name: "OrderShow",
    data() {
        return {
            order: null
        }
    },
    methods: {
        phoneFormat(phone) {
            return phoneFormat(phone)
        },
        dateFormat(date) {
            return dateFormat(date)
        }
    },
    async created() {
        await axios.get(`/api/v1/admin/orders/${this.$route.params.id}`)
            .then(response => {
                console.log(response.data.data)
                this.order = response.data.data
            })
            .catch(error => {
                console.log(error)
            })
    }
}
</script>

<style scoped>
.text-end {
    word-break: break-all;
    font-weight: 400;
    white-space: normal;
}
/*span {*/
/*    display: block;*/
/*    width: 100%;*/

/*}*/
.card {
    overflow: auto;
}
.table p {
    font-weight: 600;
}
@media screen and (max-width: 575px) {
    .table {
        font-size: 14px;
    }
    .table p {
        font-size: 14px;
    }
}
</style>
