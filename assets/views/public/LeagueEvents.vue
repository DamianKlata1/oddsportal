<script setup>
import { onMounted, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useLeagueEventsStore } from '/assets/stores/leagueEvents.js'

const route = useRoute()
const leagueEventsStore = useLeagueEventsStore()

onMounted(() => {
  leagueEventsStore.fetchLeagueEvents(route.params.leagueId)
})

watch(() => route.params.leagueId, (newLeagueId) => {
  leagueEventsStore.fetchLeagueEvents(newLeagueId)
})

</script>

<template>
  <div v-if="leagueEventsStore.leagueEvents.length > 0" class="mt-3">
    <h5>Events:</h5>
    <div v-for="event in leagueEventsStore.leagueEvents" :key="event.id" class="mb-3 border p-2 rounded bg-light">
      <div><strong>{{ event.homeTeam }} vs {{ event.awayTeam }}</strong></div>
      <div>Date: {{ event.date }}</div>
      <div class="mt-2">
        <div><strong>Best Odds:</strong></div>
        <div class="d-flex justify-content-between">
          <div>
            🏠 {{ event.bestOdds.home.odds.value }} ({{ event.bestOdds.home.bookmaker.name }})
          </div>
          <div>
            🤝 {{ event.bestOdds.draw.odds.value }} ({{ event.bestOdds.draw.bookmaker.name }})
          </div>
          <div>
            🛫 {{ event.bestOdds.away.odds.value }} ({{ event.bestOdds.away.bookmaker.name }})
          </div>
        </div>
      </div>
    </div>
  </div>
  <div v-else>No events found for this league.</div>
</template>