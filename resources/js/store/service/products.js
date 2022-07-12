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
        async getProducts({state, commit}) {
            commit('loaderTrue', null, { root: true })
            await axios.get('/api/v1/admin/products')
                .then((data) => {
                    console.log(data.data.data)
                    state.products = data.data.data
                })
                .catch((error) => {
                    console.log(error)
                })
                .then(() => {
                    commit('loaderFalse', null, { root: true })
                })
        }
    }
}
