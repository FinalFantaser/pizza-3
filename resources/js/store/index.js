import { createStore } from "vuex";
import citiesModule from "./cities/index.js";
import argon from "./modules/argon";
import auth from "./modules/auth";

export default createStore({
    modules: {
        argon,
        citiesModule,
        auth
    }
});
