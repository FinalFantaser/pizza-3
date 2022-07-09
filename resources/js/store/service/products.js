export default {
    state: {
        products: null
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
