<template>
        <div
      class="modal fade bd-example-modal-lg"
      tabindex="-1"
      :id="modal.id"
      role="dialog"
      aria-labelledby="myModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{ modal.titulo }}</h4>
            <button
              type="button"
              class="close"
              @click="cerrar_modal()"
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
                  <select class="form-control col-md-4" v-model="opcion">
                    <option value="nombre">Nombre</option>
                  </select>
                  <input
                    type="text"
                    v-model="buscar"
                    @keyup.enter="listar(buscar,criterio)"
                    class="form-control"
                    placeholder="Buscar Producto"
                  />
                  <span class="input-group-append">
                    <button
                      type="submit"
                      @click="listar(buscar,criterio)"
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
                  <th v-for="dato in modal.datos">{{dato.titulo}}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="data in modal.data" :key="data.id">
                  <td>
                    <button
                      type="button"
                      @click="agregar_detalle(data)"
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
</template>
<script>
export default {
    props:{
        modal:Object(),

    },
    data(){
return{
    opcion:'nombre',
    buscar:'',
    criterio:''
}
    }
}
</script>