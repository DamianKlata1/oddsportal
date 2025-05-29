<script setup>
import {RouterView} from 'vue-router'
import PublicNav from '../../components/navigation/PublicNav.vue'
import SideBarNav from '../../components/navigation/SidebarNav.vue'
import SportNav from "../../components/navigation/SportNav.vue";
import EventFilters from '../../components/EventFilters.vue';
import Events from './Events.vue'


</script>

<template>
  <div class="container">
    <PublicNav/>
    <SportNav/>
    <div class="d-flex">
      <SideBarNav/>
      <div class="flex-grow-1 p-3">
        <main>
          <RouterView/>
          
        </main>
      </div>
    </div>
  </div>
</template>

<!-- <style scoped src="/node_modules/bootstrap/dist/css/bootstrap.css">
    /*@import "/node_modules/bootstrap/scss/bootstrap.scss";*/
</style>-->
<!-- <style scoped lang="scss">
    .main-wrapper::v-deep(.bootstrap-scope) {
        @import "/node_modules/bootstrap/scss/bootstrap.scss";
    } 
</style> -->