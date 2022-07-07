import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "../views/Dashboard.vue";
import Users from "../views/Users.vue";
import UserInfo from "../views/UserInfo.vue";
import Products from "../views/Products.vue";
import ProductInfo from "../views/ProductInfo.vue";
import Orders from "../views/Orders.vue";
import Cities from "../views/Cities.vue";
import Profile from "../views/Profile.vue";
import Signup from "../views/Signup.vue";
import Signin from "../views/Signin.vue";

const routes = [
  {
    path: "/",
    name: "Mafia Pizza",
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
    path: "/cities",
    name: "Cities",
    component: Cities,
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

];

const router = createRouter({
  history: createWebHistory(),
  routes,
  linkActiveClass: "active",
});

export default router;
