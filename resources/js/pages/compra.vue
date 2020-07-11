<template>
  <!-- <main class="main"> -->
    <div>
    <!-- Breadcrumb-->
    <!-- <ol class="breadcrumb">
      <li class="breadcrumb-item">Home</li>
      <li class="breadcrumb-item">
        <a href="#">Admin</a>
      </li>
      <li class="breadcrumb-item active">Dashboard</li> -->
      <!-- Breadcrumb Menu-->
      <!-- <li class="breadcrumb-menu d-md-down-none">
        <div class="btn-group" role="group" aria-label="Button group">
          <a class="btn" href="#">
            <i class="icon-speech"></i>
          </a>
          <a class="btn" href="#">
            <i class="icon-graph"></i> Dashboard
          </a>
          <a class="btn" href="#">
            <i class="icon-settings"></i> Settings
          </a>
        </div>
      </li>
    </ol> -->

<br>
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Compra
          <button
            type="button"
            @click="mostrarDetalle('compra','registrar')"
            class="btn btn-secondary"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>

        <template v-if="listado">
          <div class="card-body">
            <div class="form-group row">
              <div class="col-md-6">
                <div class="input-group">
                  <select class="form-control col-md-5" v-model="criterio">
                    <option value="nombre">Proveedor</option>
                    <option value="descripcion">Descripcion</option>
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

            <table class="table table-responsive-sm table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Factura</th>
                  <th>N° Reg.</th>
                  <th>Proveedor</th>
                  <th>Fecha</th>
                  <th>Pago</th>
                  <!-- <th>Cantidad</th> -->
                  <th>Descripcion</th>
                  <th>Total Bs.</th>
                  <th>Estado</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="data in arrayData" :key="data.id">
                  <td>
                    <span class="badge badge-danger" v-text="data.id"></span>
                  </td>
                  <td>
                    <span class="badge badge-warning" v-text="data.factura"></span>
                  </td>
                  <td>
                    <span class="badge badge-success" v-text="data.registro"></span>
                  </td>
                  <td v-text="data.nombre"></td>
                  <td v-text="data.fecha"></td>
                  <td>
                    <div v-if="data.pago==2">
                      <span class="badge badge-success">Contado</span>
                    </div>
                    <div v-else-if="data.pago==1">
                      <span class="badge badge-success">Credito</span>
                    </div>
                    <div v-else>
                      <span class="badge badge-success">Diario</span>
                    </div>
                  </td>
                  <!-- <td>{{ data.cantidad }}</td> -->
                  <td>{{ data.descripcion }}</td>
                  <td>{{ data.montoCompra }}</td>
                  <td>
                    <div v-if="data.estado">
                      <span class="badge badge-success">Activo</span>
                    </div>
                    <div v-else>
                      <span class="badge badge-danger">Desactivado</span>
                    </div>
                  </td>
                  <td>
                    <button type="button" @click="imprimir(data.id)" class="btn btn-info btn-sm">
                      <i class="fa fa-print fa-lg"></i>
                    </button>&nbsp;
                    <button
                      type="button"
                      @click="pdfCompra(data.id)"
                      class="btn btn-danger btn-sm"
                    >
                      <i class="fa fa-file-pdf-o"></i>
                    </button> &nbsp;
                    <button
                      type="button"
                      @click="mostrarDetalle('compra','actualizar',data)"
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
        <!-- Aquiii -->
        <template v-else>
          <div :class="'card-body '+ validaciones">
            <div class="form-group row border">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Proveedor</label>
                  <v-select
                    v-model="selectedProveedor"
                    @search="selectProveedor"
                    :options="arrayProveedor"
                    label="nombre"
                    placeholder="Buscar Proveedor..."
                    @input="getDatosProveedor"
                  />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for>Pago</label>
                  <select class="form-control col-md-12" required v-model="pago">
                    <option value readonly>Seleccione</option>
                    <option value="2">Contado</option>
                    <option value="1">Credito</option>
                    <option value="0">Diario</option>
                  </select>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for>
                    Por Concepto De
                    <br />
                  </label>
                  <input
                    type="text"
                    v-model="descripcion"
                    class="form-control"
                    required
                    placeholder="Descripcion..."
                  />
                </div>
              </div>
            </div>
            <div class="form-group row border">
              <div class="col-md-5">
                <div class="form-group">
                  <label>Producto</label>
                  <v-select
                    @search="selectProducto"
                    label="nombre"
                    :options="arrayProducto"
                    placeholder="Buscar Productos..."
                    @input="getDatosProducto"
                  />
                </div>
              </div>
              <div class="col-md-0">
                <div class="form-group">
                  <button
                    @click="abrirModal()"
                    data-toggle="modal"
                    data-target="#ModalLong"
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
                  <label>Cantidad</label>
                  <input type="number" min="0" class="form-control" v-model="cantidad" />
                </div>
              </div>
              <div class="col-md-0">
                <div class="form-group">
                  <label>Unidad</label>
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
                  <th>Precio</th>
                  <th>Precio Total</th>
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
                      @input="compraTotal()"
                      min="0"
                      step="any"
                      v-model="detalle.cantidad"
                      class="form-control"
                      required
                      placeholder="Cantidad..."
                    />
                    <span class="input-group-append">&nbsp;&nbsp;{{ detalle.referencia }}</span>
                  </td>
                  <td>
                    {{ Math.floor(detalle.cantidad/detalle.codigo)}} {{ detalle.unidad }} +
                    {{ (((detalle.cantidad/detalle.codigo) - (Math.floor(detalle.cantidad/detalle.codigo)))*detalle.codigo).toFixed(2) }} {{ detalle.referencia }}
                  </td>
                  <td class="input-group">
                    <input
                      type="number"
                      min="0"
                      @input="compraTotal()"
                      step="any"
                      placeholder="Precio....."
                      v-model="detalle.precio"
                      class="form-control"
                      required
                    />
                    <span class="input-group-append">&nbsp;&nbsp;Bs.</span>
                  </td>
                  <td v-if="((detalle.cantidad/detalle.codigo)*detalle.precio)">
                    <div v-text="((detalle.cantidad/detalle.codigo)*detalle.precio).toFixed(2)"></div>
                  </td>
                  <td v-else>
                    <div>00.00</div>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">Total</td>
                  <td>Cantidad :</td>
                  <td>
                    <input type="number" readonly v-model="cantidadTotal" class="form-control" />
                  </td>
                  <td colspan="2">Monto :</td>
                  <td>
                    <input type="number" readonly v-model="montoCompra" class="form-control" />
                  </td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr>
                  <td colspan="7">No hay Productos Agregados</td>
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
      </div>
    </div>
    <!-- Modal -->
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
            <h5 class="modal-title" id="exampleModalLongTitle" v-text="tituloModal"></h5>
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
      </div>
    </div>
  <!-- </main> -->

</div>
</template>
<script>
import Vue from "vue";
import vSelect from "vue-select";

import "vue-select/dist/vue-select.css";

Vue.component("v-select", vSelect);
export default {
  data() {
    return {
      compra_id: 0,
      idProveedor: 0,
      fecha: "",
      pago: "",
      cantidad: 0,
      montoCompra: 0,
      descripcion: "",
      arrayData: [],
      arrayDetalle: [],
      arrayProducto: [],
      arrayProveedor: [],
      listado: 1,
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 4,
      criterio: "nombre",
      criterioP: "nombre",
      buscarP: "",
      buscar: "",
      proveedor: "",
      idProducto: 0,
      producto: "",
      unidad: "",
      referencia: "",
      codigo: 0,
      selectedProveedor: null,
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
        "compra?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayData = respuesta.compras.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    pdfCompra(id) {
      window.open("compra/pdf/compra_" + id );
    },
    imprimir(id) {
      window.open("compra/imprimir/compra_" + id );
    },
    selectProveedor(search, loading) {
      let me = this;
      loading(true);
      var url = "cuenta/selectCuenta?filtro=" + search;
      axios
        .get(url)
        .then(function(response) {
          let respuesta = response.data;
          q: search;
          me.arrayProveedor = respuesta.cuentas;
          loading(false);
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    getDatosProveedor(val1) {
      let me = this;
      me.loading = true;
      try {
        me.idProveedor = val1.id;
        me.proveedor = val1.nombre;
      } catch (error) {
        me.idProveedor = 0;
        me.proveedor = "";
      }
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
    compraTotal() {
      this.cantidadTotal = 0;
      this.montoCompra = 0;
      for (var i = 0; i < this.arrayDetalle.length; i++) {
        this.montoCompra =
          (this.arrayDetalle[i].cantidad / this.arrayDetalle[i].codigo) *
            this.arrayDetalle[i].precio +
          this.montoCompra;
        this.cantidadTotal =
          this.arrayDetalle[i].cantidad * 1 + this.cantidadTotal;
      }
      this.montoCompra = this.montoCompra.toFixed(2);
      this.cantidadTotal = this.cantidadTotal.toFixed(2);
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
            precio: 0,
            unidad: me.unidad,
            codigo: me.codigo,
            referencia: me.referencia
          });
          me.cantidad = 0;
        }
      }
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
          cantidad: 0,
          precio: 0,
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
        .post("compra/registrar", {
          idProveedor: this.idProveedor,
          pago: this.pago,
          montoCompra: this.montoCompra,
          cantidad: this.cantidadTotal,
          descripcion: this.descripcion,
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
        .put("compra/actualizar", {
          idProveedor: this.idProveedor,
          pago: this.pago,
          montoCompra: this.montoCompra,
          cantidad: this.cantidadTotal,
          descripcion: this.descripcion,
          id: this.compra_id,
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
              .put("compra/desactivar", {
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
              .put("compra/activar", {
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
    validar() {
      if (this.idProveedor == 0) {
        this.mensaje = "Seleccione el Proveedor";
        return true;
      }
      if (this.pago == "") {
        this.mensaje = "Seleccione el Pago";
        return true;
      }
      if (this.descripcion == "") {
        this.mensaje = "Ingrese La Descripcion del Concepto";
        return true;
      }
      if (this.arrayDetalle.length <= 0) {
        this.mensaje = "No tiene Productos en su Detalle";
        return true;
      }
      for (var i = 0; i < this.arrayDetalle.length; i++) {
        if (!this.arrayDetalle[i].cantidad || !this.arrayDetalle[i].precio) {
          this.mensaje =
            "La Cantidad y el Precio en el Detalle, no Puede Ser 0";
          return true;
        }
      }
      return false;
    },
    limpiarRegistro() {
      this.proveedor = "";
      this.idProveedor = 0;
      this.pago = "";
      this.cantidad = 0;
      this.descripcion = "";
      this.montoCompra = 0;
      this.producto = "";
      this.referencia = "";
      this.arrayDetalle = [];
      this.selectedProveedor = null;
      this.mensaje = "";
      this.validaciones = "";
      this.cantidadTotal = 0;
    },
    detalleGeneral(id) {
      this.listado = 0;
      this.tipoAccion = 2;
      let me = this;
      var url = "/compra/listarCompras?id=" + id;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayDetalle = respuesta.detalles;

          me.compra_id = respuesta.ventas[0].id;
          me.proveedor = respuesta.ventas[0].nombre;
          me.idProveedor = respuesta.ventas[0].idProveedor;
          me.pago = respuesta.ventas[0].pago;
          me.descripcion = respuesta.ventas[0].descripcion;
          me.montoCompra = respuesta.ventas[0].montoCompra;
          me.cantidadTotal = respuesta.ventas[0].cantidad;
          me.selectedProveedor = {
            id: me.idProveedor,
            nombre: me.proveedor
          };
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    mostrarDetalle(modelo, accion, data = []) {
      switch (modelo) {
        case "compra": {
          switch (accion) {
            case "registrar": {
              this.listado = 0;
              this.limpiarRegistro();
              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              this.listado = 0;
              this.tipoAccion = 2;
              this.compra_id = data["id"];
              this.proveedor = data["nombre"];
              this.idProveedor = data["idProveedor"];
              this.pago = data["pago"];
              this.descripcion = data["descripcion"];
              this.montoCompra = data["montoCompra"];
              this.cantidadTotal = data["cantidad"];
              this.selectedProveedor = {
                id: this.idProveedor,
                nombre: this.proveedor
              };
              let me = this;
              var url = "compra/listarCompras?id=" + data["id"];
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
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
    },
    abrirModal() {
      this.arrayProducto = [];
      this.modal = 1;
      this.tituloModal = "Selecione uno o varios Productos";
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