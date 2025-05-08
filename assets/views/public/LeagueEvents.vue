<script setup>
import { onMounted, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import apiPublic from '/assets/api/apiPublic.js'

const route = useRoute()
const leagueId = ref(route.params.leagueId)
const events = ref([])
const isLoading = ref(false)
const errorMessage = ref(null)

onMounted(() => {
  fetchLeagueEvents()
})

watch(() => route.params.leagueId, (newLeagueId) => {
  leagueId.value = newLeagueId
  fetchLeagueEvents()
})

async function fetchLeagueEvents() {
  isLoading.value = true
  errorMessage.value = null
  try {
    const response = await apiPublic().get(`/leagues/${leagueId.value}/events`)
    events.value = response.data
  } catch (error) {
    errorMessage.value = error.message
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div>
    <h3>Events for League {{ leagueId }}</h3>
    <div v-if="isLoading">Loading...</div>
    <div v-if="errorMessage" class="text-danger">{{ errorMessage }}</div>
    <ul v-if="events.length > 0" class="list-group">
      <li v-for="event in events" :key="event.id" class="list-group-item">
        {{ event.name }} - {{ event.date }}
      </li>
    </ul>
    <div v-else-if="!isLoading && events.length === 0">No events found for this league.</div>
  </div>
</template>