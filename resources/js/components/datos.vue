<template>
          
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
                  <tbody v-if="(array_detalle.length)">
                    <tr v-for="(detalle,index) in array_detalle" :key="detalle.idProducto">
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

<script>
export default {

}
</script>

<style>

</style>