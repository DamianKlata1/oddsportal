<script setup>
import { ref, onMounted } from 'vue' // 'watch' is no longer needed here for triggering fetches
import { useEventFiltersStore } from '/assets/stores/eventFilters'

const filtersStore = useEventFiltersStore()



onMounted(() => {


})

</script>

<template>
  <!-- if error show him-->
  <div v-if="filtersStore.errorMessage" class="alert alert-danger">
    {{ filtersStore.errorMessage }}
  </div>
  <div v-else class="row mb-4 g-3 align-items-center">
    <div class="col-md-5">
      <input id="searchNameInput" type="text" class="form-control" v-model="filtersStore.searchName"
        :placeholder="`${$t('search_by_team_name')}...`" />
    </div>
    <div class="col-md-5">
      <select id="dateFilterSelect" class="form-select" v-model="filtersStore.selectedDateKeyword">
        <option v-for="option in filtersStore.dateKeywordOptions" :key="option.value" :value="option.value">
          {{ option.label }}
        </option>
      </select>
    </div>
    <div class="col-md-2">
      <button class="btn btn-outline-secondary w-100" @click="filtersStore.clearFilters">
        {{ $t('clear_filters') }}
      </button>
    </div>
  </div>
</template>