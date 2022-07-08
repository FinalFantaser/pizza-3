export default {
    state: {
        editCity: false,
        city: {},
        cities: null
    },
    actions: {
        getCities({state}) {
            this.state.argon.loader = true
            axios.get('api/v1/admin/cities')
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
