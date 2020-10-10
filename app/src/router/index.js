import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    name: "Inicio",
    component: () => import("../views/Home.vue"),
  },
  {
    path: "/empresa",
    name: "Empresa",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import("../views/Empresa.vue"),
  },
  {
    path: '/cuenta_bancaria',
    name: 'Cuenta Bancaria',
    component: () => import("../views/CuentaBancaria.vue"),
  }
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes,
});

export default router;
