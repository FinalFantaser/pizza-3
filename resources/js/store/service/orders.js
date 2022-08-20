import dateFormat from "../.././assets/js/dateFormat.js";

export default {
    namespaced: true,
    state() {
        return {
            orders: null
        }
    },
    getters: {
        stateOrders(state) {
            return state.orders
        }
    },
    actions: {
        async getOrders({state, commit}) {
            commit('loaderTrue', null, { root: true })
            await axios.get('/api/v1/admin/orders')
                .then(data => {
                    if(data.data.data.length > 0) {
                        data.data.data.forEach(item => {
                            item.created_at_format = dateFormat(item.created_at)
                            item.updated_at_format = dateFormat(item.updated_at)
                        })
                    }
                    state.orders = data.data.data
                    console.log("Orders:",data.data.data)
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
