<script setup>
import { onMounted, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useLeagueEventsStore } from '/assets/stores/leagueEvents.js'
import { useBetRegionsStore } from '/assets/stores/betRegions.js'

const route = useRoute()
const leagueEventsStore = useLeagueEventsStore()
const betRegionsStore = useBetRegionsStore()

onMounted(async () => {
  await tryFetchEvents()
})

 const tryFetchEvents = async () => {
  const leagueId = route.params.leagueId
  const selectedRegion = betRegionsStore.selectedBetRegion.name
  console.log(selectedRegion)

  if (leagueId && selectedRegion) {
    leagueEventsStore.fetchLeagueEvents(leagueId, selectedRegion)
  }
}

// Watch league ID
watch(
  () => route.params.leagueId,
  () => {
    tryFetchEvents()
  }
)

// Watch selected bet region
watch(
  () => betRegionsStore.selectedBetRegion,
  () => {
    tryFetchEvents()
  }
)

</script>

<template>
  <div v-if="leagueEventsStore.leagueEvents.length > 0" class="mt-3">
    <h5>Events:</h5>
    <div v-for="event in leagueEventsStore.leagueEvents" :key="event.id" class="mb-3 border p-2 rounded bg-light">
      <div><strong>{{ event.homeTeam }} vs {{ event.awayTeam }}</strong></div>
      <div>Date: {{ event.commenceTime }}</div>
      <div class="mt-2">
        <div><strong>Best Odds:</strong></div>
        <div class="d-flex justify-content-between" v-for="outcome in event.bestOutcomes" >
          <div>
            🏠 {{ outcome.name }} ({{ outcome.price }}) [{{ outcome.bookmaker.name }}]
          </div>
        </div>
      </div>
    </div>
  </div>
  <div v-else>No events found for this league.</div>
</template>