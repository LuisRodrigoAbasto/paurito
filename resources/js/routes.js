
import Vue from 'vue';
import VueRouter from 'vue-router'
import pages_router from './pages/pages.routes.js'

// import app_root from "./layouts/app.vue";
import LoginComponent from "./login/login.vue";
import pages_component from './pages/pages.vue'
import error from "./shared/error.vue";


Vue.use(VueRouter)

const routes = [
  { path: "/login", component: LoginComponent},
  { path: "/", component:pages_component,
    children: pages_router },
  { path: "**", component: error }
];

const app_router = new VueRouter({
mode: 'hash',
routes,
  
});

export default app_router;