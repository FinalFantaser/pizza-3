export default {
    namespaced: true,
    state() {
        return {
            products: null
        }
    },
    getters: {
        stateProducts(state) {
            return state.products
        }
    },
    actions: {
        async getProducts({state}) {
            this.state.argon.loader = true
            await axios.get('/api/v1/admin/products')
                .then((data) => {
                    console.log(data.data.data)
                    state.products = data.data.data
                })
                .catch((error) => {
                    console.log(error)
                })
                .then(() => {
                    this.state.argon.loader = false
                })
        }
    }
}
