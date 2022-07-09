export default {
    data: {
        categories: null
    },
    actions: {
        async getCategories({state}) {
            this.state.argon.loader = true
            await axios.get('/api/v1/admin/categories')
                .then(data => {
                    state.categories = data.data.data
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
