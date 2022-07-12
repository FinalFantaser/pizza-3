export default {
    namespaced: true,
    state() {
        return {
            posters: null
        }
    },
    getters: {
        statePosters(state) {
            return state.posters
        }
    },
    actions: {
        async getPosters({state, commit}) {
            commit('loaderTrue', null, { root: true })
            await axios.get('/api/v1/admin/posters')
                .then(data => {
                    state.posters = data.data.data
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
