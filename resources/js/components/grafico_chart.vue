<template>
  <div class="card">
    <div class="card-header">
      Line Chart
      <div class="card-header-actions">
        <a class="card-header-action" href="http://www.chartjs.org" target="_blank">
          <small class="text-muted">docs</small>
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="chart-wrapper">
        <canvas :id="table"></canvas>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment';
import Chart from 'chart.js';
export default {
  props: {
      grafico:Array,
      table:String
  },

  data() {
    return {
    // table: null,
    nombre:null,
      chart: null,
      total: [],
      mes: []
    };
  },
  mounted(){
      this.cargar();
  },
  methods: {
    cargar(){
        let me=this;
        moment().locale('es');
      me.grafico.map(function(x) {
        me.mes.push(x.mes);
        me.total.push(x.total);
      });
      me.nombre = document.getElementById(me.table).getContext("2d");

      me.chart = new Chart(me.nombre, {
        type: 'bar',
        data: {
          labels: me.mes,
          datasets: [
            {
              label:me.table,
              data: me.total,
              backgroundColor: "#fd324e",
              borderColor: "#fd324e",
              borderWidth: 1
            }
          ]
        },
        options: {
          scales: {
            yAxes: [
              {
                ticks: {
                  beginAtZero: true
                }
              }
            ]
          }
        }
      });
    }
  }
};
</script>

<style>
</style>