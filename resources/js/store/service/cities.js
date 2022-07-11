export default {
    namespaced: true,
    state()  {
        return {
            editCity: false,
            city: {},
            cities: null
        }
    },
    getters: {
        stateEditCity(state) {
            return state.editCity
        },
        stateCity(state) {
            return state.city
        },
        stateCities(state) {
            return state.cities
        }
    },
    mutations: {
        editCityTrue(state) {
            state.editCity = true
        },
        editCityFalse(state) {
            state.editCity = false
        },
        addCity(state, payload) {
            state.city = payload
        }
    },
    actions: {
        async getCities({state}) {
            this.state.argon.loader = true
            await axios.get('/api/v1/admin/cities')
                .then(data => {
                    state.cities = data.data.data
                    console.log(data.data.data)
                })
                .catch( (error) => {
                    console.log(error);
                })
                .then(() => {
                    this.state.argon.loader = false
                })
        }
    }
}
