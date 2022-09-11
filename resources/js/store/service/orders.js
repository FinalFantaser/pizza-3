import dateFormat from "../.././assets/js/dateFormat.js";

export default {
    namespaced: true,
    state() {
        return {
            meta: null,
            orders: null
        }
    },
    getters: {
        stateOrders(state) {
            return state.orders
        },
        stateMeta(state) {
            return state.meta
        }
    },
    actions: {
        async getOrders({state, commit}, page) {
            commit('loaderTrue', null, { root: true })
            await axios.get(`/api/v1/admin/orders${ page ? '?page='+page : '' }`)
                .then(data => {
                    if(data.data.data.length > 0) {
                        data.data.data.forEach(item => {
                            item.created_at_format = dateFormat(item.created_at)
                            item.updated_at_format = dateFormat(item.updated_at)
                            item.deleteFlag = false
                        })
                    }
                    state.orders = data.data.data
                    state.meta = data.data.meta
                    console.log("Orders:", data.data)
                })
                .catch(error => {
                    console.log(error)
                })
                .then(() => {
                    commit('loaderFalse', null, { root: true })
                })
        }
    }

}
