export default {
    namespaced: true,
    state() {
        return {
            options: null
        }
    },
    getters: {
        stateOptions(state) {
            return state.options
        }
    },
    actions: {
        async getOptions({state, commit}) {
            commit('loaderTrue', null, { root: true })
            await axios.get('/api/v1/admin/options')
                .then((data) => {
                    console.log(data.data.data)
                    state.options = data.data.data
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
