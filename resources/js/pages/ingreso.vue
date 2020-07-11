<template>
  <!-- <main class="main"> -->
    <div>
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
          <i class="fa fa-align-justify"></i> Ingreso
          <button
            type="button"
            @click="mostrarDetalle('ingreso','registrar')"
            class="btn btn-secondary"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <!---Listado -->
        <template v-if="listado==1">
          <div class="card-body">
            <div class="form-group row">
              <div class="col-md-6">
                <div class="input-group">
                  <select class="form-control col-md-5" v-model="criterio">
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
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Factura</th>
                    <th>N° Reg.</th>
                    <th>Fecha</th>
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
                    <td v-text="data.fecha"></td>
                    <td v-text="data.descripcion"></td>
                    <td v-text="data.monto"></td>
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
                        @click="pdfIngreso(data.id)"
                        class="btn btn-danger btn-sm"
                      >
                        <i class="fa fa-file-pdf-o"></i>
                      </button> &nbsp;
                      <button
                        type="button"
                        @click="mostrarDetalle('ingreso','actualizar',data)"
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

        <template v-else-if="listado==0">
          <div :class="'card-body '+validaciones">
            <div class="form-group row border">
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
                    placeholder="Descripcion...."
                  />
                </div>
              </div>
            </div>
            <div class="form-group row border">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Cuenta</label>
                  <v-select
                    @search="selectCuenta"
                    label="nombre"
                    :options="arrayCuenta"
                    placeholder="Buscar Cuenta..."
                    @input="getDatosCuenta"
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
                  <label for>Debe</label>
                  <input type="number" min="0" class="form-control" v-model="debe" />
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label for>Haber</label>
                  <input type="number" min="0" class="form-control" v-model="haber" />
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
                      <th>Quitar</th>
                      <th>Codigo</th>
                      <th>Cuenta</th>
                      <th>Debe</th>
                      <th>Haber</th>
                      <th>Descripcion</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody v-if="(arrayDetalle.length)">
                    <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.idCuenta">
                      <td>
                        <button
                          @click="eliminarDetalle(index)"
                          type="button"
                          class="btn btn-danger btn-sm"
                        >
                          <i class="icon-close"></i>
                        </button>
                      </td>
                      <td>
                        <span class="badge badge-success" v-text="detalle.codigo"></span>
                      </td>

                      <td v-text="detalle.cuenta"></td>
                      <td>
                        <input
                          type="number"
                          min="0"
                          step="any"
                          v-model="detalle.debe"
                          class="form-control"
                          @input="sumarDebeHaber()"
                        />
                      </td>
                      <td>
                        <input
                          type="number"
                          min="0"
                          step="any"
                          v-model="detalle.haber"
                          class="form-control"
                          @input="sumarDebeHaber()"
                        />
                      </td>
                      <td>
                        <input
                          type="text"
                          v-model="detalle.descripcionD"
                          required
                          class="form-control"
                          placeholder="Descripcion"
                        />
                      </td>
                      <td>
                        <div
                          v-if="detalle.debe-detalle.haber==detalle.debe || detalle.haber-detalle.debe==detalle.haber"
                        >
                          <span class="badge badge-success">OK</span>
                        </div>
                        <div v-else>
                          <span class="badge badge-danger">ERROR</span>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3">Total</td>
                      <td>
                        <input readonly type="number" v-model="debeTotal" class="form-control" />
                      </td>
                      <td>
                        <input readonly type="number" v-model="haberTotal" class="form-control" />
                      </td>
                      <td>
                        <div v-if="debeTotal-haberTotal==0">
                          <span class="badge badge-success">Asiento Correcto</span>
                        </div>
                        <div v-else>
                          <span class="badge badge-danger">Asiento Incorrecto</span>
                        </div>
                      </td>
                      <td>
                        <div v-if="debeTotal-haberTotal==0">
                          <span class="badge badge-success">OK</span>
                        </div>
                        <div v-else>
                          <span class="badge badge-danger">ERROR</span>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="6">No hay Cuentas Agregadas</td>
                    </tr>
                  </tbody>
                </table>

            <!---listado formula-->
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
                    @keyup.enter="listarCuenta(buscarP)"
                    class="form-control"
                    placeholder="Buscar Producto"
                  />
                  <span class="input-group-append">
                    <button type="submit" @click="listarCuenta(buscarP)" class="btn btn-primary">
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
                    <th>Opciones</th>
                    <th>Nombre</th>
                    <th>Codigo</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="data in arrayCuenta" :key="data.id">
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
                    <td>{{ data.nivel1+"."+data.nivel2+"."+data.nivel3 +"."+data.nivel4 +"."+data.nivel5 }}</td>
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
      ingreso_id: 0,
      codigo: "",
      cuenta: "",
      idCuenta: 0,
      fecha: "",
      monto: 0,
      descripcion: "",
      descripcionD: "",
      arrayData: [],
      arrayIngresoT: [],
      arrayDetalle: [],
      arrayCuenta: [],
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
      criterio: "descripcion",
      criterioP: "nombre",
      buscarP: "",
      buscar: "",
      debe: 0,
      haber: 0,
      debeTotal: 0,
      haberTotal: 0,
      mensaje: "",
      validaciones: ""
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
        "ingreso?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayData = respuesta.ingresos.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    imprimir(id) {
      window.open("ingreso/imprimir/ingreso_" + id );
    },
    pdfIngreso(id) {
      window.open("ingreso/pdf/ingreso_" + id );
    },
    selectCuenta(search, loading) {
      let me = this;
      loading(true);
      var url = "cuenta/selectCuenta?filtro=" + search;
      axios
        .get(url)
        .then(function(response) {
          let respuesta = response.data;
          q: search;
          me.arrayCuenta = respuesta.cuentas;
          loading(false);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    getDatosCuenta(val1) {
      let me = this;
      me.loading = true;
      try {
        me.idCuenta = val1.id;
        me.cuenta = val1.nombre;
        me.codigo =
          val1.nivel1 +
          "." +
          val1.nivel2 +
          "." +
          val1.nivel3 +
          "." +
          val1.nivel4 +"."
          +val1.nivel5;
      } catch (error) {
        me.idCuenta = 0;
        me.cuenta = "";
        me.codigo = "";
      }
    },
    sumarDebeHaber() {
      let me = this;
      me.debeTotal = 0;
      me.haberTotal = 0;
      for (var i = 0; i < this.arrayDetalle.length; i++) {
        me.debeTotal = me.arrayDetalle[i].debe * 1 + me.debeTotal;
        me.haberTotal = me.arrayDetalle[i].haber * 1 + me.haberTotal;
      }
    },
    verIngreso(id) {
      let me = this;
      me.listado = 2;
      var arrayIngresoT = [];
      // var arrayDetalle = [];
      var url = "ingreso/obtenerIngreso?id=" + id;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          arrayIngresoT = respuesta.ingreso;
          me.ingreso_id = arrayIngresoT[0]["id"];
          me.cuenta = arrayIngresoT[0]["cliente"];
          me.fecha = arrayIngresoT[0]["fecha"];
          me.descripcion = arrayIngresoT[0]["descripcion"];
          me.monto = arrayIngresoT[0]["monto"];
        })
        .catch(function(error) {
          console.log(error);
        });
      //obtener los Datos de los Detalles
      var urld = "ingreso/obtenerDetalles?id=" + id;
      axios
        .get(urld)
        .then(function(response) {
          console.log(response);
          var respuesta = response.data;
          me.arrayDetalle = respuesta.detalles;
        })
        .catch(function(error) {
          console.log(error);
        });
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
        if (this.arrayDetalle[i].idCuenta == id) {
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
      if (me.idCuenta == 0) {
        Swal.fire({
          position: "center",
          title: "Error !!",
          type: "error",
          showConfirmButton: false,
          timer: 1000
        });
      } else {
        if (me.encuentra(me.idCuenta)) {
          Swal.fire({
            position: "center",
            title: "Se Encuentra Agregado",
            type: "error",
            showConfirmButton: false,
            timer: 1000
          });
        } else {
          me.arrayDetalle.push({
            idCuenta: me.idCuenta,
            codigo: me.codigo,
            cuenta: me.cuenta,
            debe: me.debe,
            haber: me.haber,
            descripcionD: ""
          });
          this.debe = 0;
          this.haber = 0;
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
          idCuenta: data["id"],
          cuenta: data["nombre"],
          codigo:
            data["nivel1"] +
            "." +
            data["nivel2"] +
            "." +
            data["nivel3"] +
            "." +
            data["nivel4"]+
            "." +
            data["nivel5"],
          debe: 0,
          haber: 0,
          descripcionD: ""
        });
      }
    },
    listarCuenta(buscar) {
      let me = this;
      var url = "cuenta/listarCuenta?filtro=" + buscar;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayCuenta = respuesta.cuentas;
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
        .post("ingreso/registrar", {
          descripcion: this.descripcion,
          monto: this.debeTotal,
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
        .put("ingreso/actualizar", {
          descripcion: this.descripcion,
          monto: this.debeTotal,
          data: this.arrayDetalle,
          id: this.ingreso_id
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
              .put("ingreso/desactivar", {
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
            let me = this;

            axios
              .put("ingreso/activar", {
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
      if (!this.descripcion) {
        this.mensaje = "Describa El Concepto";
        return true;
      }
      if (!this.arrayDetalle.length) {
        this.mensaje = "No tiene Cuentas en Su Detalle";
        return true;
      }
      if (this.haberTotal != this.debeTotal) {
        this.mensaje = "El debe y el haber no coinciden";
        return true;
      }
      if (this.haberTotal == 0 && this.debeTotal == 0) {
        this.mensaje = "El Debe y el Haber no debe ser Cero";
        return true;
      }
      for (var i = 0; i < this.arrayDetalle.length; i++) {
        if (!this.arrayDetalle[i].descripcionD) {
          this.mensaje = "Le Falta la Descripcion de la Cuenta!!";
          return true;
        }
      }
      return false;
    },
    limpiarRegistro() {
      this.cuenta = "";
      this.idCuenta = 0;
      this.codigo = "";
      this.debe = 0;
      this.haber = 0;
      this.descripcion = "";
      this.haberTotal = 0;
      this.debeTotal = 0;
      this.arrayCuenta = [];
      this.arrayDetalle = [];
    },
    mostrarDetalle(modelo, accion, data = []) {
      switch (modelo) {
        case "ingreso": {
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
              this.ingreso_id = data["id"];
              this.cuenta = data["cuenta"];
              this.monto = data["monto"];
              this.haberTotal = data["monto"];
              this.debeTotal = data["monto"];
              this.descripcion = data["descripcion"];

              let me = this;
              var url = "ingreso/listarIngreso?id=" + data["id"];
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
      this.arrayCuenta = [];
      this.modal = 1;
      this.tituloModal = "Selecione una o varias Cuentas";
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

