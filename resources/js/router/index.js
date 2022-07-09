import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "../views/Dashboard.vue";
import Users from "../views/Users.vue";
import UserInfo from "../views/UserInfo.vue";
import Cities from "../views/Cities.vue";
import Categories from "../views/Categories.vue";
import CategoryInfo from "../views/CategoryInfo.vue";
import Products from "../views/Products.vue";
import ProductInfo from "../views/ProductInfo.vue";
import Orders from "../views/Orders.vue";
import Profile from "../views/Profile.vue";
import Signup from "../views/Signup.vue";
import Signin from "../views/Signin.vue";
import Logout from "../views/Logout.vue";

const routes = [
  {
    path: "/",
    name: "Dashboard",
    component: Dashboard,
  },
  {
    path: "/users",
    name: "Users",
    component: Users,
  },
  {
    path: "/users/create",
    name: "users.create",
    component: UserInfo,
  },
  {
    path: "/users/:id/edit",
    name: "users.edit",
    component: UserInfo
  },
    {
        path: "/cities",
        name: "Cities",
        component: Cities,
    },
    {
        path: "/categories",
        name: "Categories",
        component: Categories,
    },
    {
        path: "/categories/create",
        name: "categories.create",
        component: CategoryInfo,
    },
    {
        path: "/categories/:id/edit",
        name: "categories.edit",
        component: CategoryInfo,
    },
  {
    path: "/products",
    name: "Products",
    component: Products,
  },
  {
    path: "/products/create",
    name: "products.create",
    component: ProductInfo,
  },
  {
    path: "/products/:id/edit",
    name: "products.edit",
    component: ProductInfo,
  },
  {
    path: "/orders",
    name: "Orders",
    component: Orders,
  },
  {
    path: "/profile",
    name: "Profile",
    component: Profile,
  },
  {
    path: "/signin",
    name: "Signin",
    component: Signin,
  },
  {
    path: "/signup",
    name: "Signup",
    component: Signup,
  },
  {
    path: "/logout",
    name: "Logout",
    component: Logout,
  },

];

const router = createRouter({
  history: createWebHistory(),
  routes,
  linkActiveClass: "active",
});

router.beforeEach((to,from,next) => {
    const token = localStorage.getItem('x_xsrf_token')
    if(!token){
        if (to.name === 'Signin') return next()

        return next({ name: 'Signin'})
    }

    if (to.name === 'Signin') return next({name: 'Dashboard'})

    next()
})

export default router;
