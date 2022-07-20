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
                            var date = new Date(item.created_at);
                            var options = {
                                // era: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                                // weekday: 'long',
                                timezone: 'UTC',
                                hour: 'numeric',
                                minute: 'numeric',
                                second: 'numeric'
                            };
                            item.created_at_format = date.toLocaleString("ru", options)
                        })
                    }
                    state.orders = data.data.data
                    console.log(data.data.data)
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
