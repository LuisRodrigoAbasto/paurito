<template>
  <!-- <main class="main"> -->
    <!-- Breadcrumb -->
    <!-- <ol class="breadcrumb">
      <li class="breadcrumb-item">Home</li>
      <li class="breadcrumb-item">
        <a href="#">Admin</a>
      </li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>-->
    <div>
    <br />
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Productos
          <!-- <button
            type="button"
            data-toggle="modal"
            :data-target="'#'+array_data.modal.id"
            @click="nuevo(true)"
            class="btn btn-secondary"
          > -->
           <button
            type="button"
            @click="nuevo()"
            class="btn btn-secondary"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>

          <button type="button"  class="btn btn-danger">
            <i class="fa fa-file-pdf-o"></i>&nbsp;PDF
          </button>

          <button type="button" class="btn btn-info">
            <i class="fa fa-print fa-lg"></i>
          </button>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-12">
              <div class="input-group">
                <div class="col-md-10">
                  <div class="input-group">
                    <select class="form-control col-md-3" v-model="opcion" >
                      <option value="nombre">Nombre</option>
                    </select>
                    <input
                      type="text"
                      class="form-control"
                      v-model="buscar"
                      placeholder="Buscar Producto"
                      @keyup.enter="listar(1,buscar,opcion)"
                    />
                    <span class="input-group-append">
                      <button
                        type="submit"
                        class="btn btn-primary"
                        @click="listar(1,buscar,opcion)"
                      >
                        <i class="fa fa-search"></i> Buscar
                      </button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
            <app-table :array_data="array_data" @update="update($event)">
            </app-table>
          <app-pagination-datos :pagination="pagination" :offset="offset" @pagina="pagina($event)">
          </app-pagination-datos>
        </div>
      </div>
    </div>

  <app-modal-datos :modal="array_data.modal" @cerrar_modal="cerrar_modal()" @registrar="guardar()">
  </app-modal-datos>
  <!-- </main> -->
  </div>
</template>


<script>
import table from "../components/table.vue";
import modal_datos from "../components/modal_datos.vue";
import pagination_datos from "../components/pagination.vue";
import Swal from "sweetalert2/dist/sweetalert2.js";
import "sweetalert2/src/sweetalert2.scss";
import Vue from "vue";
export default {
    components: {
    "app-table": table,
    "app-modal-datos": modal_datos,
    "app-pagination-datos": pagination_datos,
  },
  data() {
    return {
      
     
     array_data:{
        titulo:[
        {nombre:'id',titulo:"ID"},
        {nombre:"nombre",titulo:"Producto"},
        {nombre:"stock",titulo:"Stock"},
        {nombre:"referencia",titulo:"Referencia"},
        {nombre:"total",titulo:"Total + Unidad"},
        ],
        
        modal:{
          titulo:"Producto",
          id:'producto',
          validate:false,
          datos:[
                {
                titulo:"ID",
                indice:'id',
                tipo:'numero',
                dato:0,
                visible:false,
                placeholder:'',
                required:false,
                error:''
                },
                {
                titulo:"Nombre",
                indice:'nombre',
                tipo:'text',
                dato:'',
                visible:true,
                placeholder:'Ingrese El Nombre del Producto',
                required:true,
                error:''
                },
                {
                titulo:"Stock",
                indice:'stock',
                tipo:'decimal',
                dato:0,
                visible:true,
                placeholder:'Ingrese el Stock',
                required:true,
                error:''
                },
                {
                titulo:"Ref/Stock",
                indice:'ref_stock',
                tipo:'text',
                dato:'',
                visible:true,
                placeholder:'Ingrese la Referencia para el Stock',
                required:true,
                error:''
                },
                {
                titulo:"Unidad",
                indice:'unidad',
                tipo:'decimal',
                dato:0,
                visible:true,
                placeholder:'Ingrese Unidad',
                required:true,
                error:''
                },
                {
                titulo:"Ref/Unidad",
                indice:'ref_unidad',
                tipo:'text',
                dato:'',
                visible:true,
                placeholder:'Ingrese la Referencia de la Unidad',
                required:true,
                error:''
                },
                ]
            ,
          },
        data:[],
      },
      
      datos:{
        id:0,
        nombre:'',
        stock:0,
        ref_stock:'',
        unidad:0,
        ref_unidad:''
      },
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      buscar: "",
      // activarValidate: "",
      // mensaje: "",
      opcion:"nombre",
      metodo:'',
      url:'api/producto'
    };
  },
    mounted() {
    this.listar(1, this.buscar,this.opcion);
    // console.log(this.array_data[0]);
  },
  methods: {
    pagina(page){
      this.listar(page,this.buscar,this.opcion)
    },
    listar(page, buscar,opcion) {
      var url = this.url+"?page=" + page + "&buscar=" + buscar+"&opcion="+opcion;
      axios
        .get(url)
        .then(resp=> {
          // var respuesta = resp.data;
          this.array_data.data=resp.data.data.data;
          this.pagination={
                  total: resp.data.data.total,
                  current_page: resp.data.data.current_page,
                  per_page: resp.data.data.per_page,
                  last_page: resp.data.data.last_page,
                  from: resp.data.data.from,
                  to: resp.data.data.to
          }
          // console.log(this.array_data);
          // this.pagination = resp.data.data
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    limpiar_datos(){
      this.array_data.modal.datos=[
                {
                titulo:"ID",
                indice:'id',
                tipo:'numero',
                dato:0,
                visible:false,
                placeholder:'',
                required:false,
                error:''
                },
                {
                titulo:"Nombre",
                indice:'nombre',
                tipo:'text',
                dato:'',
                visible:true,
                placeholder:'Ingrese El Nombre del Producto',
                required:true,
                error:''
                },
                {
                titulo:"Stock",
                indice:'stock',
                tipo:'decimal',
                dato:0,
                visible:true,
                placeholder:'Ingrese el Stock',
                required:true,
                error:''
                },
                {
                titulo:"Ref/Stock",
                indice:'ref_stock',
                tipo:'text',
                dato:'',
                visible:true,
                placeholder:'Ingrese la Referencia para el Stock',
                required:true,
                error:''
                },
                {
                titulo:"Unidad",
                indice:'unidad',
                tipo:'decimal',
                dato:0,
                visible:true,
                placeholder:'Ingrese Unidad',
                required:true,
                error:''
                },
                {
                titulo:"Ref/Unidad",
                indice:'ref_unidad',
                tipo:'text',
                dato:'',
                visible:true,
                placeholder:'Ingrese la Referencia de la Unidad',
                required:true,
                error:''
                },
                ];
    },
    eventoAlerta(success, mensaje) {
      Swal.fire({
        position: "center",
        icon: success,
        title: mensaje,
        showConfirmButton: false,
        timer: 1500
      });
    },
    cerrar_modal(){
      $(`#${this.array_data.modal.id}`).modal('hide');
      this.limpiar_datos()
    },
    nuevo(){
      $(`#${this.array_data.modal.id}`).modal('show');
    },
    agregar_dato(){
      this.array_data.modal.datos.forEach(x=>
        this.datos[x.indice]=x.dato      
      );
      // console.log(this.datos);
    },
    guardar(){
      this.agregar_dato();
      if( this.datos.id==0){
        this.metodo="post"
      }
      else{
        this.metodo="put"
      }
        axios({
        method: this.metodo,
        data: this.datos,
        url: this.url
        })
        .then(resp => {
        this.eventoAlerta(resp.data.alert,resp.data.message)
        this.listar(1,'','nombre');
        this.cerrar_modal();
        this.array_data.modal.validate=resp.data.success;
        })
        .catch(error => {
        this.array_data.modal.validate=error.response.data.success;
        this.eventoAlerta(error.response.data.alert,error.response.data.error)

        console.log('ERROR', error);
        })
    },
    pasar_datos(){
       this.array_data.modal.datos.forEach(x=>
        x.dato=this.datos[x.indice]
      );
    },
    update(id)
    {
      axios({
        method: 'get',
        url: this.url+'/get/'+id
        })
        .then(resp => {        
          this.datos=resp.data.data
          this.pasar_datos();
        // this.array_data.modal.datos
        // console.log(registro);
        })
        .catch(error => {
        this.eventoAlerta(error.response.data.alert,error.response.data.error)
        // console.log('ERROR', error);
        })
    }
    // cambiarPagina(page, buscar) {
    //   let me = this;
    //   // actualizar la Pagina
    //   me.pagination.current_page = page;
    //   // enviar la peticion para visualizar la data de esta pagina
    //   me.listarProducto(page, buscar);
    // },
    // cargarPdf() {
    //   window.open("producto/listarPdf");
    // },
    // imprimir() {
    //   window.open("producto/imprimir");
    // },
    // registrarProducto() {
    //   if (this.validarProducto()) {
    //     this.activarValidate = "was-validated";
    //     Swal.fire({
    //       position: "center",
    //       type: "error",
    //       title: this.mensaje,
    //       showConfirmButton: false,
    //       timer: 1500
    //     });
    //     return;
    //   }
    //   let me = this;

    //   axios
    //     .post("producto/registrar", {
    //       nombre: this.nombre,
    //       stock: this.stock,
    //       unidad: this.unidad,
    //       codigo: this.codigo,
    //       referencia: this.referencia
    //     })
    //     .then(function(response) {
    //       $("#ModalLong").modal("hide");
    //       me.cerrarModal();
    //       me.listarProducto(1, "");
    //     })
    //     .catch(function(error) {
    //       console.log(error);
    //     });
    // },
    // actualizarProducto() {
    //   if (this.validarProducto()) {
    //     this.activarValidate = "was-validated";
    //     Swal.fire({
    //       position: "center",
    //       type: "error",
    //       title: this.mensaje,
    //       showConfirmButton: false,
    //       timer: 1500
    //     });
    //     return;
    //   }
    //   let me = this;

    //   axios
    //     .put("producto/actualizar", {
    //       nombre: this.nombre,
    //       stock: this.stock,
    //       unidad: this.unidad,
    //       codigo: this.codigo,
    //       referencia: this.referencia,
    //       id: this.producto_id
    //     })
    //     .then(function(response) {
    //       $("#ModalLong").modal("hide");
    //       me.cerrarModal();
    //       me.listarProducto(1, "");
    //     })
    //     .catch(function(error) {
    //       console.log(error);
    //     });
    // },
    // desactivar(id) {
    //   const swalWithBootstrapButtons = Swal.mixin({
    //     customClass: {
    //       confirmButton: "btn btn-success",
    //       cancelButton: "btn btn-danger"
    //     },
    //     buttonsStyling: false
    //   });

    //   swalWithBootstrapButtons
    //     .fire({
    //       title: "Estas Seguro de Desactivar el Registro?",
    //       text: "Si Desactiva no estara en la Lista!",
    //       type: "warning",
    //       showCancelButton: true,
    //       confirmButtonText: "Si, Desactivar!",
    //       cancelButtonText: "No, Cancelar!",
    //       reverseButtons: true
    //     })
    //     .then(result => {
    //       if (result.value) {
    //         let me = this;

    //         axios
    //           .put("producto/desactivar", {
    //             id: id
    //           })
    //           .then(function(response) {
    //             me.listarProducto(1, "");
    //             Swal.fire({
    //               position: "center",
    //               type: "success",
    //               title: "El Registro ha sido Desactivado",
    //               showConfirmButton: false,
    //               timer: 1000
    //             }).catch(function(error) {
    //               console.log(error);
    //             });
    //           });
    //       } else if (result.dismiss === Swal.DismissReason.cancel) {
    //         Swal.fire({
    //           position: "center",
    //           type: "error",
    //           title: "Cancelado",
    //           showConfirmButton: false,
    //           timer: 1000
    //         });
    //       }
    //     });
    // },
    // activar(id) {
    //   const swalWithBootstrapButtons = Swal.mixin({
    //     customClass: {
    //       confirmButton: "btn btn-success",
    //       cancelButton: "btn btn-danger"
    //     },
    //     buttonsStyling: false
    //   });

    //   swalWithBootstrapButtons
    //     .fire({
    //       title: "Estas Seguro de Activar el Registro?",
    //       text: "Si Activa no estara en la Lista!",
    //       type: "warning",
    //       showCancelButton: true,
    //       confirmButtonText: "Si, Activar!",
    //       cancelButtonText: "No, Cancelar!",
    //       reverseButtons: true
    //     })
    //     .then(result => {
    //       if (result.value) {
    //         let me = this;

    //         axios
    //           .put("producto/activar", {
    //             id: id
    //           })
    //           .then(function(response) {
    //             me.listarProducto(1, "");
    //             Swal.fire({
    //               position: "center",
    //               type: "success",
    //               title: "El Registro ha sido Activado",
    //               showConfirmButton: false,
    //               timer: 1000
    //             }).catch(function(error) {
    //               console.log(error);
    //             });
    //           });
    //       } else if (result.dismiss === Swal.DismissReason.cancel) {
    //         Swal.fire({
    //           position: "center",
    //           type: "error",
    //           title: "Cancelado",
    //           showConfirmButton: false,
    //           timer: 1000
    //         });
    //       }
    //     });
    // },
    // validarProducto() {
    //   if (!this.nombre || !this.unidad || !this.referencia) {
    //     this.mensaje = "Ingrese El Nombre y Referencia del stock y la Unidad";
    //     return true;
    //   }
    //   if (!this.stock || !this.codigo) {
    //     this.mensaje = "Ingrese El Stock y la Unidad no Puede ser 0";
    //     return true;
    //   }
    //   return false;
    // },
    // cerrarModal() {
    //   this.tituloModal = "";
    //   this.limpiarRegistro();
    // },
    // limpiarRegistro() {
    //   this.nombre = "";
    //   this.stock = 0;
    //   this.codigo = 0;
    //   this.unidad = "";
    //   this.activarValidate = "";
    //   this.mensaje = "";
    //   this.referencia = "";
    // },
    // abrirModal(modelo, accion, data = []) {
    //   switch (modelo) {
    //     case "producto": {
    //       switch (accion) {
    //         case "registrar": {
    //           this.tituloModal = "Registrar Producto";
    //           this.limpiarRegistro();
    //           this.tipoAccion = 1;
    //           break;
    //         }
    //         case "actualizar": {
    //           // console.log(data);
    //           this.tituloModal = "Actualizar Producto";
    //           this.tipoAccion = 2;
    //           this.producto_id = data["id"];
    //           this.nombre = data["nombre"];
    //           this.stock = data["stock"];
    //           this.codigo = data["codigo"];
    //           this.unidad = data["unidad"];
    //           this.referencia = data["referencia"];
    //           break;
    //         }
    //       }
    //     }
    //   }
    // }
  },

};
</script>
<style>
.modal-content {
  width: 100% !important;
  position: absolute !important;
}
.mostrar {
  display: list-item !important;
  opacity: 1 !important;
  position: absolute !important;
  background-color: #3c29297a !important;
}
.div-error {
  display: flex;
  justify-content: center;
}
.text-error {
  color: red !important;
  font-weight: bold;
}
</style>

