import DashboardComponent from './dashboard.vue'
import CompraComponent from './compra.vue'
import VentaComponent from './venta.vue'
import IngresoComponent from './ingreso.vue'
import EgresoComponent from './egreso.vue'
import FormulaComponent from './formula.vue'
import ProductoComponent from './producto.vue'
import CuentaComponent from './cuenta.vue'

import axios from "axios";
// Vue.use(VueRouter)listarMenu(){
  let pages =
 [
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
let page_routes=pages;
let data=[];
var url = "api/menu";
axios.get(url)
        .then(resp=> {
    data=resp.data.data;     
      
  })
  .catch(error => {
    console.log(error)
  })


let c=0;
for(let item in pages)
{

  if(data[item.path])
  {    
    page_routes.splice(c ,1);
  }
  c++;
  
}

page_routes.push(
  // { path: "dashboard", component: DashboardComponent, name: 'Dashboard' },
{ path: "", redirect: { name: 'dashboard' } });

export default page_routes;