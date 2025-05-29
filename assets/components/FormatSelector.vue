<script setup>
import { onMounted} from 'vue'

import { useOddsFormatStore } from '../stores/oddsFormat';

const store = useOddsFormatStore()
onMounted(() => {
  store.fetchFormats()
})
</script>

<template>
  <div class="dropdown">
    <button
      class="btn btn-outline-success dropdown-toggle d-flex align-items-center"
      type="button"
      id="oddsFormatDropdown"
      data-bs-toggle="dropdown"
      aria-expanded="false"
    >
     {{store.selectedFormat }}
    </button>
    <ul class="dropdown-menu" aria-labelledby="oddsFormatDropdown">
      <li
        v-for="format in store.formats"
        :key="format.id"
        @click="store.selectedFormat = format"
      >
        <button class="dropdown-item">
          {{ format }} 
        </button>
      </li>
    </ul>
  </div>
</template>