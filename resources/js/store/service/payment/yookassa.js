export default {
    namespaced: true,
    state() {
        return {
            shops: null
        }
    },
    getters: {
        stateShops(state) {
            return state.shops
        }
    },
    actions: {
        async getShops({state, commit}) {
            commit('loaderTrue', null, { root: true })
            await axios.get('/api/v1/admin/payment/yookassa_shop')
                .then(response => {
                    state.shops = response.data.data
                    console.log(response.data.data)
                })
                .catch( (error) => {
                    console.log(error);
                })
                .then(() => {
                    commit('loaderFalse', null, { root: true })
                })
        }
    }
}
