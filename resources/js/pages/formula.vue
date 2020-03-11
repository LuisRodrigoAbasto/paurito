<template>
  <main class="main">
    <!-- Breadcrumb -->
    <!-- <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/">Escritorio</a>
      </li>
    </ol> -->
    <br>
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Formula
          <button
            type="button"
            @click="mostrarDetalle('formula','registrar')"
            class="btn btn-secondary"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <!---Listado -->
        <template v-if="listado">
          <div class="card-body">
            <div class="form-group row">
              <div class="col-md-6">
                <div class="input-group">
                  <select class="form-control col-md-5" v-model="criterio">
                    <option value="nombre">Nombre</option>
                  </select>
                  <input
                    type="text"
                    v-model="buscar"
                    @keyup.enter="listar(1,buscar,criterio)"
                    class="form-control"
                    placeholder="Texto a buscar"
                  />
                  <span class="input-group-append">
                    <button
                      type="submit"
                      @click="listar(1,buscar,criterio)"
                      class="btn btn-primary"
                    >
                      <i class="fa fa-search"></i> Buscar
                    </button>
                  </span>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>N°</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Estado</th>
                    <th>Opciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="formula in arrayFormula" :key="formula.id">
                    <td>
                      <span class="badge badge-success">{{ formula.id }}</span>
                    </td>
                    <td>{{ formula.nombre }}</td>
                    <td>{{ formula.cantidad }}</td>
                    <td>
                      <div v-if="formula.estado">
                        <span class="badge badge-success">Activo</span>
                      </div>
                      <div v-else>
                        <span class="badge badge-danger">Desactivado</span>
                      </div>
                    </td>
                    <td>
                      <button type="button" @click="imprimir(formula.id)" class="btn btn-info btn-sm">
                      <i class="fa fa-print fa-lg"></i>
                    </button>&nbsp;
                      <button
                        type="button"
                        @click="pdfFormula(formula.id)"
                        class="btn btn-danger btn-sm"
                    >
                      <i class="fa fa-file-pdf-o"></i>
                    </button> &nbsp;
                      <button
                        type="button"
                        @click="mostrarDetalle('formula','actualizar',formula)"
                        class="btn btn-warning btn-sm"
                      >
                        <i class="icon-pencil"></i>
                      </button> &nbsp;
                      <template v-if="formula.estado">
                        <button
                          type="button"
                          class="btn btn-danger btn-sm"
                          @click="desactivar(formula.id)"
                        >
                          <i class="icon-trash"></i>
                        </button>
                      </template>
                      <template v-else>
                        <button
                          type="button"
                          class="btn btn-info btn-sm"
                          @click="activar(formula.id)"
                        >
                          <i class="icon-check"></i>
                        </button>
                      </template>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <nav>
              <ul class="pagination">
                <li class="page-item" v-if="pagination.current_page > 1">
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)"
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
                    @click.prevent="cambiarPagina(page,buscar,criterio)"
                    v-text="page"
                  ></a>
                </li>
                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)"
                  >Sig</a>
                </li>
              </ul>
            </nav>
          </div>
        </template>
        <!-- Fin de Listado-->
        <!---Detalle-->
        <template v-else>
          <div :class="'card-body '+validaciones">
            <div class="form-group row border">
              <div class="col-md-12">
                <div class="form-group">
                  <label for>Nombre</label>
                  <input type="text" required class="form-control" v-model="nombre" />
                </div>
              </div>
            </div>
            <div class="form-group row border">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Producto</label>
                  <v-select
                    @search="selectProducto"
                    label="nombre"
                    :options="arrayProducto"
                    placeholder="Buscar Productos..."
                    @input="getDatosProducto"
                  ></v-select>
                </div>
              </div>
              <div class="col-md-0">
                <div class="form-group">
                  <button
                    data-toggle="modal"
                    data-target="#ModalLong"
                    @click="abrirModal()"
                    class="btn btn-success form-control btnagregar"
                  >
                    <i class="icon-plus"></i>
                    <i class="icon-plus"></i>
                    <i class="icon-plus"></i>
                  </button>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label for>Cantidad</label>
                  <input type="number" value="0" class="form-control" v-model="cantidad" />
                </div>
              </div>
              <div class="col-md-0">
                <div class="form-group">
                  <label for>Unidad</label>
                  <td v-text="unidad"></td>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar">
                    <i class="icon-plus">Agregar</i>
                  </button>
                </div>
              </div>
            </div>
            <table class="table table-responsive-sm table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th>N°</th>
                      <th>Opciones</th>
                      <th>Producto</th>
                      <th>Cantidad</th>
                      <th>Cantidad + Unidad</th>
                    </tr>
                  </thead>
                  <tbody v-if="(arrayDetalle.length)">
                    <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.idProducto">
                      <td>
                        <b>{{ (index+1) }}</b>
                      </td>
                      <td>
                        <button
                          @click="eliminarDetalle(index)"
                          type="button"
                          class="btn btn-danger btn-sm"
                        >
                          <i class="icon-close"></i>
                        </button>
                      </td>
                      <td v-text="detalle.producto"></td>
                      <td class="input-group">
                        <input
                          type="number"
                          min="0"
                          step="any"
                          @input="sumarCantidadTotal()"
                          v-model="detalle.cantidad"
                          class="form-control"
                          required
                        />
                        <span class="input-group-append">&nbsp;&nbsp;{{ detalle.referencia }}</span>
                      </td>
                      <td>
                        {{ Math.floor(detalle.cantidad/detalle.codigo)}} {{ detalle.unidad }} +
                        {{ (((detalle.cantidad/detalle.codigo) - (Math.floor(detalle.cantidad/detalle.codigo)))*detalle.codigo).toFixed(2) }} {{ detalle.referencia }}
                      </td>
                    </tr>
                    <tr>
                      <td>Total :</td>
                      <td colspan="3"></td>
                      <td>{{ cantidadTotal }}</td>
                      <td colspan="1"></td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="6">No hay Productos Agregados</td>
                    </tr>
                  </tbody>
                </table>
            <div class="form-group row">
              <div class="col-md-12">
                <button type="button" class="btn btn-secondary" @click="ocultarDetalle()">Cerrar</button>
                <button
                  type="button"
                  v-if="tipoAccion==1"
                  class="btn btn-primary"
                  @click="registrar()"
                >Guardar</button>
                <button
                  type="button"
                  v-if="tipoAccion==2"
                  class="btn btn-primary"
                  @click="actualizar()"
                >Actualizar</button>
              </div>
            </div>
          </div>
        </template>
        <!--Fin de Detalle -->
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div
      class="modal fade bd-example-modal-lg"
      tabindex="-1"
      id="ModalLong"
      role="dialog"
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
          <div class="modal-body">
            <div class="form-group row">
              <div class="col-md-8">
                <div class="input-group">
                  <select class="form-control col-md-4" v-model="criterioP">
                    <option value="nombre">Nombre</option>
                  </select>
                  <input
                    type="text"
                    v-model="buscarP"
                    @keyup.enter="listarProducto(buscarP,criterioP)"
                    class="form-control"
                    placeholder="Buscar Producto"
                  />
                  <span class="input-group-append">
                    <button
                      type="submit"
                      @click="listarProducto(buscarP,criterioP)"
                      class="btn btn-primary"
                    >
                      <i class="fa fa-search"></i> Buscar
                    </button>
                  </span>
                </div>
              </div>
            </div>
            <table class="table table-responsive-sm table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th>Opciones</th>
                  <th>Nombre</th>
                  <th>Stock</th>
                  <th>Unidad</th>
                  <th>Estado</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="data in arrayProducto" :key="data.id">
                  <td>
                    <button
                      type="button"
                      @click="agregarDetalleModal(data)"
                      class="btn btn-success btn-sm"
                    >
                      <i class="icon-check"></i>
                    </button>
                  </td>
                  <td v-text="data.nombre"></td>
                  <td>{{ data.stock }} {{ data.unidad }}</td>
                  <td>{{ data.codigo }} {{ data.referencia }}</td>
                  <td>
                    <div v-if="data.estado">
                      <span class="badge badge-success">Activo</span>
                    </div>
                    <div v-else>
                      <span class="badge badge-danger">Desactivado</span>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
              @click="cerrarModal()"
            >Cerrar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
  </main>
</template>

<script>
import Vue from "vue";
import vSelect from "vue-select";

import "vue-select/dist/vue-select.css";

Vue.component("v-select", vSelect);
export default {
  data() {
    return {
      formula_id: 0,
      nombre: "",
      arrayFormula: [],
      arrayDetalle: [],
      arrayProducto: [],
      listado: 1,
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorMostrar: 0,
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      criterio: "nombre",
      criterioP: "nombre",
      buscarP: "",
      buscar: "",
      idProducto: 0,
      cantidad: 0,
      producto: "",
      unidad: "",
      referencia: "",
      codigo: 0,
      validaciones: "",
      mensaje: "",
      cantidadTotal: 0
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
  methods: {
    listar(page, buscar, criterio) {
      let me = this;
      var url =
        "formula?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayFormula = respuesta.formulas.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    pdfFormula(id) {
      window.open("formula/pdf/formula_" + id);
    },
    imprimir(id) {
      window.open("formula/imprimir/formula_" + id);
    },
    selectProducto(search, loading) {
      let me = this;
      loading(true);
      var url = "producto/selectProducto?filtro=" + search;
      axios
        .get(url)
        .then(function(response) {
          let respuesta = response.data;
          q: search;
          me.arrayProducto = respuesta.productos;
          loading(false);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    getDatosProducto(val1) {
      let me = this;
      me.loading = true;
      try {
        me.idProducto = val1.id;
        me.producto = val1.nombre;
        me.codigo = val1.codigo;
        me.unidad = val1.unidad;
        me.referencia = val1.referencia;
      } catch (error) {
        me.idProducto = 0;
        me.producto = "";
        me.codigo = 0;
        me.unidad = "";
        me.referencia = "";
      }
    },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      // actualizar la Pagina
      me.pagination.current_page = page;
      // enviar la peticion para visualizar la data de esta pagina
      me.listar(page, buscar, criterio);
    },
    encuentra(id) {
      var sw = false;
      for (var i = 0; i < this.arrayDetalle.length && sw == false; i++) {
        if (this.arrayDetalle[i].idProducto == id) {
          sw = true;
        }
      }
      return sw;
    },
    eliminarDetalle(index) {
      let me = this;
      me.arrayDetalle.splice(index, 1);
    },
    agregarDetalle() {
      let me = this;
      if (me.idProducto == 0) {
        Swal.fire({
          position: "center",
          title: "Error !!",
          type: "error",
          showConfirmButton: false,
          timer: 1000
        });
      } else {
        if (me.encuentra(me.idProducto)) {
          Swal.fire({
            position: "center",
            title: "Se Encuentra Agregado",
            type: "error",
            showConfirmButton: false,
            timer: 1000
          });
        } else {
          me.arrayDetalle.push({
            idProducto: me.idProducto,
            producto: me.producto,
            cantidad: me.cantidad,
            unidad: me.unidad,
            codigo: me.codigo,
            referencia: me.referencia
          });
          me.cantidad = 0;
        }
      }
    },
    ocultarDetalle() {
      this.listado = 1;
    },
    agregarDetalleModal(data = []) {
      let me = this;
      if (me.encuentra(data["id"])) {
        Swal.fire({
          position: "center",
          title: "Se Encuentra Agregado",
          type: "error",
          showConfirmButton: false,
          timer: 1000
        });
      } else {
        me.arrayDetalle.push({
          idProducto: data["id"],
          producto: data["nombre"],
          cantidad: 1,
          unidad: data["unidad"],
          codigo: data["codigo"],
          referencia: data["referencia"]
        });
      }
    },
    listarProducto(buscar, criterio) {
      let me = this;
      var url =
        "producto/listarProducto?buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayProducto = respuesta.productos;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    limpiarRegistro() {
      this.formula_id = 0;
      this.arrayDetalle = [];
      this.arrayProducto = [];
      this.tituloModal = "";

      this.criterio = "nombre";
      this.criterioP = "nombre";
      this.buscarP = "";
      this.buscar = "";
      this.idProducto = 0;
      this.cantidad = 0;
      this.producto = "";
      this.unidad = "";

      this.nombre = "";
      this.cantidadTotal = 0;
      this.validaciones = "";
      this.mensaje = "";
    },
    sumarCantidadTotal() {
      this.cantidadTotal = 0;
      for (let i = 0; i < this.arrayDetalle.length; i++) {
        this.cantidadTotal =
          this.arrayDetalle[i].cantidad * 1 + this.cantidadTotal;
      }
      this.cantidadTotal = this.cantidadTotal.toFixed(2);
    },
    registrar() {
      if (this.validar()) {
        this.validaciones = "was-validated";
        Swal.fire({
          position: "center",
          type: "error",
          title: this.mensaje,
          showConfirmButton: false,
          timer: 1500
        });
        return;
      }
      let me = this;

      axios
        .post("formula/registrar", {
          nombre: this.nombre,
          cantidad: this.cantidadTotal,
          data: this.arrayDetalle
        })
        .then(function(response) {
          me.listado = 1;
          me.listar(1, "", "nombre");
          me.limpiarRegistro();
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    actualizar() {
      if (this.validar()) {
        this.validaciones = "was-validated";
        Swal.fire({
          position: "center",
          type: "error",
          title: this.mensaje,
          showConfirmButton: false,
          timer: 1500
        });
        return;
      }
      let me = this;

      axios
        .put("formula/actualizar", {
          nombre: this.nombre,
          cantidad: this.cantidadTotal,
          data: this.arrayDetalle,
          id: this.formula_id
        })
        .then(function(response) {
          me.listado = 1;
          me.listar(1, "", "nombre");
          me.limpiarRegistro();
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    desactivar(id) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });

      swalWithBootstrapButtons
        .fire({
          title: "Estas Seguro de Desactivar el Registro?",
          text: "Si Desactiva no estara en la Lista!",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Si, Desactivar!",
          cancelButtonText: "No, Cancelar!",
          reverseButtons: true
        })
        .then(result => {
          if (result.value) {
            let me = this;

            axios
              .put("formula/desactivar", {
                id: id
              })
              .then(function(response) {
                me.listar(1, "", "nombre");
                Swal.fire({
                  position: "center",
                  type: "success",
                  title: "El Registro ha sido Desactivado",
                  showConfirmButton: false,
                  timer: 1000
                }).catch(function(error) {
                  console.log(error);
                });
              });
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire({
              position: "center",
              type: "error",
              title: "Cancelado",
              showConfirmButton: false,
              timer: 1000
            });
          }
        });
    },
    activar(id) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });

      swalWithBootstrapButtons
        .fire({
          title: "Estas Seguro Activar el Registro?",
          text: "Si Activa estara en la Lista!",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Si, Activar!",
          cancelButtonText: "No, Cancelar!",
          reverseButtons: true
        })
        .then(result => {
          if (result.value) {
            let me = this;

            axios
              .put("formula/activar", {
                id: id
              })
              .then(function(response) {
                me.listar(1, "", "nombre");
                Swal.fire({
                  position: "center",
                  type: "success",
                  title: "El Registro ha sido Activado",
                  showConfirmButton: false,
                  timer: 1000
                }).catch(function(error) {
                  console.log(error);
                });
              });
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire({
              position: "center",
              type: "error",
              title: "Cancelado",
              showConfirmButton: false,
              timer: 1000
            });
          }
        });
    },
    validar() {
      if (!this.nombre) {
        this.mensaje = "Ingrese el Nombre a la Formula";
        return true;
      }

      if (this.arrayDetalle.length <= 0) {
        this.mensaje = "No tiene Productos en su Detalle";
        return true;
      }
      for (var i = 0; i < this.arrayDetalle.length; i++) {
        if (!this.arrayDetalle[i].cantidad) {
          this.mensaje = "Ingrese la Cantidad en el Detalle, no Puede Ser 0";
          return true;
        }
      }
      return false;
    },
    mostrarDetalle(modelo, accion, data = []) {
      this.arrayDetalle = [];
      switch (modelo) {
        case "formula": {
          switch (accion) {
            case "registrar": {
              this.listado = 0;
              this.tipoAccion = 1;
              this.limpiarRegistro();
              break;
            }
            case "actualizar": {
              this.listado = 0;
              this.tipoAccion = 2;
              this.formula_id = data["id"];
              this.nombre = data["nombre"];
              this.cantidadTotal = data["cantidad"];
              let me = this;
              var url = "formula/listarFormula?id=" + data["id"];
              axios
                .get(url)
                .then(function(response) {
                  var respuesta = response.data;
                  me.arrayDetalle = respuesta.detalles;
                })
                .catch(function(error) {
                  console.log(error);
                });

              break;
            }
          }
        }
      }
    },

    ocultarDetalle() {
      this.listado = 1;
      this.tipoAccion = 0;
      this.limpiarRegistro();
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
    },
    abrirModal() {
      this.arrayProducto = [];
      this.modal = 1;
      this.tituloModal = "Selecione uno o varios Productos";
      this.idFormula_if = "selecion id";
    }
  },
  mounted() {
    this.listar(1, this.buscar, this.criterio);
  }
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
@media (min-width: 600px) {
  .btnagregar {
    margin-top: 2rem;
  }
}
</style>

