<template>
  <!-- <main class="main"> -->
    <div>
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
          <i class="fa fa-align-justify"></i>
          {{ formulario }}
          <button
            type="button"
            @click="ventana=0"
            class="btn btn-secondary"
          >
            <i class="icon-home"></i>&nbsp;Inicio
          </button>
          <button type="button" @click="cargarPdf()" class="btn btn-info">
            <span class="badge badge-danger">
              <i class="icon-doc"></i>PDF
            </span>&nbsp;Reporte
          </button>
        </div>
        <template v-if="ventana==0">
          <div class="card-body">
            <div class="form-group row">
              <div class="col-md-12">
                <div class="input-group">
                  <label for>Fecha Inicio</label>
                  &nbsp;&nbsp;
                  <input
                    type="date"
                    v-model="fechaInicio"
                    class="form-control"
                    placeholder="fecha Inicio"
                  />
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  <label for>Fecha Fin</label>
                  &nbsp;&nbsp;
                  <input
                    type="date"
                    v-model="fechaFin"
                    class="form-control"
                    placeholder="fecha Inicio"
                  />
                  <span class="input-group-append">
                    <button
                      type="submit"
                      @click="listar(fechaInicio,fechaFin)"
                      class="btn btn-primary"
                    >
                      <i class="fa fa-search"></i> Buscar
                    </button>
                  </span>
                </div>
                <div class="input-group"></div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th></th>
                    <th>Codigo</th>
                    <th colspan="5">Balance General</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody v-if="arrayData.length>0">
                  <template v-for="(data,index) in arrayData">
                    <tr :key="data.id" v-if="data.montoTotal>0">
                      <td>
                        <button
                          v-if="data.estado"
                          type="button"
                          @click="abrirNivel(0,'nivel2',index)"
                          class="btn btn-danger form-control-sm btn-sm"
                        >
                          <i class="icon-minus"></i>
                        </button>
                        <button
                          v-else
                          type="button"
                          @click="abrirNivel(1,'nivel2',index)"
                          class="btn btn-success form-control-sm btn-sm"
                        >
                          <i class="icon-plus"></i>
                        </button>
                      </td>
                      <td>
                        <u>
                          <b>{{ data.nivel1}}</b>
                        </u>
                      </td>
                      <td colspan="5">
                        <font style="text-transform: uppercase;">
                          <u>
                            <b>{{ data.nombre}}</b>
                          </u>
                        </font>
                      </td>
                      <td>{{ (data.montoTotal).toFixed(2) }}</td>
                    </tr>
                    <template v-if="data.estado">
                      <template v-for="(data2,index2) in data.datos">
                        <tr :key="data2.id" v-if="data2.montoTotal>0">
                          <td>
                            &nbsp;&nbsp;
                            <button
                              v-if="data2.estado"
                              type="button"
                              @click="abrirNivel(0,'nivel3',index,index2)"
                              class="btn btn-danger form-control-sm btn-sm"
                            >
                              <i class="icon-minus"></i>
                            </button>
                            <button
                              v-else
                              type="button"
                              @click="abrirNivel(1,'nivel3',index,index2)"
                              class="btn btn-success form-control-sm btn-sm"
                            >
                              <i class="icon-plus"></i>
                            </button>
                          </td>
                          <td>
                            <b>{{ data2.nivel1+'.'+data2.nivel2}}</b>
                          </td>
                          <td colspan="5">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <font
                              style="text-transform: uppercase;"
                            >
                              <b>{{ data2.nombre}}</b>
                            </font>
                          </td>
                          <td>{{ (data2.montoTotal).toFixed(2) }}</td>
                        </tr>
                        <template v-if="data2.estado">
                          <template v-for="(data3,index3) in data2.datos">
                            <tr :key="data3.id" v-if="data3.montoTotal>0">
                              <td>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <button
                                  v-if="data3.estado"
                                  type="button"
                                  @click="abrirNivel(0,'nivel4',index,index2,index3)"
                                  class="btn btn-danger form-control-sm btn-sm"
                                >
                                  <i class="icon-minus"></i>
                                </button>
                                <button
                                  v-else
                                  type="button"
                                  @click="abrirNivel(1,'nivel4',index,index2,index3)"
                                  class="btn btn-success form-control-sm btn-sm"
                                >
                                  <i class="icon-plus"></i>
                                </button>
                              </td>
                              <td>
                                <u>{{ data3.nivel1+'.'+data3.nivel2+'.0'+data3.nivel3}}</u>
                              </td>
                              <td colspan="5">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <font
                                  style="text-transform: uppercase;"
                                >
                                  <u>{{ data3.nombre }}</u>
                                </font>
                              </td>
                              <td>{{ (data3.montoTotal) }}</td>
                            </tr>
                            <template v-if="data3.estado">
                              <template v-for="(data4,index4) in data3.datos">
                                <tr :key="data4.id" v-if="data4.montoTotal>0">
                                  <td>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <button
                                      v-if="data4.estado"
                                      type="button"
                                      @click="abrirNivel(0,'nivel5',index,index2,index3,index4)"
                                      class="btn btn-danger form-control-sm btn-sm"
                                    >
                                      <i class="icon-minus"></i>
                                    </button>
                                    <button
                                      v-else
                                      type="button"
                                      @click="abrirNivel(1,'nivel5',index,index2,index3,index4)"
                                      class="btn btn-success form-control-sm btn-sm"
                                    >
                                      <i class="icon-plus"></i>
                                    </button>
                                  </td>
                                  <td>{{ data4.nivel1+'.'+data4.nivel2+'.0'+data4.nivel3+'.0'+data4.nivel4 }}</td>
                                  <td colspan="5">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <font
                                      style="text-transform: uppercase;"
                                    >{{ data4.nombre}}</font>
                                  </td>
                                  <td>{{ (data4.montoTotal) }}</td>
                                </tr>
                                <template v-if="data4.estado">
                                  <template v-for="(data5,index5) in data4.datos">
                                    <tr v-if="data5.montoTotal>0">
                                      <td>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <button
                                          v-if="data5.estado"
                                          type="button"
                                          @click="abrirNivel(0,'nivel5',index,index2,index3,index4,index5)"
                                          class="btn btn-danger form-control-sm btn-sm"
                                        >
                                          <i class="icon-minus"></i>
                                        </button>
                                        <button
                                          v-else
                                          type="button"
                                          @click="abrirNivel(1,'nivel5',index,index2,index3,index4,index5)"
                                          class="btn btn-success form-control-sm btn-sm"
                                        >
                                          <i class="icon-plus"></i>
                                        </button>
                                      </td>
                                      <td>{{ data5.nivel1+'.'+data5.nivel2+'.0'+data5.nivel3+'.0'+data5.nivel4+'.0'+data5.nivel5 }}</td>
                                      <td colspan="5">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        {{ data5.nombre }}
                                      </td>
                                      <td>{{ (data5.montoTotal) }}</td>
                                    </tr>
                                    <template v-if="data5.estado && data5.montoTotal>0">
                                      <tr>
                                        <th></th>
                                        <th>Factura</th>
                                        <th>Registro</th>
                                        <th>Fecha</th>
                                        <th>Descripcion</th>
                                        <th>Monto Total</th>
                                        <th>Tipo</th>
                                        <th>Opciones</th>
                                      </tr>
                                      <template v-for="data6 in data5.datos">
                                        <tr v-if="data6.montoTotal>0">
                                          <td></td>
                                          <td>{{ data6.factura }}</td>
                                          <td>{{ data6.registro }}</td>
                                          <td>{{ data6.fecha }}</td>
                                          <td>{{ data6.descripcion }}</td>
                                          <td>{{ data6.montoTotal }}</td>
                                          <td>
                                            <span class="badge badge-success">{{ data6.tipo }}</span>
                                          </td>
                                          <td>
                                            <button
                                              type="button"
                                              @click="imprimir(data.id)"
                                              class="btn btn-info btn-sm"
                                            >
                                              <i class="fa fa-print fa-lg"></i>
                                            </button>&nbsp;
                                            <button
                                              type="button"
                                              @click="pdf(data6.tipo,data6.id)"
                                              class="btn btn-danger btn-sm"
                                            >
                                              <i class="fa fa-file-pdf-o"></i>
                                            </button> &nbsp;
                                            <button
                                              type="button"
                                              @click="mostrarDetalle(data6.tipo,data6)"
                                              class="btn btn-warning btn-sm"
                                            >
                                              <i class="icon-pencil"></i>
                                            </button>
                                            &nbsp;
                                            <button
                                              type="button"
                                              class="btn btn-danger btn-sm"
                                              @click="desactivar(data6.id,data6.tipo)"
                                            >
                                              <i class="icon-trash"></i>
                                            </button>
                                          </td>
                                        </tr>
                                      </template>
                                    </template>
                                  </template>
                                </template>
                              </template>
                            </template>
                          </template>
                        </template>
                      </template>
                    </template>
                  </template>
                </tbody>
              </table>
            </div>
          </div>
        </template>
        <!-- Ventassss -->
        <template v-else-if="ventana==1">
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
                      data-toggle="modal"
                      data-target="#ModalProducto"
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
                  <tr v-for="(detalle,index) in arrayDetalle" :key="index">
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
                  class="btn btn-primary"
                  @click="actualizarVenta()"
                >Actualizar {{ formulario }}</button>
              </div>
            </div>
          </div>
        </template>
        <!-- compras -->
        <template v-else-if="ventana==2">
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
                    data-toggle="modal"
                    data-target="#ModalProducto"
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
                  <td colspan="1"></td>
                  <td>Monto :</td>
                  <td>
                    <input type="number" readonly v-model="montoCompra" class="form-control" />
                  </td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr>
                  <td colspan="8">No hay Productos Agregados</td>
                </tr>
              </tbody>
            </table>
            <div class="form-group row">
              <div class="col-md-12">
                <button type="button" class="btn btn-secondary" @click="ocultarDetalle()">Cerrar</button>
                <button
                  type="button"
                  class="btn btn-primary"
                  @click="actualizarCompra()"
                >Actualizar {{ formulario }}</button>
              </div>
            </div>
          </div>
        </template>

        <template v-else-if="ventana==3">
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
                    data-toggle="modal"
                    data-target="#ModalIngresoEgreso"
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
                  <button
                    @click="agregarDetalleIE()"
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
                  class="btn btn-primary"
                  @click="actualizarIE()"
                >Actualizar {{ formulario }}</button>
              </div>
            </div>
          </div>
        </template>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!-- Modal -->
    <div
      class="modal fade bd-example-modal-lg"
      tabindex="-1"
      id="ModalProducto"
      role="dialog"
      aria-labelledby="myModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle" v-text="tituloModal"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    <div
      class="modal fade bd-example-modal-lg"
      tabindex="-1"
      id="ModalIngresoEgreso"
      role="dialog"
      aria-labelledby="myModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                        @click="agregarDetalleModalIE(data)"
                        class="btn btn-success btn-sm"
                      >
                        <i class="icon-check"></i>
                      </button>
                    </td>
                    <td v-text="data.nombre"></td>
                    <td>{{ data.nivel1+"."+ data.nivel2+"."+data.nivel3+"."+data.nivel4 +"."+data.nivel5 }}</td>
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
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
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
      factura: 0,
      registro: 0,
      cuenta: "",
      nivel: 1,
      idCuenta: 0,
      cuenta_id: 0,
      nombre: "",
      telefono: "",
      empresa: "",
      direccion: "",
      arrayData: [],
      arrayDetalle: [],
      arrayProducto: [],
      nivel1:0,
      nivel2: 0,
      nivel3: 0,
      nivel4: 0,
      nivel5: 0,
      modal: 0,
      editar: false,
      tituloModal: "",
      tipoAccion: 0,
      offset: 3,

      fecha: new Date(),
      fechaInicio: "",
      fechaFin: "",
      ventana: 0,
      // Ventas
      venta_id: 0,
      idCliente: 0,
      idFormula: 0,
      pago: "",
      cantidad: 0,
      montoVenta: 0,
      descripcion: "",
      formula: "",
      arrayFormula: [],
      arrayCliente: [],

      cliente: "",
      idProducto: 0,
      producto: "",
      unidad: "",
      referencia: "",
      tipo: 0,
      arrayDetalleFormula: [],
      precio: 0,
      codigo: 0,
      selectedCliente: null,
      selectedFormula: null,
      validaciones: "",
      mensaje: "",
      // Compras
      compra_id: 0,
      idProveedor: 0,

      montoCompra: 0,

      arrayProveedor: [],

      criterio: "descripcion",
      criterioP: "nombre",
      buscarP: "",
      buscar: "",
      proveedor: "",
      selectedProveedor: null,
      cantidadTotal: 0,
      formulario: "Balance General",

      ingreso_id: 0,
      haberTotal: 0,
      debeTotal: 0,
      arrayCuenta: [],
      debe: 0,
      haber: 0
    };
  },
  mounted() {
    this.cargarFecha();
    this.listar(this.fechaInicio, this.fechaFin);
  },
  methods: {
    listar(inicio, fin) {
      let me = this;
      var url = "estadoResultado?fechaInicio=" + inicio + "&fechaFin=" + fin;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayData = respuesta.cuentas;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    cargarPdf() {
      window.open("cuenta/listarPdf");
    },
    pdf(accion, id) {
      switch (accion) {
        case "Ventas": {
          window.open("venta/pdf/venta_" + id);
          break;
        }
        case "Compras": {
          window.open("compra/pdf/compra_" + id);
        }
      }
    },
    imprimir(id) {
      window.open("venta/imprimir/venta_" + id);
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
          val1.nivl1 +
          "." +
          val1.nivel2 +
          "." +
          val1.nivel3 +
          "." +
          val1.nivel4 +
          "." +
          val1.nivel5;
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
    // Ventas
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
    encuentra(id) {
      let sw = false;
      for (let i = 0; i < this.arrayDetalle.length && sw == false; i++) {
        if (this.arrayDetalle[i].idProducto == id) {
          sw = true;
        }
      }
      return sw;
    },
    encuentraIE(id) {
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
    ocultarDetalle() {
      this.ventana = 0;
      this.limpiarRegistro();
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
    agregarDetalleIE() {
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
        if (me.encuentraIE(me.idCuenta)) {
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
    agregarDetalleModalIE(data = []) {
      let me = this;
      if (me.encuentraIE(data["id"])) {
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
            data["nivel4"] +
            "." +
            data["nivel5"],
          debe: 0,
          haber: 0,
          descripcionD: ""
        });
      }
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
          if (me.formulario == "Ventas") {
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
            Swal.fire({
              position: "center",
              title: "Se Encuentra Agregado",
              type: "error",
              showConfirmButton: false,
              timer: 1000
            });
          }
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
        if (me.formulario == "Ventas") {
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
          Swal.fire({
            position: "center",
            title: "Se Encuentra Agregado",
            type: "error",
            showConfirmButton: false,
            timer: 1000
          });
        }
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
    validarCompra() {
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
    validarIE() {
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
    actualizarIE() {
      if (this.validarIE()) {
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
      let url = "";
      if (me.formulario == "Ingresos") {
        url = "ingreso/actualizar";
      } else {
        url = "egreso/actualizar";
      }
      axios
        .put(url, {
          descripcion: me.descripcion,
          monto: me.debeTotal,
          data: me.arrayDetalle,
          id: me.ingreso_id
        })
        .then(function(response) {
          me.ventana = 0;
          me.listar(me.fechaInicio, me.fechaFin);
          me.limpiarRegistro();
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    actualizarCompra() {
      if (this.validarCompra()) {
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
          idProveedor: me.idProveedor,
          pago: me.pago,
          montoCompra: me.montoCompra,
          cantidad: me.cantidadTotal,
          descripcion: me.descripcion,
          id: me.compra_id,
          data: me.arrayDetalle
        })
        .then(function(response) {
          me.ventana = 0;
          me.listar(me.fechaInicio, me.fechaFin);
          me.limpiarRegistro();
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    validarVenta() {
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
    actualizarVenta() {
      if (this.validarVenta()) {
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
          me.ventana = 0;
          me.listar(me.fechaInicio, me.fechaFin);
          me.limpiarRegistro();
        })
        .catch(function(error) {
          console.log(error);
        });
    },
     cargarFecha() {
      let year = this.fecha.getFullYear();
      // let mes = this.fecha.getMonth();
      let mes=12;
      // mes++;
      if (mes < 10) {
        this.fechaInicio = year + "-0" + mes + "-" + "01";
        if (mes + 1 < 10) {
          this.fechaFin = year + "-0" + (mes + 1) + "-" + "01";
        } else {
          this.fechaFin = year + "-" + (mes + 1) + "-" + "01";
        }
      } else {
        // this.fechaInicio = year + "-" + mes + "-" + "01";
        // this.fechaFin = year + "-" + (mes + 1) + "-" + "01";
        this.fechaInicio = (year-3) + "-" + mes + "-" + "01";
        this.fechaFin = (year+1) + "-" + (mes) + "-" + "31";
      }
    },
    desactivar(id, opcion) {
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
            let url = "";
            if (opcion == "Ventas") {
              url = "venta/desactivar";
            } else {
              if (opcion == "Compras") {
                url = "compra/desactivar";
              } else {
                if (opcion == "Ingresos") {
                  url = "ingreso/desactivar";
                } else {
                  if (opcion == "Egresos") {
                    url = "egreso/desactivar";
                  }
                }
              }
            }

            axios
              .put(url, {
                id: id
              })
              .then(function(response) {
                me.listar(me.fechaInicio, me.fechaFin);
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
    limpiarRegistro() {
      this.mensaje = "";
      this.validaciones = "";

      this.compra_id = 0;
      this.proveedor = "";
      this.idProveedor = 0;

      this.montoCompra = 0;
      this.producto = "";
      this.referencia = "";
      this.selectedProveedor = null;
      this.cantidadTotal = 0;

      this.venta_id = 0;
      this.cliente = "";
      this.idCliente = 0;
      this.pago = "";
      this.cantidad = 0;
      this.idFormula = 0;
      this.formula = "";
      this.descripcion = "";
      this.idProducto = 0;
      this.tipo = 0;
      this.montoVenta = 0;
      this.arrayDetalle = [];
      this.arrayCliente = [];
      this.arrayProducto = [];
      this.arrayFormula = [];
      this.selectedCliente = null;
      this.mensaje = "";
      this.validaciones = "";
      this.arrayCuenta = [];
      this.formulario = "Balance General";
      this.ingreso_id = 0;
    },
    cargarVenta(id) {
      let me = this;
      var url = "venta/listarVentas?id=" + id;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayDetalle = respuesta.detalles;

          me.venta_id = respuesta.ventas[0].id;
          me.tipo = respuesta.ventas[0].idFormula;
          me.idFormula = respuesta.ventas[0].idFormula;
          me.cliente = respuesta.ventas[0].nombre;
          me.idCliente = respuesta.ventas[0].idCliente;
          me.pago = respuesta.ventas[0].pago;
          me.cantidad = respuesta.ventas[0].cantidad;
          me.descripcion = respuesta.ventas[0].descripcion;
          me.montoVenta = respuesta.ventas[0].montoVenta;
          me.selectedCliente = { id: me.idCliente, nombre: me.cliente };
          if (me.tipo > 0) {
            me.tipo = 1;
          }
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
    },
    cargarCompra(id) {
      let me = this;
      var url = "compra/listarCompras?id=" + id;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayDetalle = respuesta.detalles;

          me.compra_id = respuesta.compras[0].id;
          me.proveedor = respuesta.compras[0].nombre;
          me.idProveedor = respuesta.compras[0].idProveedor;
          me.pago = respuesta.compras[0].pago;
          // me.cantidad = respuesta.compras[0].cantidad;
          me.descripcion = respuesta.compras[0].descripcion;
          me.montoCompra = respuesta.compras[0].montoCompra;
          me.selectedProveedor = { id: me.idProveedor, nombre: me.proveedor };
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    cargarIngresoEgreso(id) {
      let me = this;
      if (me.formulario == "Ingresos") {
        var url = "ingreso/listarIngreso?id=" + id;
        axios
          .get(url)
          .then(function(response) {
            var respuesta = response.data;
            me.arrayDetalle = respuesta.detalles;

            me.ingreso_id = respuesta.ingresos[0].id;
            me.factura = respuesta.ingresos[0].factura;
            me.registro = respuesta.ingresos[0].registro;
            // me.fecha = respuesta.ingresos[0].pago;
            me.haberTotal = respuesta.ingresos[0].monto;
            me.debeTotal = respuesta.ingresos[0].monto;
            me.descripcion = respuesta.ingresos[0].descripcion;
          })
          .catch(function(error) {
            console.log(error);
          });
      } else {
        var url = "egreso/listarEgreso?id=" + id;
        axios
          .get(url)
          .then(function(response) {
            var respuesta = response.data;
            me.arrayDetalle = respuesta.detalles;

            me.ingreso_id = respuesta.egresos[0].id;
            me.factura = respuesta.egresos[0].factura;
            me.registro = respuesta.egresos[0].registro;
            me.fecha = respuesta.egresos[0].pago;
            me.haberTotal = respuesta.egresos[0].monto;
            me.debeTotal = respuesta.egresos[0].monto;
            me.descripcion = respuesta.egresos[0].descripcion;
          })
          .catch(function(error) {
            console.log(error);
          });
      }
    },
    mostrarDetalle(accion, data = []) {
      this.formulario = accion;
      switch (accion) {
        case "Ventas": {
          this.cargarVenta(data["id"]);
          this.ventana = 1;
          break;
        }
        case "Compras": {
          this.cargarCompra(data["id"]);
          this.ventana = 2;
          break;
        }
        case "Ingresos": {
          this.cargarIngresoEgreso(data["id"]);
          this.ventana = 3;
          break;
        }
        case "Egresos": {
          this.cargarIngresoEgreso(data["id"]);
          this.ventana = 3;
          break;
        }
      }
    },
    abrirNivel(valor, accion, index1, index2, index3, index4, index5) {
      switch (accion) {
        case "nivel2": {
          this.arrayData[index1].estado = valor;
          break;
        }
        case "nivel3": {
          this.arrayData[index1].datos[index2].estado = valor;
          break;
        }
        case "nivel4": {
          this.arrayData[index1].datos[index2].datos[index3].estado = valor;
          break;
        }
        case "nivel5": {
          this.arrayData[index1].datos[index2].datos[index3].datos[
            index4
          ].estado = valor;
          break;
        }
        case "nivel5": {
          this.arrayData[index1].datos[index2].datos[index3].datos[
            index4
          ].datos[index5].estado = valor;
          break;
        }
      }
    }
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

