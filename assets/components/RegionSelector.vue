<script setup>
import { onMounted} from 'vue'
import { useBetRegionsStore } from '/assets/stores/betRegions.js'

const store = useBetRegionsStore()
onMounted(() => {
  store.fetchBetRegions()
})

</script>

<template>
  <div class="dropdown">
    <button
      class="btn btn-outline-success dropdown-toggle d-flex align-items-center"
      type="button"
      id="regionSelectDropdown"
      data-bs-toggle="dropdown"
      aria-expanded="false"
    >
      <img
        v-if="store.selectedBetRegion?.logoPath"
        :src="store.selectedBetRegion.logoPath"
        alt="selected region logo"
        class="me-2"
        style="width: 20px; height: 20px; object-fit: cover;"
      />
      {{ store.selectedBetRegion?.name || $t('select_region') }}
    </button>
    <ul class="dropdown-menu" aria-labelledby="regionSelectDropdown">
      <li
        v-for="region in store.betRegions"
        :key="region.id"
        @click="store.selectedBetRegion = region"
      >
        <button class="dropdown-item d-flex align-items-center">
          <img
            :src="region.logoPath"
            alt="region logo"
            class="me-2"
            style="width: 20px; height: 20px; object-fit: cover;"
          />
          {{ region.name }}
        </button>
      </li>
    </ul>
  </div>
</template>