<script setup>
import {RouterView} from 'vue-router'
import PublicNav from '../../components/navigation/PublicNav.vue'
import SideBarNav from '../../components/navigation/SidebarNav.vue'
</script>

<template>
  <div class="container-fluid">
    <PublicNav/>
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

<!--<style scoped src="/node_modules/bootstrap/dist/css/bootstrap.css">
    /*@import "/node_modules/bootstrap/scss/bootstrap.scss";*/
</style>-->
<!--<style scoped lang="scss">
    .main-wrapper::v-deep(.bootstrap-scope) {
        @import "/node_modules/bootstrap/scss/bootstrap.scss";
    }
</style>-->