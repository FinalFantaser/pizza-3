<template>
    <div v-if="order" class="py-sm-4 p-2 p-sm-4 container-fluid">
        <div class="row">
            <div class="col-lg-6 m-auto text-center">
                <h4 class="text-white">Просмотр заказа</h4>
                <p class="text-white">Ниже представлена инфориация о заказе.</p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-5">
                <div class="card p-2 p-sm-4">
                    <table class="table caption-top">
                        <caption>
                            <div class="d-flex align-items-center text-success">
                                <div class="px-2">
                                    <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                                </div>
                                <span>Заказ</span>
                            </div>
                        </caption>
                        <tbody>
                        <tr>
                            <th scope="row">Город</th>
                        </tr>
                        <tr>
                            <th scope="row">Дата</th>
                        </tr>
                        <tr>
                            <th scope="row">Вид оплаты</th>
                        </tr>
                        <tr>
                            <th scope="row">Вид доставки</th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-5">
                <div class="card p-2 p-sm-4">
                    <table class="table caption-top">
                        <caption>
                            <div class="d-flex align-items-center text-primary">
                                <div class="px-2">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                </div>
                                <span>Клиент</span>
                            </div>
                        </caption>
                        <tbody>
                        <tr>
                            <th scope="row">Имя</th>
                        </tr>
                        <tr>
                            <th scope="row">Телефон</th>
                        </tr>
                        <tr>
                            <th scope="row">Почта</th>
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
export default {
    name: "OrderShow",
    data() {
        return {
            order: null
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

</style>
