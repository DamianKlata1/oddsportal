<script setup>
import { RouterLink } from 'vue-router';
import LocaleNav from './LocaleNav.vue';
import AccountNav from './AccountNav.vue';
import useUserStore from '/assets/stores/user.js';
import RegionSelector from '../RegionSelector.vue';
import FormatSelector from '../FormatSelector.vue';
const store = useUserStore();
</script>
<template>
  <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
    
    <div class="col-md-3 mb-2 mb-md-0">
      <RouterLink to="/" class="d-inline-flex link-body-emphasis text-decoration-none text-success">
        <span class="fs-4">{{ $t('name') }}</span>
      </RouterLink>
    </div>

    <div class="col-md-auto text-end d-flex justify-content-end align-items-end gap-3">

      <div>
        <span class="d-block small text-muted text-start">Format</span>
        <FormatSelector />
      </div>

      <div>
        <span class="d-block small text-muted text-start">Region</span>
        <RegionSelector />
      </div>
      
      <AccountNav />
      <div>
        <span class="d-block small text-muted text-start">Language</span>
        <LocaleNav />
      </div>

    </div>
  </header>
</template>