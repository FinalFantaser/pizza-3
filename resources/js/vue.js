import { createApp } from "vue";
import App from "./App.vue";
import store from "./store";
import router from "./router";
import "./assets/css/nucleo-icons.css";
import "./assets/css/nucleo-svg.css";
import "./assets/scss/argon-dashboard.scss";
import ArgonDashboard from "./argon-dashboard";
import directives from './directives.js'
//toast
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

// font awesome
import fontawesome from "./assets/js/fontawesome";
import "./assets/css/fontawesome.css";

const appInstance = createApp(App);

directives(appInstance);

appInstance.use(store);
appInstance.use(router);
appInstance.use(ArgonDashboard);
appInstance.use(Toast, {
    position: 'bottom-right',
    timeout: 3000
});
appInstance.mount("#app");

fontawesome()
