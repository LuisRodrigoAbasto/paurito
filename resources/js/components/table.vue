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
                  <th>Opciones</th> 
                </tr>
              </thead>
              <tbody>
                <tr v-for="data in array_data.data" :key="data.id">                  
                  <td v-for="(row,index) in array_data.titulo" :key="index"> {{ data[row.nombre] }}</td>
                  <td>
                    <button
                      type="button"
                      data-toggle="modal"
                      :data-target="'#'+array_data.modal.id"
                      @click="update(data.id)"
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
                      <button type="button" class="btn btn-info btn-sm" 
                      >
                        <i class="icon-check"></i>
                      </button>
                    </template>
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
  }
}
}
</script>

<style>

</style>