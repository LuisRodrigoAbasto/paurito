<template>
  <div>
    <a
      class="nav-link"
      data-toggle="dropdown"
      href="#"
      role="button"
      aria-haspopup="true"
      aria-expanded="false"
      @focus.prevent="notificacion()"
    >
      <i class="icon-bell"></i>
      <span class="badge badge-pill badge-danger">{{ arrayData.length }}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-right">
      <div class="dropdown-header text-center">
        <strong>Productos</strong>
      </div>
        <div v-for="data in arrayData" :key="data.id">
            <a class="dropdown-item" href="#">
                <i class="fa fa-tasks"></i> {{ data.nombre }}
                <span class="badge badge-danger">{{ data.stock }} {{ data.unidad }}</span>
            </a>
        </div>
    </div>
  </div>
</template>
<script>
export default {
    data(){
    return{
        arrayData:[]
        }
    },
     methods: {
    notificacion() {
      let me = this;
      var url =
        "/producto/notificaciones";
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayData = respuesta.productos;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
 },
  mounted() {
    this.notificacion();
  }
}
</script>

