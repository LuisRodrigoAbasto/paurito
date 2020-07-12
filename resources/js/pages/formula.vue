<template>
  <!-- <main class="main"> -->
    <!-- Breadcrumb -->
    <!-- <ol class="breadcrumb">
      <li class="breadcrumb-item">Home</li>
      <li class="breadcrumb-item">
        <a href="#">Admin</a>
      </li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>-->
    <div>
    <br />
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> {{ array_data.nombre }}
          <!-- <button
            type="button"
            data-toggle="modal"
            :data-target="'#'+array_data.modal.id"
            @click="nuevo(true)"
            class="btn btn-secondary"
          > -->
           <button
            type="button"
            @click="nuevo()"
            class="btn btn-secondary"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo {{ array_data.nombre }}
          </button>

          <button type="button"  class="btn btn-danger" 
          @click="cargarPdf()">
            <i class="fa fa-file-pdf-o"></i>&nbsp;PDF
          </button>

          <button type="button" class="btn btn-info"
          @click="imprimir()">
            <i class="fa fa-print fa-lg"></i>
          </button>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-12">
              <div class="input-group">
                <div class="col-md-10">
                  <div class="input-group">
                    <select class="form-control col-md-3" v-model="opcion" >
                      <option value="nombre">Nombre</option>
                    </select>
                    <input
                      type="text"
                      class="form-control"
                      v-model="buscar"
                      placeholder="Buscar Producto"
                      @keyup.enter="listar(1,buscar,opcion)"
                    />
                    <span class="input-group-append">
                      <button
                        type="submit"
                        class="btn btn-primary"
                        @click="listar(1,buscar,opcion)"
                      >
                        <i class="fa fa-search"></i> Buscar
                      </button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
            <app-table :array_data="array_data" @update="update($event)" @activar="activar($event)" @desactivar="desactivar($event)">
            </app-table>
          <app-pagination-datos :pagination="pagination" :offset="offset" @pagina="pagina($event)">
          </app-pagination-datos>
        </div>
      </div>
    </div>

  <app-modal-listar :modal="modal" @cerrar_modal="cerrar_modal()" >
  </app-modal-listar>
  <!-- </main> -->
  </div>
</template>


<script>
import table from "../components/table.vue";
import modal_listar from "../components/modal_listar.vue";
import pagination_datos from "../components/pagination.vue";
import Swal from "sweetalert2/dist/sweetalert2.js";
import "sweetalert2/src/sweetalert2.scss";
import Vue from "vue";
export default {
    components: {
    "app-table": table,
    "app-modal-listar": modal_listar,
    "app-pagination-datos": pagination_datos,
  },
  data() {
    return {
      
     
     array_data:{
       nombre:'Formula',
       boton:true,
       botones:{
         editar:true,
         estado:true,
         mostrar:false,
       },
        titulo:[
              {nombre:'id',titulo:"ID"},
              {nombre:"nombre",titulo:"Formula"},
              ],
      
      },
      modal:{
          titulo:"Lista Producto",
          id:'formula',
          datos:[
                {nombre:'id',titulo:"ID"},
                {nombre:"nombre",titulo:"Producto"},
                {nombre:"stock",titulo:"Stock"},
                {nombre:"unidad",titulo:"Unidad"},
                ]
            ,
          },
        data:[],
      datos:{
        id:0,
        nombre:'',
        stock:0,
        ref_stock:'',
        unidad:0,
        ref_unidad:''
      },
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      buscar: "",
      error_messages:{},
      // activarValidate: "",
      // mensaje: "",
      opcion:"nombre",
      metodo:'',
      url:'api/formula'
    };
  },
    mounted() {
    this.listar(1, this.buscar,this.opcion);
    // console.log(this.array_data[0]);
  },
  methods: {
    pagina(page){
      this.listar(page,this.buscar,this.opcion)
    },
    listar(page, buscar,opcion) {
      var url = this.url+"?page=" + page + "&buscar=" + buscar+"&opcion="+opcion;
      axios
        .get(url)
        .then(resp=> {
          this.array_data.data=resp.data.data.data;
          this.pagination={
                  total: resp.data.data.total,
                  current_page: resp.data.data.current_page,
                  per_page: resp.data.data.per_page,
                  last_page: resp.data.data.last_page,
                  from: resp.data.data.from,
                  to: resp.data.data.to
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    limpiar_datos(){
      this.array_data.modal.validate="";
      this.array_data.modal.datos=[
                {
                titulo:"ID",
                indice:'id',
                tipo:'numero',
                dato:0,
                visible:false,
                placeholder:'',
                required:false,
                error:''
                },
                {
                titulo:"Nombre",
                indice:'nombre',
                tipo:'text',
                dato:'',
                visible:true,
                placeholder:'Ingrese El Nombre de la Formula',
                required:true,
                error:''
                },
               
                ];
    },
    eventoAlerta(success, mensaje) {
      Swal.fire({
        position: "center",
        icon: success,
        title: mensaje,
        showConfirmButton: false,
        timer: 1500
      });
    },
    cerrar_modal(){
      $(`#${this.modal.id}`).modal('hide');
      this.limpiar_datos()
    },
    nuevo(){
      $(`#${this.modal.id}`).modal('show');
    },
    agregar_dato(){
      this.modal.datos.forEach(x=>
        this.datos[x.indice]=x.dato      
      );
    },
    guardar(){
      this.agregar_dato();
      if( this.datos.id==0){
        this.metodo="post"
      }
      else{
        this.metodo="put"
      }
        axios({
        method: this.metodo,
        data: this.datos,
        url: this.url
        })
        .then(resp => {
        this.eventoAlerta(resp.data.alert,resp.data.message)
        this.listar(1,'','nombre');
        this.cerrar_modal();
        this.array_data.modal.validate="";
        })
        .catch(error => {
        this.array_data.modal.validate='was-validated';
        this.eventoAlerta(error.response.data.alert,error.response.data.error)
        this.error_messages=error.response.data.error_messages;
        // console.log(this.error_messages);
        this.pasar_error();
        // console.log('ERROR', error);
        })
    },
    pasar_error(){

      this.array_data.modal.datos.forEach(x=>
        x.error=this.error_messages[x.indice]==undefined?'':this.error_messages[x.indice][0]  
      );
    },
     pasar_datos(){
       this.array_data.modal.datos.forEach(x=>
        x.dato=this.datos[x.indice]
      );
    },
    update(id)
    {
      axios({
        method: 'get',
        url: this.url+'/get/'+id
        })
        .then(resp => {        
          this.datos=resp.data.data
          this.pasar_datos();
        })
        .catch(error => {
        this.eventoAlerta(error.response.data.alert,error.response.data.error)
        })
    },
    desactivar(id){
 const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      })

      swalWithBootstrapButtons.fire({
          title: "Estas Seguro de Desactivar el Registro?",
          text: "Si Desactiva no estara en la Lista!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Si, Desactivar!",
          cancelButtonText: "No, Cancelar!",
          reverseButtons: true
        }).then(result => {
          if (result.value) {
            this.registrar_activar(id);
          } else if (result.dismiss === Swal.DismissReason.cancel) {
           this.eventoAlerta('error',"Cancelado");
          }
        });
    },
    activar(id)
    {
      let sw=false;
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
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Si, Activar!",
          cancelButtonText: "No, Cancelar!",
          reverseButtons: true
        })
        .then(result => {
          if (result.value) {
            this.registrar_activar(id);
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            this.eventoAlerta('error',"Cancelado")
          }
        });

      
    
    },
    registrar_activar(id){
    axios({
        method: 'put',
        url: this.url+'/activar/'+id
        })
        .then(resp => {
          this.eventoAlerta(resp.data.alert,resp.data.message);
          this.listar(1,'','nombre');
        })
        .catch(error => {
        this.eventoAlerta(error.response.data.alert,error.response.data.error)
        })
    },
    cargarPdf() {
      window.open("producto/listarPdf");
    },
    imprimir() {
      window.open("producto/imprimir");
    },
  },

};
</script>


