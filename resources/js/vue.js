import { createApp } from 'vue/dist/vue.esm-bundler';
import router from "./router.js";

//Импорт компонентов
import App from './components/App.vue'

const app = createApp({
    //router
});

app.component('App', App);
app.use(router);
app.mount('#app');
