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
          <button
            type="button"
            data-toggle="modal"
            data-target="#ModalLong"
         
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
                    <select class="form-control col-md-3" v-model="opcion">
                      <option value="nombre">Nombre</option>
                    </select>
                    <input
                      type="text"
                      v-model="buscar"
                      @keyup.enter="listar(1,buscar,opcion)"
                      class="form-control"
                      placeholder="Buscar Producto"
                    />
                    <span class="input-group-append">
                      <button
                        type="submit"
                        @click="listar(1,buscar,opcion)"
                        class="btn btn-primary"
                      >
                        <i class="fa fa-search"></i> Buscar
                      </button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th>N°</th>

                  <th>Nombre</th>
                  <th>Stock</th>
                  <th>Unidad</th>
                  <th>Total + Unidad</th>
                  <th>Estado</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="data in arrayProducto" :key="data.id">
                  <td>
                    <span class="badge badge-success" v-text="data.id"></span>
                  </td>
                  <td>{{ data.nombre}}</td>
                  <td>{{ data.stock }} {{ data.unidad }}</td>
                  <td>{{ data.codigo }} {{ data.referencia }}</td>

                  <td>
                    {{ data.total }} {{ data.unidad }} +
                    {{ data.decimales }} {{ data.referencia }}
                  </td>
                  <td>
                    <div v-if="data.estado">
                      <span class="badge badge-success">Activo</span>
                    </div>
                    <div v-else>
                      <span class="badge badge-danger">Desactivado</span>
                    </div>
                  </td>
                  <td>
                    <button
                      type="button"
                      data-toggle="modal"
                      data-target="#ModalLong"
                      @click="abrirModal('producto','actualizar',data)"
                      class="btn btn-warning btn-sm"
                    >
                      <i class="icon-pencil"></i>
                    </button> &nbsp;
                    <template v-if="data.estado">
                      <button
                        type="button"
                        class="btn btn-danger btn-sm"
                        @click="desactivar(data.id)"
                      >
                        <i class="icon-trash"></i>
                      </button>
                    </template>
                    <template v-else>
                      <button type="button" class="btn btn-info btn-sm" @click="activar(data.id)">
                        <i class="icon-check"></i>
                      </button>
                    </template>
                  </td>
                </tr>
              </tbody>
            </table>
          </div> -->
<app-table :array_data="array_data">
</app-table>
          <nav>

            <!-- <ul class="pagination">
              <li class="page-item" v-if="pagination.current_page > 1">
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(pagination.current_page - 1,buscar)"
                >Ant</a>
              </li>
              <li
                class="page-item"
                v-for="page in pagesNumber"
                :key="page"
                :class="[page == isActived ? 'active' : '']"
              >
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(page,buscar)"
                  v-text="page"
                ></a>
              </li>
              <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(pagination.current_page + 1,buscar)"
                >Sig</a>
              </li>
            </ul> -->
          </nav>
        </div>
      </div>

    </div>

    <!-- <div
      class="modal fade bd-example-modal-lg"
      tabindex="-1"
      role="dialog"
      id="ModalLong"
      aria-labelledby="myModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button
              type="button"
              class="close"
              @click="cerrarModal()"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div :class="'modal-body '+activarValidate">
            <form action method="post" enctype="multipart/form-data" class="form-horizontal">
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="nombre"
                    placeholder="Nombre del Producto............"
                    class="form-control"
                    required
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Stock</label>
                <div class="col-md-9">
                  <input
                    type="number"
                    min="0"
                    step="any"
                    v-model="stock"
                    class="form-control"
                    required
                    placeholder="Ingrese el Stock"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Ref/Stock</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="unidad"
                    class="form-control"
                    required
                    placeholder="Ingrese la Referencia para el Stock...."
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Unidad</label>
                <div class="col-md-9">
                  <input
                    type="number"
                    v-model="codigo"
                    min="0"
                    step="any"
                    class="form-control"
                    required
                    placeholder="Ingrese La Cantidad...."
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Ref/Unidad</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="referencia"
                    class="form-control"
                    required
                    placeholder="Ingrese Su Referencia de la Unidad...."
                  />
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
              @click="cerrarModal()"
            >Cerrar</button>
            <button
              type="button"
              v-if="tipoAccion==1"
              class="btn btn-primary"
              @click="registrarProducto()"
            >Guardar</button>
            <button
              type="button"
              v-if="tipoAccion==2"
              class="btn btn-primary"
              @click="actualizarProducto()"
            >Actualizar</button>
          </div>
        </div>

      </div>


    </div> -->

  <!-- </main> -->
  </div>
</template>


<script>
import table from "../components/table.vue";
import Vue from "vue";
export default {
    components: {
    "app-table": table
  },
  data() {
    return {
      producto_id: 0,
      nombre: "",
      stock: 0,
      codigo: 0,
      unidad: "",
      referencia: "",
     array_data:{
        titulo:[
        {nombre:'id',titulo:"ID"},
        {nombre:"nombre",titulo:"Producto"},
        {nombre:"stock",titulo:"Stock"},
        ],
        data:[]
      },
      tituloModal: "",
      tipoAccion: 0,
      errorProducto: 0,
      errorMostrarMsjProducto: [],
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
      activarValidate: "",
      mensaje: "",
      opcion:"nombre"
    };
  },
  computed: {
    isActived: function() {
      return this.pagination.current_page;
    },
    pagesNumber: function() {
      if (!this.pagination.to) {
        return [];
      }
      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }
      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    }
  },
    mounted() {
    this.listar(1, this.buscar,this.opcion);
  },
  methods: {

    listar(page, buscar,opcion) {
      var url = "api/producto?page=" + page + "&buscar=" + buscar+"&opcion="+opcion;
      axios
        .get(url)
        .then(resp=> {
          // var respuesta = response.data;
          this.array_data.data=resp.data.data.data;
          // console.log(this.array_data);
          // this.pagination = resp.data.data
        })
        .catch(function(error) {
          console.log(error);
        });
    },
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

