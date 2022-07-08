import axios from "axios";
import router from "../../router";

const token = "x_xsrf_token"

export default {
    state: {
        isAuthenticated: localStorage.getItem("x_xsrf_token") !== null
    },

    getters: {
        isAuthenticated(state) {
            return state.isAuthenticated;
        }
    },

    mutations: {
        isAuthenticated(state, payload) {
            state.isAuthenticated = payload.isAuthenticated;
        }
    },

    actions: {
        login(context, payload) {
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/login', {email: payload.user.email, password: payload.user.password})
                    .then( res => {
                        localStorage.setItem( token , res.config.headers['X-XSRF-TOKEN'])
                            context.commit("isAuthenticated", {
                                isAuthenticated: true
                            });
                        router.push({name: "Dashboard"})
                    })
                    .catch( err => {
                        if (err.response) console.log(err.response)
                    })
                    .catch( err => {
                        console.log(err.response)
                    })
            });
        },

        // register(context, payload) {
        //     return vueAuth.register(payload.user, payload.requestOptions).then(response => {
        //         context.commit("isAuthenticated", {
        //             isAuthenticated: vueAuth.isAuthenticated()
        //         });
        //         router.push({name: "Home"});
        //     });
        // },

        logout(context, payload) {
            axios.post('/logout')
                .then( r => {
                    context.commit("isAuthenticated", {
                        isAuthenticated: false
                    });
                    localStorage.removeItem(token)
                    router.push({name: 'Signin'})
                })
        }
    }
};
