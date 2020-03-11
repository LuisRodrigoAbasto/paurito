<template>
<div class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
<app-header></app-header>
 <div class="app-body">
    <app-sidebar></app-sidebar>
    <main class="main">
                <app-breadcrumb></app-breadcrumb>
                <router-view></router-view>
    </main>
    <app-menu-aside/>

    </div>
<app-footer/>
</div>
</template>
<script>
import Sidebar from '../shared/sidebar.vue'
import Header from '../shared/header.vue'
import Breadcrumb from '../shared/breadcrumb.vue'
import Menu_Aside from '../shared/menu_aside.vue'
import Footer from '../shared/footer.vue'
export default {
    components:{
        'app-header':Header,
        'app-sidebar':Sidebar,
        'app-breadcrumb':Breadcrumb,
        'app-menu-aside':Menu_Aside,
        'app-footer':Footer
    }
}
</script>