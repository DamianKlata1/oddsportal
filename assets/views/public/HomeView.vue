<script setup>
import { onMounted } from 'vue'
import EventFilters from '../../components/EventFilters.vue'
import Events from './Events.vue'
import { useEventFiltersStore } from '/assets/stores/eventFilters.js'

const eventFiltersStore = useEventFiltersStore()
onMounted(() => {
  eventFiltersStore.selectedDateKeyword = 'today'
  eventFiltersStore.searchName = ''
})
</script>

<template>
  <div>
    <EventFilters/>
    <Events />
  </div>
</template>
