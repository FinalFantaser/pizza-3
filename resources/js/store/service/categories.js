export default {
    namespaced: true,
    state() {
        return {
            categories: null
        }
    },
    getters: {
        stateCategories(state) {
            return state.categories
        }
    },
    actions: {
        async getCategories({state, commit}) {
            commit('loaderTrue', null, { root: true })
            await axios.get('/api/v1/admin/categories')
                .then(data => {
                    state.categories = data.data.data
                    console.log(data.data.data)
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
