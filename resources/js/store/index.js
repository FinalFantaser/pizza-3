import { createStore } from "vuex";
import argon from "./modules/argon";
import auth from "./modules/auth";
import serviceCities from "./service/cities";
import serviceCategories from "./service/categories";
import serviceProducts from "./service/products";
import serviceOptions from "./service/options";
import servicePosters from "./service/posters";
import { useToast } from 'vue-toastification'

const toast = useToast()

export default createStore({
    modules: {
        serviceCities,
        serviceCategories,
        serviceProducts,
        serviceOptions,
        servicePosters,
        argon,
        auth
    },
    state() {
        return {
            loader: false
        }
    },
    getters: {
        stateLoader(state) {
            return state.loader
        }
    },
    mutations: {
        loaderTrue(state) {
            state.loader = true
        },
        loaderFalse(state) {
            state.loader = false
        }
    },
    actions: {
        getToast(context, {msg, settings}) {
            toast(msg, settings)
        }
    }
});
