export default {
    namespaced: true,
    state() {
        return {
            products: null,
            popularProducts: null,
            recommendedProducts: null
        }
    },
    getters: {
        stateProducts(state) {
            return state.products
        },
        statePopularProducts(state) {
            return state.popularProducts
        },
        stateRecommendedProducts(state) {
            return state.recommendedProducts
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
        },
        async getPopularProducts({state, commit}) {
            commit('loaderTrue', null, { root: true })
            await axios.get('/api/v1/admin/products.popular')
                .then((data) => {
                    console.log(data.data.data)
                    state.popularProducts = data.data.data
                })
                .catch((error) => {
                    console.log(error)
                })
                .then(() => {
                    commit('loaderFalse', null, { root: true })
                })
        },
        async getRecommendedProducts({state, commit}) {
            commit('loaderTrue', null, { root: true })
            await axios.get('/api/v1/admin/products.recommended.index')
                .then((data) => {
                    console.log(data.data.data)
                    state.recommendedProducts = data.data.data
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
