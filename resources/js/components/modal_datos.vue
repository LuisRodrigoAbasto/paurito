<template>
    <div
      class="modal fade bd-example-modal-lg"
      tabindex="-1"
      role="dialog"
      :id="modal.id"
      aria-labelledby="myModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{ modal.titulo }} {{ modal.datos[0].dato }} </h4>
            <button
              type="button"
              class="close"            
              data-dismiss="modal"
              aria-label="Close"
              @click="cerrar_modal()"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div :class="'modal-body '+ (modal.validate==true)?+ 'was-validated':''">
            <form action method="post" enctype="multipart/form-data" class="form-horizontal">
            <template v-for="datos in modal.datos" v-if="datos.visible==true">
                        <div class="form-group row" v-if="datos.tipo=='text'" >
                        <label class="col-md-3 form-control-label" for="text-input">{{ datos.titulo }}</label>
                        <div class="col-md-9">
                            <input
                            v-model="datos.dato"
                            type="text"
                            class="form-control"
                            :required="datos.required==true"
                            :placeholder="datos.placeholder"
                            />
                             <span class="invalid-feedback" role="alert" v-show="datos.error">
                                        <strong>{{ datos.error }}</strong>
                                    </span>
                        </div>
                        </div>
                        <div class="form-group row" v-if="datos.tipo=='decimal'">
                        <label class="col-md-3 form-control-label" for="text-input">{{ datos.titulo }}</label>
                        <div class="col-md-9">
                            <input
                            v-model="datos.dato"
                            type="number"
                            min="1"
                            step="any"
                            class="form-control"
                            :required="datos.required==true"
                           :placeholder="datos.placeholder"
                            />
                             <span class="invalid-feedback" role="alert" v-show="datos.error">
                                        <strong>{{ datos.error }}</strong>
                                    </span>
                        </div>
                        </div>

                        <div class="form-group row" v-if="datos.tipo=='numero'">
                        <label class="col-md-3 form-control-label" for="text-input">{{ datos.titulo }}></label>
                        <div class="col-md-9">
                            <input
                            v-model="datos.dato"
                            type="number"
                            min="1"
                            step="any"
                            class="form-control"
                            :required="datos.required==true"
                            :placeholder="datos.placeholder"
                            />
                             <span class="invalid-feedback" role="alert" v-show="datos.error">
                                        <strong>{{ datos.error }}</strong>
                                    </span>
                        </div>
                        </div>
                        <div class="form-group row" v-if="datos.tipo=='date'">
                        <label class="col-md-3 form-control-label" for="text-input">{{ datos.titulo }}</label>
                        <div class="col-md-9">
                            <input
                            v-model="datos.dato"
                            type="date"
                            class="form-control"
                            :required="datos.required==true"
                            :placeholder="datos.placeholder"
                            />
                            <span class="invalid-feedback" role="alert" v-show="datos.error">
                                        <strong>{{ datos.error }}</strong>
                                    </span>
                        </div>
                        </div>
                        <div class="form-group row" v-if="datos.tipo=='textarea'">
                        <label class="col-md-3 form-control-label" for="text-input">{{ datos.titulo }}</label>
                        <div class="col-md-9">
                            <textarea
                            v-model="datos.dato"
                            type="text"
                            class="form-control"
                            :required="datos.required==true"
                            :placeholder="datos.placeholder"
                            />
                             <span class="invalid-feedback" role="alert" v-show="datos.error">
                                        <strong>{{ datos.error }}</strong>
                                    </span>
                        </div>
                        </div>
              </template>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
            @click="cerrar_modal()"
            >Cerrar</button>
            <button
              type="button"
              class="btn btn-primary"
              @click="registrar()"
            >Guardar</button>
            
          </div>
        </div>

      </div>


    </div>

</template>
<script>
export default {
    props:{
        modal:Object(),
    },
    methods:{
        cerrar_modal(){
            this.$emit('cerrar_modal');
        },
        registrar(){
            this.$emit('registrar');
        }
    }
}
</script>