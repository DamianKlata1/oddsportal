<script setup>
import { onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useLeagueEventsStore } from '/assets/stores/leagueEvents.js'
import { useBetRegionsStore } from '/assets/stores/betRegions.js'

const route = useRoute()
const leagueEventsStore = useLeagueEventsStore()
const betRegionsStore = useBetRegionsStore()

const tryFetchEvents = async () => {
  const leagueId = route.params.leagueId
  const selectedRegion = betRegionsStore.selectedBetRegion?.name
  if (leagueId && selectedRegion) {
    await leagueEventsStore.fetchLeagueEvents(leagueId, selectedRegion)
  }
}

onMounted(tryFetchEvents)

watch(() => route.params.leagueId, tryFetchEvents)
watch(() => betRegionsStore.selectedBetRegion, tryFetchEvents)
</script>

<template>
  <div class="container my-4">
    <div v-if="leagueEventsStore.isLoading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-else-if="leagueEventsStore.leagueEvents.length > 0">
      <h4 class="mb-4">Upcoming Events</h4>
      <div class="row g-4">
        <div
          v-for="event in leagueEventsStore.leagueEvents"
          :key="event.id"
          class="col-md-6 col-lg-4"
        >
          <div class="card shadow-sm h-100">
            <div class="card-body">
              <h5 class="card-title">
                {{ event.homeTeam }} vs {{ event.awayTeam }}
              </h5>
              <p class="card-text text-muted mb-2">
                <i class="bi bi-calendar-event"></i> {{ event.commenceTime }}
              </p>
              <h6 class="mt-3">Best Odds:</h6>
              <ul class="list-unstyled">
                <li v-for="outcome in event.bestOutcomes" class="mb-1">
                  🏠 <strong>{{ outcome.name }}</strong> - 
                  <span class="text-success">{{ outcome.price }}</span>
                  <small class="text-muted">({{ outcome.bookmaker.name }})</small>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="text-center text-muted py-5">
      <i class="bi bi-emoji-frown fs-1 d-block mb-3"></i>
      No events found for this league.
    </div>
  </div>
</template>