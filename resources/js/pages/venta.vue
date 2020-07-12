<template>
  <!-- <main class="main"> -->
    <!-- Breadcrumb-->
    <!-- <ol class="breadcrumb">
      <li class="breadcrumb-item">Home</li>
      <li class="breadcrumb-item">
        <a href="#">Admin</a>
      </li>
      <li class="breadcrumb-item active">Dashboard</li>
       <li class="breadcrumb-menu d-md-down-none">
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
    <div>
    <br>

    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Venta
          <button
            type="button"
            @click="mostrarDetalle('venta','registrar')"
            class="btn btn-secondary"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>

        <template v-if="listado==1">
          <div class="card-body">
            <div class="form-group row">
              <div class="col-md-6">
                <div class="input-group">
                  <select class="form-control col-md-5" v-model="criterio">
                    <option value="nombre">Cliente</option>
                    <option value="descripcion">Descripcion</option>
                  </select>
                  <input
                    type="text"
                    v-model="buscar"
                    @keyup.enter="listar(1,buscar,criterio)"
                    class="form-control"
                    placeholder="Buscar Producto"
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
                  <th>Cliente</th>
                  <th>Fecha</th>
                  <th>Pago</th>
                  <!-- <th>Cantidad</th> -->
                  <th>Ventas</th>
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
                    <div v-else-if="data.pago">
                      <span class="badge badge-success">Credito</span>
                    </div>
                    <div v-else>
                      <span class="badge badge-success">Diario</span>
                    </div>
                  </td>
                  <!-- <td v-text="data.cantidad"></td> -->
                  <td>
                    <div v-if="data.idFormula">
                      <span class="badge badge-success">Granjas</span>
                    </div>
                    <div v-else>
                      <span class="badge badge-warning">Publica</span>
                    </div>
                  </td>
                  <td v-text="data.descripcion"></td>
                  <td v-text="data.montoVenta"></td>
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
                      @click="pdfVenta(data.id)"
                      class="btn btn-danger btn-sm"
                    >
                      <i class="fa fa-file-pdf-o"></i>
                    </button> &nbsp;
                    <button
                      type="button"
                      @click="mostrarDetalle('venta','actualizar',data)"
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
        <template v-else-if="listado==0">
          <div :class="'card-body '+validaciones">
            <div class="form-group row border">
              <div class="col-md-5">
                <div class="form-group">
                  <label>Cliente</label>
                  <v-select
                    v-model="selectedCliente"
                    @search="selectCliente"
                    :options="arrayCliente"
                    label="nombre"
                    placeholder="Buscar Cliente..."
                    @input="getDatosCliente"
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
              <div class="col-md-3">
                <div class="form-group">
                  <label for>Ventas</label>
                  <select class="form-control col-md-12" v-model="tipo" @change="renovarDetalle()">
                    <option value="0">Publicas</option>
                    <option value="1">Granjas</option>
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
            <template v-if="tipo==0">
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
                <div class="col-md-2">
                  <div class="form-group">
                    <label for></label>
                    <button
                      @click="abrirModal()"
                      data-toggle="modal"
                      data-target="#ModalLong"
                      class="btn btn-success form-control"
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
                    <input type="number" min="0" step="any" class="form-control" v-model="cantidad" />
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
                    <label for></label>
                    <button @click="agregarDetalle()" class="btn btn-success form-control">
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
                    <th>Unidad</th>
                    <th>Precio</th>
                    <th>Descripcion</th>
                    <th>Precio Total</th>
                  </tr>
                </thead>
                <tbody v-if="(arrayDetalle.length)">
                  <tr v-for="(detalle,index) in arrayDetalle" :key="index">
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
                        v-model="detalle.cantidad"
                        @input="ventaTotal()"
                        class="form-control"
                        required
                        placeholder="Cantidad...."
                      />
                      <span class="input-group-append">&nbsp;&nbsp;{{ detalle.referencia }}</span>
                    </td>
                    <td>
                      {{ Math.floor(detalle.cantidad/detalle.codigo)}} {{ detalle.unidad }} +
                      {{ (((detalle.cantidad/detalle.codigo) - (Math.floor(detalle.cantidad/detalle.codigo)))*detalle.codigo).toFixed(2) }} {{ detalle.referencia }}
                    </td>
                    <td>
                      <input
                        type="number"
                        min="0"
                        step="any"
                        v-model="detalle.precio"
                        @input="ventaTotal()"
                        class="form-control"
                        required
                        placeholder="Precio....."
                      />
                    </td>
                    <td>
                      <input
                        type="text"
                        v-model="detalle.descripcionD"
                        class="form-control"
                        required
                        placeholder="Descripcion...."
                      />
                    </td>
                    <td v-if="((detalle.cantidad/detalle.codigo)*detalle.precio)">
                      <div v-text="((detalle.cantidad/detalle.codigo)*detalle.precio).toFixed(2)"></div>
                    </td>
                    <td v-else>
                      <div>00.00</div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="7">Total</td>
                    <td>
                      <input type="number" readonly v-model="montoVenta" class="form-control" />
                    </td>
                  </tr>
                </tbody>
                <tbody v-else>
                  <tr>
                    <td colspan="8">No hay Productos Agregados</td>
                  </tr>
                </tbody>
              </table>
            </template>
            <template v-if="tipo>=1">
              <div class="form-group row border">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>
                      Formula
                      <span></span>
                    </label>
                    <v-select
                      v-model="selectedFormula"
                      @search="selectFormula"
                      label="nombre"
                      :options="arrayFormula"
                      placeholder="Buscar Formula..."
                      @input="getDatosFormula"
                    />
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Cantidad</label>
                    <input
                      type="number"
                      min="0"
                      step="any"
                      required
                      @input="ventaTotalFormula()"
                      class="form-control"
                      v-model="cantidad"
                      placeholder="Cantidad...."
                    />
                  </div>
                </div>
                <div class="col-md-0">
                  <div class="form-group">
                    <br />
                    <br />
                    <label for>Ton.</label>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <button
                      type="button"
                      @click="mostrarFormula(idFormula)"
                      class="btn btn-success form-control btnagregar"
                    >
                      <i class="icon-plus">Agregar</i>
                    </button>
                  </div>
                </div>
              </div>
              <table class="table table-responsive-sm table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>N°</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Cantidad + Unidad</th>
                    <th>Precio</th>
                    <!-- <th>Descripcion</th> -->
                    <th>Precio Total</th>
                  </tr>
                </thead>
                <tbody v-if="(arrayDetalle.length)">
                  <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.idProducto">
                    <td>
                      <b>{{ (index+1) }}</b>
                    </td>
                    <td v-text="detalle.producto"></td>
                    <td>{{ (detalle.cantidad*cantidad).toFixed(2) }} {{ detalle.referencia }}</td>
                    <td>
                      {{ Math.floor((detalle.cantidad*cantidad)/detalle.codigo)}} {{ detalle.unidad }} +
                      {{ ((((detalle.cantidad*cantidad)/detalle.codigo) - (Math.floor((detalle.cantidad*cantidad)/detalle.codigo)))*detalle.codigo).toFixed(2) }} {{ detalle.referencia }}
                    </td>
                    <td>
                      <input
                        type="number"
                        min="0"
                        step="any"
                        v-model="detalle.precio"
                        @input="ventaTotalFormula()"
                        class="form-control"
                        required
                        placeholder="Precio..."
                      />
                    </td>
                    <!-- <td><input type="text"  v-model="detalle.descripcionD" class="form-control" placeholder="Descripcion"></td> -->
                    <td v-if="(((detalle.cantidad*cantidad)/detalle.codigo)*detalle.precio)">
                      <div
                        v-text="(((detalle.cantidad*cantidad)/detalle.codigo)*detalle.precio).toFixed(2)"
                      ></div>
                    </td>
                    <td v-else>
                      <div>00.00</div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="5">Total :</td>
                    <td>
                      <input type="number" readonly v-model="montoVenta" class="form-control" />
                    </td>
                  </tr>
                </tbody>
                <tbody v-else>
                  <tr>
                    <td colspan="6">No hay Productos Agregados</td>
                  </tr>
                </tbody>
              </table>
            </template>
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
                  <th>Codigo</th>
                  <th>Unidad</th>
                  <th>Estado</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="producto in arrayProducto" :key="producto.id">
                  <td>
                    <button
                      type="button"
                      @click="agregarDetalleModal(producto)"
                      class="btn btn-success btn-sm"
                    >
                      <i class="icon-check"></i>
                    </button>
                  </td>
                  <td v-text="producto.nombre"></td>
                  <td v-text="producto.stock"></td>
                  <td v-text="producto.codigo"></td>
                  <td v-text="producto.unidad"></td>
                  <td>
                    <div v-if="producto.estado">
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
    </div>
  <!-- </main> -->
</template>
<script>
import Vue from "vue";
import vSelect from "vue-select";

import "vue-select/dist/vue-select.css";

Vue.component("v-select", vSelect);
export default {
  data() {
    return {
      venta_id: 0,
      idCliente: 0,
      fecha: "",
      idFormula: 0,
      pago: "",
      cantidad: 0,
      montoVenta: 0,
      descripcion: "",
      arrayData: [],
      arrayVentaT: [],
      arrayDetalle: [],
      arrayProducto: [],
      arrayFormula: [],
      arrayCliente: [],
      listado: 1,
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorMostrar: 0,
      errorMostrarMsj: [],
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
      cliente: "",
      idProducto: 0,
      producto: "",
      unidad: "",
      formula: "",
      tipo: 0,
      modeloDetalle: 0,
      arrayDetalleFormula: [],
      precio: 0,
      codigo: 0,
      selectedCliente: null,
      selectedFormula: null,
      validaciones: "",
      mensaje: ""
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
        "venta?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayData = respuesta.ventas.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    pdfVenta(id) {
      window.open("venta/pdf/venta_" + id);
    },
    imprimir(id) {
      window.open("venta/imprimir/venta_" + id);
    },
    selectCliente(search, loading) {
      let me = this;
      loading(true);
      var url = "cuenta/selectCuenta?filtro=" + search;
      axios
        .get(url)
        .then(function(response) {
          let respuesta = response.data;
          q: search;
          me.arrayCliente = respuesta.cuentas;
          loading(false);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    getDatosCliente(val1) {
      let me = this;
      me.loading = true;
      try {
        me.idCliente = val1.id;
        me.cliente = val1.nombre;
      } catch (error) {
        me.idCliente = 0;
        me.cliente = "";
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
    selectFormula(search, loading) {
      let me = this;
      loading(true);
      var url = "formula/selectFormula?filtro=" + search;
      axios
        .get(url)
        .then(function(response) {
          let respuesta = response.data;
          q: search;
          me.arrayFormula = respuesta.formulas;
          loading(false);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    mostrarFormula(id) {
      let me = this;
      var url = "formula/listarFormula?id=" + id;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayDetalle = respuesta.detalles;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    getDatosFormula(val2) {
      let me = this;
      me.loading = true;
      try {
        me.idFormula = val2.id;
        me.formula = val2.nombre;
      } catch (error) {
        me.idFormula = 0;
        me.formula = "";
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
      for (let i = 0; i < this.arrayDetalle.length; i++) {
        if (this.arrayDetalle[i].idProducto == id) {
          return true;
        }
      }
      return false;
    },
    eliminarDetalle(index) {
      let me = this;
      me.arrayDetalle.splice(index, 1);
    },
    ventaTotal() {
      this.montoVenta = 0;
      for (var i = 0; i < this.arrayDetalle.length; i++) {
        if (this.arrayDetalle[i].precio) {
          this.montoVenta =
            (this.arrayDetalle[i].cantidad / this.arrayDetalle[i].codigo) *
              this.arrayDetalle[i].precio +
            this.montoVenta;
        }
      }
      this.montoVenta = this.montoVenta.toFixed(2);
    },
    ventaTotalFormula() {
      this.montoVenta = 0;
      for (var i = 0; i < this.arrayDetalle.length; i++) {
        if (this.arrayDetalle[i].precio) {
          this.montoVenta =
            ((this.arrayDetalle[i].cantidad * this.cantidad) /
              this.arrayDetalle[i].codigo) *
              this.arrayDetalle[i].precio +
            this.montoVenta;
        }
      }
      this.montoVenta = this.montoVenta.toFixed(2);
    },
    agregarDetalle() {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });
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
          swalWithBootstrapButtons
            .fire({
              // position: "center",
              title: "Desea Volver a Agregarlo el Producto",
              type: "warning",
              showCancelButton: true,
              confirmButtonText: "Si, Agregar!",
              cancelButtonText: "No, Cancelar!",
              reverseButtons: true
              // showConfirmButton: true,
              // timer: 1000
            })
            .then(result => {
              if (result.value) {
                me.arrayDetalle.push({
                  idProducto: me.idProducto,
                  producto: me.producto,
                  cantidad: me.cantidad,
                  codigo: me.codigo,
                  precio: 1,
                  unidad: me.unidad,
                  referencia: me.referencia,
                  descripcionD: ""
                });
                me.cantidad = 0;
                Swal.fire({
                  position: "center",
                  type: "success",
                  title: "Agregado Exitosamente",
                  showConfirmButton: false,
                  timer: 1000
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
        } else {
          me.arrayDetalle.push({
            idProducto: me.idProducto,
            producto: me.producto,
            cantidad: me.cantidad,
            codigo: me.codigo,
            precio: 1,
            unidad: me.unidad,
            referencia: me.referencia,
            descripcionD: ""
          });
          me.cantidad = 0;
        }
      }
    },
    renovarDetalle() {
      let me = this;
      me.arrayDetalle = [];
      me.montoVenta = 0;
      me.formula = "";
      me.producto = "";
      (me.idFormula = 0), (me.cantidad = 0);
    },
    agregarDetalleModal(data = []) {
       const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });
      let me = this;
      if (me.encuentra(data["id"])) {
        swalWithBootstrapButtons
          .fire({
            // position: "center",
            title: "Desea Volver a Agregarlo el Producto",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Si, Agregar!",
            cancelButtonText: "No, Cancelar!",
            reverseButtons: true
            // showConfirmButton: true,
            // timer: 1000
          })
          .then(result => {
            if (result.value) {
              me.arrayDetalle.push({
                idProducto: data["id"],
                producto: data["nombre"],
                cantidad: 1,
                codigo: data["codigo"],
                precio: 1,
                unidad: data["unidad"],
                referencia: data["referencia"],
                descripcionD: ""
              });
              Swal.fire({
                position: "center",
                type: "success",
                title: "Agregado Exitosamente",
                showConfirmButton: false,
                timer: 1000
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
      } else {
        me.arrayDetalle.push({
          idProducto: data["id"],
          producto: data["nombre"],
          cantidad: 1,
          codigo: data["codigo"],
          precio: 1,
          unidad: data["unidad"],
          referencia: data["referencia"],
          descripcionD: ""
        });
      }
    },
    listarProducto(buscar, criterio) {
      let me = this;
      var url ="producto/listarProducto?buscar=" + buscar + "&criterio=" + criterio;
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
        .post("venta/registrar", {
          idCliente: this.idCliente,
          idFormula: this.idFormula,
          pago: this.pago,
          cantidad: this.cantidad,
          montoVenta: this.montoVenta,
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
        .put("venta/actualizar", {
          idCliente: this.idCliente,
          idFormula: this.idFormula,
          pago: this.pago,
          cantidad: this.cantidad,
          descripcion: this.descripcion,
          montoVenta: this.montoVenta,
          id: this.venta_id,
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
              .put("venta/desactivar", {
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
          title: "Estas Seguro de Activar el Registro?",
          text: "Si Activa no estara en la Lista!",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Si, Activar!",
          cancelButtonText: "No, Cancelar!",
          reverseButtons: true
        })
        .then(result => {
          if (result.value) {
            
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            
          }
        });
    },
    validar() {
      if (!this.idCliente) {
        this.mensaje = "Seleccione el Cliente";
        return true;
      }
      if (this.pago == "") {
        this.mensaje = "Sleecione el Pago";
        return true;
      }
      if (!this.descripcion) {
        this.mensaje = "Ingrese La Descripcion del Concepto";
        return true;
      }
      if (this.tipo == 1) {
        if (!this.idFormula) {
          this.mensaje = "Seleccione la Formula";
          return true;
        }
        if (!this.cantidad) {
          this.mensaje = "Ingrese la Cantidad Para la Formula";
          return true;
        }
      }
      if (this.arrayDetalle.length <= 0) {
        this.mensaje = "No tiene Productos en su Detalle";
        return true;
      }
      for (var i = 0; i < this.arrayDetalle.length; i++) {
        if (this.tipo == 0) {
          if (
            !this.arrayDetalle[i].cantidad ||
            !this.arrayDetalle[i].precio ||
            !this.arrayDetalle[i].descripcionD
          ) {
            this.mensaje =
              "La Cantidad o el Precio, Descripcion no tienen Valor en el  en el Detalle";
            return true;
          }
        } else {
          if (!this.arrayDetalle[i].cantidad || !this.arrayDetalle[i].precio) {
            this.mensaje =
              "La Cantidad y el Precio en el Detalle, no Puede Ser 0";
            return true;
          }
        }
      }
      return false;
    },
    limpiarRegistro() {
      this.cliente = "";
      this.idCliente = 0;
      this.pago = "";
      this.cantidad = 0;
      this.idFormula = 0;
      this.formula = "";
      this.descripcion = "";
      this.idProducto = 0;
      this.producto = "";
      this.tipo = 0;
      this.montoVenta = 0;
      this.arrayDetalle = [];
      this.arrayCliente = [];
      this.arrayProducto = [];
      this.arrayFormula = [];
      this.selectedCliente = null;
      this.mensaje = "";
      this.validaciones = "";
    },
    mostrarDetalle(modelo, accion, data = []) {
      switch (modelo) {
        case "venta": {
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
              this.venta_id = data["id"];
              this.cliente = data["nombre"];
              this.idCliente = data["idCliente"];
              this.tipo = data["idFormula"];
              this.idFormula = data["idFormula"];
              this.pago = data["pago"];
              this.cantidad = data["cantidad"];
              this.montoVenta = data["montoVenta"];
              this.descripcion = data["descripcion"];
              this.selectedCliente = {
                id: this.idCliente,
                nombre: this.cliente
              };
              if(this.tipo>0)
              {
                this.tipo=1;
              }
              let me = this;
              var url ="venta/listarVentas?id=" +data["id"] +"&idFormula=" +me.idFormula;
              axios.get(url)
                .then(function(response) {
                  var respuesta = response.data;
                  me.arrayDetalle = respuesta.detalles;
                  if (me.idFormula > 0) {
                    me.selectedFormula = {
                      id: respuesta.formula[0].id,
                      nombre: respuesta.formula[0].nombre
                    };
                  } else {
                    me.selectedFormula = null;
                  }
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
      this.limpiarRegistro();
      this.listar(1, "", "nombre");
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
    },
    abrirModal() {
      this.arrayProducto = [];
      this.arrayFormulaProducto = [];
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

