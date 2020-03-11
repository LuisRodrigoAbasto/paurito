<template>
  <main class="main">
    <!-- Breadcrumb -->
    <!-- <ol class="breadcrumb">
      <li class="breadcrumb-item">Home</li>
      <li class="breadcrumb-item">
        <a href="#">Admin</a>
      </li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol> -->
    <br>
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Cuentas
          <button
            type="button"
            data-toggle="modal"
            data-target="#ModalLong"
            @click="abrirModal('cuenta','registrar')"
            class="btn btn-secondary"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
          <button type="button" @click="cargarPdf()" class="btn btn-danger">
              <i class="fa fa-file-pdf-o"></i>&nbsp;PDF
          </button>

          <button type="button" @click="imprimir()" class="btn btn-info">
              <i class="fa fa-print fa-lg"></i>
          </button>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-6">
              <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio">
                  <option value="nombre">Nombre</option>
                </select>
                <input
                  type="text"
                  v-model="buscar"
                  @keyup.enter="listar(1,buscar,criterio)"
                  class="form-control"
                  placeholder="Buscar Cuenta"
                />
                <span class="input-group-append">
                  <button type="submit" @click="listar(1,buscar,criterio)" class="btn btn-primary">
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
                  <!-- <th>N°</th> -->
                  <th>Codigo</th>
                  <th>Nombre</th>
                  <th>Estado</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="data in arrayData" :key="data.id">
                  <td v-if="data.nivel==1">
                    <u>
                      <b>{{ data.nivel1}}</b>
                    </u>
                  </td>
                  <td v-if="data.nivel==2">
                    <b>{{ data.nivel1+'.'+data.nivel2}}</b>
                  </td>
                  <td v-if="data.nivel==3">
                    <u>{{ data.nivel1+'.'+data.nivel2+'.'+data.nivel3 }}</u>
                  </td>
                  <td
                    v-if="data.nivel==4"
                  >{{ data.nivel1+'.'+data.nivel2+'.'+data.nivel3+'.'+data.nivel4 }}</td>
                  <td
                    v-if="data.nivel==5"
                  >{{ data.nivel1+'.'+data.nivel2+'.'+data.nivel3+'.'+data.nivel4+'.'+data.nivel5 }}</td>
                  <td v-if="data.nivel==1">
                    <font style="text-transform: uppercase;">
                      <u>
                        <b>{{ data.nombre}}</b>
                      </u>
                    </font>
                  </td>
                  <td v-if="data.nivel==2">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <font
                      style="text-transform: uppercase;"
                    >
                      <b>{{ data.nombre}}</b>
                    </font>
                    <!-- <label class="form-control-label" for="text-input" v-text="data.nombre"></label> -->
                  </td>
                  <td v-if="data.nivel==3">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <font
                      style="text-transform: uppercase;"
                    >
                      <u>{{ data.nombre }}</u>
                    </font>
                  </td>
                  <td v-if="data.nivel==4">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <font
                      style="text-transform: uppercase;"
                    >{{ data.nombre}}</font>
                  </td>
                  <td v-if="data.nivel==5">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{ data.nombre }}
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
                      @click="abrirModal('cuenta','actualizar',data)"
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
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div
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
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div :class="'modal-body '+validaciones">
            <form action method="POST" enctype="multipart/form-data" class="form-horizontal">
              <div class="form-group row border">
                <!-- <label class="col-md-3 form-control-label" for="text-input">Nivel1</label> -->

                <div class="col-md-2">
                  <div class="form-group">
                    <label class="col-md-3 form-control-label" for="text-input">
                      <span class="badge badge-success">
                        <i class="icon-check"></i> Nivel 1
                      </span>
                    </label>
                    <input type="text" readonly class="form-control" value v-model="nivel1" />
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <label class="col-md-3 form-control-label" for="text-input">
                      <span class="badge badge-success">
                        <i class="icon-check"></i> Nivel 2
                      </span>
                    </label>
                    <input type="text" readonly class="form-control" value v-model="nivel2" />
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <label class="col-md-3 form-control-label" for="text-input">
                      <span class="badge badge-success">
                        <i class="icon-check"></i> Nivel 3
                      </span>
                    </label>
                    <input type="text" readonly class="form-control" value v-model="nivel3" />
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <label class="col-md-3 form-control-label" for="text-input">
                      <span class="badge badge-success">
                        <i class="icon-check"></i> Nivel 4
                      </span>
                    </label>
                    <input type="text" readonly class="form-control" value v-model="nivel4" />
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <label class="col-md-3 form-control-label" for="text-input">
                      <span class="badge badge-success">
                        <i class="icon-check"></i> Nivel 5
                      </span>
                    </label>
                    <input type="text" readonly class="form-control" value v-model="nivel5" />
                  </div>
                </div>
              </div>
              <template v-if="editar==false">
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input">Clase</label>
                  <div class="col-md-9">
                    <v-select
                      v-model="selectedCuenta"
                      @search="selectCuenta"
                      label="nombre"
                      :options="arrayCuenta"
                      placeholder="Buscar Cuenta..."
                      @input="getDatosCuenta"
                    />
                  </div>
                </div>
              </template>

              <template>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                  <div class="col-md-9">
                    <input
                      type="text"
                      v-model="nombre"
                      class="form-control"
                      required
                      placeholder="Nombre............."
                      @keyup.enter="teclaRapida()"
                    />
                  </div>
                </div>
              </template>

              <template v-if="nivel==5">
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                  <div class="col-md-9">
                    <input
                      type="text"
                      v-model="telefono"
                      class="form-control"
                      placeholder="Telefono.............."
                      @keyup.enter="teclaRapida()"
                    />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input">Empresa</label>
                  <div class="col-md-9">
                    <input
                      type="text"
                      v-model="empresa"
                      class="form-control"
                      placeholder="Empresa............"
                      @keyup.enter="teclaRapida()"
                    />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input">Direccion</label>
                  <div class="col-md-9">
                    <input
                      type="text"
                      v-model="direccion"
                      class="form-control"
                      placeholder="Direccion............"
                      @keyup.enter="teclaRapida()"
                    />
                  </div>
                </div>
              </template>
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
              v-if="nivel5Accion==1"
              class="btn btn-primary"
              @click="registrar()"
            >Guardar</button>
            <button
              type="button"
              v-if="nivel5Accion==2"
              class="btn btn-primary"
              @click="actualizar()"
            >Actualizar</button>
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
      cuenta: "",
      nivel: 1,
      idCuenta: 0,
      cuenta_id: 0,
      nombre: "",
      telefono: "",
      empresa: "",
      direccion: "",
      arrayData: [],
      arrayCuenta: [],
      nivel5: 0,
      nivel1: 0,
      nivel2: 0,
      nivel3: 0,
      nivel4: 0,
      modal: 0,
      editar: false,
      tituloModal: "",
      nivel5Accion: 0,
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
      buscar: "",
      selectedCuenta: null,
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
        "cuenta?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayData = respuesta.cuentas.data;
          me.pagination = respuesta.pagination;
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
    cargarPdf() {
      window.open("cuenta/listarPdf");
    },
    imprimir()
    {
      window.open("cuenta/imprimir");
    },
    selectCuenta(search, loading) {
      let me = this;
      loading(true);
      var url = "cuenta/cuenta?filtro=" + search;
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
        me.nivel = val1.nivel+1;
        me.nivel5 = val1.nivel5;
        me.nivel1 = val1.nivel1;
        me.nivel2 = val1.nivel2;
        me.nivel3 = val1.nivel3;
        me.nivel4 = val1.nivel4;
        me.selectedCuenta = {
          id: me.idCuenta,
          nombre: me.cuenta,
          nivel: me.nivel,
          nivel1: me.nivel1,
          nivel2: me.nivel2,
          nivel3: me.nivel3,
          nivel4: me.nivel4,
          nivel5: me.nivel5,
        };
        me.cambiarNivel();
      } catch (error) {
        me.idCuenta = 0;
        me.cuenta = "";
        me.nivel5 = 0;
        me.nivel1 = 0;
        me.nivel2 = 0;
        me.nivel3 = 0;
        me.nivel4 = 0;
        me.nivel = 1;
        me.selectedCuenta = null;
        me.cambiarNivel();
      }
    },
    cambiarNivel() {
      let me = this;
      var url =
        "cuenta/buscarCuenta?idCuenta="+me.idCuenta;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          if (me.nivel == 1) {
            me.nivel1 = respuesta.cuentas;
          } else {
            if (me.nivel == 2) {
              me.nivel2 = respuesta.cuentas;
            } else {
              if (me.nivel == 3) {
                me.nivel3 = respuesta.cuentas;
              } else {
                if (me.nivel == 4) {
                  me.nivel4 = respuesta.cuentas;
                } else {
                  if (me.nivel == 5) {
                    me.nivel5 = respuesta.cuentas;
                  }
                }
              }
            }
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    registrar() {
      this.cambiarNivel();
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
        .post("cuenta/registrar", {
          idCuenta:me.idCuenta,
          nombre: me.nombre,
          direccion: me.direccion,
          empresa: me.empresa,
          telefono: me.telefono,
          nivel: me.nivel,
          nivel1: me.nivel1,
          nivel2: me.nivel2,
          nivel3: me.nivel3,
          nivel4: me.nivel4,
          nivel5: me.nivel5
        })
        .then(function(response) {
          $("#ModalLong").modal("hide");
          me.cerrarModal();
          me.listar(1, "", "nombre");
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    actualizar() {
      this.cambiarNivel();
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
        .put("cuenta/actualizar", {
          nombre: this.nombre,
          direccion: this.direccion,
          empresa: this.empresa,
          telefono: this.telefono,
          nivel: this.nivel,
          nivel1: this.nivel1,
          nivel2: this.nivel2,
          nivel3: this.nivel3,
          nivel4: this.nivel4,
          nivel5: this.nivel5,
          id: this.cuenta_id
        })
        .then(function(response) {
          $("#ModalLong").modal("hide");
          me.cerrarModal();
          me.listar(1, "", "nombre");
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
              .put("cuenta/desactivar", {
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
          } else if (result.dismiss == Swal.DismissReason.cancel) {
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
              .put("cuenta/activar", {
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
          } else if (result.dismiss == Swal.DismissReason.cancel) {
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
        this.mensaje = "Ingrese el Nombre de la Cuenta";
        return true;
      }
      return false;
    },
    limpiarRegistro() {
      this.cuenta_id = 0;
      this.nombre = "";
      this.telefono = "";
      this.direccion = "";
      this.empresa = "";
      this.nivel = 1;
      this.nivel5 = 0;
      this.nivel1 = 0;
      this.nivel2 = 0;
      this.nivel3 = 0;
      this.nivel4 = 0;
      this.arrayCuenta = [];
      this.cuenta = "";
      this.idCuenta = 0;
      this.selectedCuenta = null;
      this.mensaje = "";
      this.validaciones = "";
    },
    cerrarModal() {
      this.nivel5Accion = 0;
      this.modal = 0;
      this.tituloModal = "";
      this.limpiarRegistro();
    },
    teclaRapida() {
      if (this.editar) {
        this.actualizar();
      } else {
        this.registrar();
      }
    },

    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "cuenta": {
          switch (accion) {
            case "registrar": {
              this.editar = false;
              this.modal = 1;
              this.tituloModal = "Registrar Cuenta";
              this.limpiarRegistro();
              this.cambiarNivel();
              this.nivel5Accion = 1;
              break;
            }
            case "actualizar": {
              this.editar = true;
              this.modal = 1;
              this.tituloModal = "Actualizar Cuenta";
              this.nivel5Accion = 2;
              this.nivel = data["nivel"];
              this.nivel5 = data["nivel5"];
              this.nivel1 = data["nivel1"];
              this.nivel2 = data["nivel2"];
              this.nivel3 = data["nivel3"];
              this.nivel4 = data["nivel4"];
              this.cuenta_id = data["id"];
              this.nombre = data["nombre"];
              break;
            }
          }
        }
      }
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
</style>

