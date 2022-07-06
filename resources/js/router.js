import { createRouter, createWebHistory } from "vue-router";

import Get from './components/Get.vue'
import Login from './components/Login.vue'
import Registration from './components/Registration.vue'



const routes = [

    {
        path: '/get',
        name: 'get.index',
        component: Get
    },
    {
        path: '/user/login',
        name: 'user.login',
        component: Login
    },
    {
        path: '/user/registration',
        name: 'user.registration',
        component: Registration
    }

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
