import DashboardComponent from './dashboard.vue'
import CompraComponent from './compra.vue'
import VentaComponent from './venta.vue'
import IngresoComponent from './ingreso.vue'
import EgresoComponent from './egreso.vue'
import FormulaComponent from './formula.vue'
import ProductoComponent from './producto.vue'
import CuentaComponent from './cuenta.vue'


// Vue.use(VueRouter)
const pages_routes = [
  { path: "dashboard", component: DashboardComponent, name: 'Dashboard' },
  { path: 'producto', component: ProductoComponent, name: 'Producto' },
  { path: 'formula', component: FormulaComponent, name: 'Formula' },
  { path: 'venta', component: VentaComponent, name: 'Venta' },
  { path: 'compra', component: CompraComponent, name: 'Compra' },
  { path: 'ingreso', component: IngresoComponent, name: 'Ingreso' },
  { path: 'egreso', component: EgresoComponent, name: 'Egreso' },
  { path: 'cuenta', component: CuentaComponent, name: 'Cuenta' },

  { path: "", redirect: { name: 'Dashboard' } },
  // { path: '**', component: error }
];

// const pages_routes = new VueRouter({
//   routes,
// //   mode: "history"
// });

// export default {
//     components:{
//         'app-sidebar':sidebar_component,
//         'app-header':header_component,
//         'app-breadcrumb':breadcrumb_component
//     }

// }
export default pages_routes;