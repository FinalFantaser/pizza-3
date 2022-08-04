export default {
    namespaced: true,
    state()  {
        return {
            cities: null
        }
    },
    getters: {
        stateCities(state) {
            return state.cities
        }
    },
    mutations: {
    },
    actions: {
        async getCities({state, commit}) {
            commit('loaderTrue', null, { root: true })
            await axios.get('/api/v1/admin/cities')
                .then(data => {
                    state.cities = data.data.data
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
