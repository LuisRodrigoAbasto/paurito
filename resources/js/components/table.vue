<template>
   <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th v-for="data in array_data.titulo" :key="data.index">{{ data.titulo }}</th>
                  <!-- <th>Nombre</th>
                  <th>Stock</th>
                  <th>Unidad</th>
                  <th>Total + Unidad</th>
                  <th>Estado</th>-->
                  <th v-if="array_data.botones.estado">Opciones</th> 
                </tr>
              </thead>
              <tbody>
                <tr v-for="data in array_data.data" :key="data.id">                  
                  <td v-for="(row,index) in array_data.titulo" :key="index"> {{ data[row.nombre] }}</td>
                  <td v-show="array_data.boton">
                    <button v-if="array_data.botones.editar"
                      type="button"
                      data-toggle="modal"
                      :data-target="'#'+array_data.modal.id"
                      @click="update(data.id)"
                      class="btn btn-warning btn-sm"
                    >
                      <i class="icon-pencil"></i>
                    </button> &nbsp;
                    <template v-if="array_data.botones.estado">
                      <button v-if="data.estado"
                        type="button"
                        class="btn btn-danger btn-sm"
                        @click="desactivar(data.id)"
                      >
                        <i class="icon-trash"></i>
                      </button>

                      <button v-else-if="!data.estado"
                      type="button" class="btn btn-info btn-sm" 
                       @click="activar(data.id)"
                      >
                        <i class="icon-check"></i>
                      </button>
                    </template>
                     &nbsp;
                     <button v-if="array_data.botones.mostrar"
                      type="button" class="btn btn-info btn-sm" 
                      @click="mostrar(data.id)"
                      >
                        <i class="icon-eye"></i>
                      </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
</template>

<script>
export default {
    props:{
        array_data:Object(),
    },
data(){
    return{

    }
},
mounted(){
},
methods:{
  update(id){
    this.$emit('update',id);
  },
  activar(id){
    this.$emit('activar',id);
  },
  desactivar(id){
    this.$emit('desactivar',id);
  },
  mostrar(){
    this.$emit('mostrar',id);
  }
}
}
</script>

<style>

</style>