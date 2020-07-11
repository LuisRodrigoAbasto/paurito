<template>
  <div class="sidebar">
    <nav class="sidebar-nav">
      <ul class="nav">
        <!-- <li class="nav-item">
          <a class="nav-link" href="index.html">
            <i class="nav-icon icon-speedometer"></i> Dashboard
            <span class="badge badge-primary">NEW</span>
          </a>
        </li> -->
        <!-- <li class="nav-title">SISTEMA</li> -->
        <template v-for="data in array_menu">
        <li class="nav-item nav-dropdown"  :key="data.padre_id" v-if="data.padre_id!=null">
          <a class="nav-link nav-dropdown-toggle" href="#" > 
            <i :class=data.icono></i> {{data.nombre}}
          </a>

          <ul class="nav-dropdown-items" v-for="sub in data.data" :key="sub.nombre">
           
            <li class="nav-item">            
               <router-link :to=sub.path>
                 <a class="nav-link" href="#">
                <i :class=sub.icono></i> {{ sub.nombre }}
                  </a>
               </router-link>    
            </li>
              
          </ul>

        </li>
       <li class="nav-item" v-else :key="data.nombre">
                     <router-link :to=data.path class="nav-link" href="#"><i :class=data.icono></i> {{data.nombre}} </router-link>
                </li>
        </template>               
        <!-- <li class="nav-item">
          <a class="nav-link" href="typography.html">
            <i class="nav-icon icon-pencil"></i> Typography
          </a>
        </li> -->
      </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
  </div>
</template>
<script>
export default {
  data(){
    return {
    array_menu:[]
    }
  },
  methods:{
    listarMenu(){
      var url = "api/menu";
      axios
        .get(url)
        .then(resp=> {
          this.array_menu=resp.data.data;
          })
        .catch(function(error) {
          console.log(error);
        });
    }
  },
  mounted(){
    this.listarMenu();
  }
};
</script>