<template>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="card-columns cols-2">
          <template v-for="(data,index) in array_data.data">
        <app-grafico-chart :table="index" :grafico="data"></app-grafico-chart>
          </template>
      </div>
    </div>
  </div>
</template>

<script>
import grafico_chart from "../components/grafico_chart.vue";
export default {
  components: {
    "app-grafico-chart": grafico_chart
  },
  data() {
    return {
      array_data:[]
    };
  },
  mounted(){
      this.cargar();
  },
  methods: {
    cargar() {
      var url = "dashboard_controller";
      axios
        .get(url)
        .then(resp=> {
          this.array_data=resp.data;
        })
        .catch(error=> {
          console.log(error);
        })},
  }
  };
</script>