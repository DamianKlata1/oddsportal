<script setup>
import { onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useEventsStore } from '/assets/stores/events.js'
import { useBetRegionsStore } from '/assets/stores/betRegions.js'
import { useOddsFormatStore } from '/assets/stores/oddsFormat'
import { usePaginationStore } from '/assets/stores/pagination'
import { useEventFiltersStore } from '/assets/stores/eventFilters.js'
import BasePagination from '/assets/components/Pagination.vue'
import { formatInTimeZone } from 'date-fns-tz'
import debounce from 'lodash/debounce'
import { useLeagueStore } from '../../stores/league'
import { ref } from 'vue'




const formatDateTime = (isoTime) => {
  if (!isoTime) return '—'
  try {
    const timeZone = Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC'
    return formatInTimeZone(isoTime, timeZone, 'yyyy-MM-dd HH:mm')
  } catch (error) {
    console.error('Date formatting error:', error)
    return isoTime
  }
}

const eventsStore = useEventsStore()
const eventFiltersStore = useEventFiltersStore()
const betRegionsStore = useBetRegionsStore()
const oddsFormatStore = useOddsFormatStore()
const paginationStore = usePaginationStore()
const leagueStore = useLeagueStore()

const tryFetchEvents = async () => {
  const leagueId = leagueStore.selectedLeague?.id ?? null
  const selectedRegion = betRegionsStore.selectedBetRegion?.name
  const selectedFormat = oddsFormatStore.selectedFormat
  const currentPage = paginationStore.currentPage
  const searchName = eventFiltersStore.searchName || ''
  const selectedDateKeyword = eventFiltersStore.selectedDateKeyword || ''

  await eventsStore.fetchEvents(
    leagueId,
    selectedRegion,
    selectedFormat,
    currentPage,
    10, // limit
    searchName,
    selectedDateKeyword
  )
}


const debouncedFetchEvents = debounce(tryFetchEvents, 500) //0,5s debounce delay



watch(
  [
    () => betRegionsStore.selectedBetRegion,
    () => oddsFormatStore.selectedFormat,
    () => eventFiltersStore.searchName,
    () => eventFiltersStore.selectedDateKeyword
  ],
  () => {
    paginationStore.resetPage()
    debouncedFetchEvents()
  }
)

watch(
  () => leagueStore.selectedLeague?.id,
  () => {
    paginationStore.resetPage()
    eventFiltersStore.clearFilters()
    tryFetchEvents()
  },
  { immediate: true }
)

watch(() => paginationStore.currentPage, () => {
  tryFetchEvents()
})

onMounted(() => {
  tryFetchEvents()
})


const visibleOutcomesCount = ref({})

const initVisibleOutcomes = (eventId) => {
  if (!(eventId in visibleOutcomesCount.value)) {
    visibleOutcomesCount.value[eventId] = 5
  }
}

const showMoreOutcomes = (eventId) => {
  visibleOutcomesCount.value[eventId] += 5
}
const showLessOutcomes = (eventId) => {
  visibleOutcomesCount.value[eventId] = Math.max(5, visibleOutcomesCount.value[eventId] - 5)
}
console.log(visibleOutcomesCount.value)
</script>

<template>
  <div class="container my-4">
    <h4 class="mb-4">{{eventFiltersStore.dateKeywordOptions.find(option => option.value ===
      eventFiltersStore.selectedDateKeyword)?.label}} {{ leagueStore.selectedLeague?.name || '' }} Events</h4>
    <div v-if="eventsStore.isLoading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    <div v-else-if="eventsStore.events.length > 0">
      <div class="row g-4">
        <div v-for="event in eventsStore.events" :key="event.id" class="col-md-6 col-lg-4">
          <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column">
              <div>
                <div v-if="event.sport && (event.sport.name || event.sport.logoPath)"
                  class="d-flex align-items-center mb-1">
                  <img v-if="event.sport.logoPath" :src="event.sport.logoPath"
                    :alt="event.sport.name ? event.sport.name + ' icon' : 'Sport icon'" class="me-2"
                    style="max-height: 20px; width: auto; vertical-align: middle;">
                  <i v-else-if="event.sport.iconClass" :class="'me-2'"
                    style="font-size: 1.2em; line-height: 1; vertical-align: middle;"></i>
                  <p v-if="event.sport.name" class="card-text text-muted small mb-0">
                    {{ event.sport.name }}
                  </p>
                </div>
                <div v-if="(event.league && event.league.name) || (event.region && event.region.logoPath)"
                  class="d-flex align-items-center mb-1">
                  <img v-if="event.region && event.region.logoPath" :src="event.region.logoPath"
                    :alt="event.region.name ? event.region.name + ' logo' : 'Region logo'" class="me-2"
                    style="max-height: 16px; width: auto; vertical-align: middle;">
                  <p v-if="event.league && event.league.name" class="card-text text-muted small mb-0">
                    {{ event.league.name }}
                  </p>
                </div>
                <!-- <p v-if="event.region && event.region.name" class="card-text text-muted mb-2 small">
                  <i class="bi bi-geo-alt"></i> {{ event.region.name }}
                </p> -->
                <h5 v-if="event.homeTeam && event.awayTeam" class="card-title mt-1">{{ event.homeTeam }} vs {{
                  event.awayTeam }}</h5>
                <p class="card-text text-muted mb-2">
                  <i class="bi bi-calendar-event"></i> {{ formatDateTime(event.commenceTime) }}
                </p>
              </div>

              <div class="mt-auto">
                <h6 class="mt-3">Best Odds:</h6>
                <div v-if="event.bestOutcomes && event.bestOutcomes.length > 0">
                  <div v-if="!visibleOutcomesCount[event.id]" >
                    {{ initVisibleOutcomes(event.id) }}
                  </div>

                  <ul class="list-unstyled">
                    <li v-for="(outcome, index) in event.bestOutcomes.slice(0, visibleOutcomesCount[event.id])"
                      :key="outcome.name + outcome.bookmaker.name + outcome.price" class="mb-1">
                      <strong>{{ outcome.name }}</strong> -
                      <span class="text-success">{{ outcome.price }}</span>
                      <small class="text-muted">({{ outcome.bookmaker.name }})</small>
                      <br />
                      <small class="text-muted">Updated: {{ formatDateTime(outcome.lastUpdate) }}</small>
                    </li>
                  </ul>

                  <div class="mt-2 d-flex gap-2">
                    <button v-if="event.bestOutcomes.length > visibleOutcomesCount[event.id]"
                      class="btn btn-sm btn-outline-primary" @click="showMoreOutcomes(event.id)">
                      Show more
                    </button>
                    <button v-if="visibleOutcomesCount[event.id] > 5" class="btn btn-sm btn-outline-secondary"
                      @click="showLessOutcomes(event.id)">
                      Show less
                    </button>
                  </div>
                </div>
                <div v-else class="text-muted fst-italic">
                  No odds available for this event.
                </div>
                <div v-else class="text-muted fst-italic">
                  No odds available for this event.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <BasePagination />
    </div>
    <div v-else class="text-center text-muted py-5">
      <i class="bi bi-emoji-frown fs-1 d-block mb-3"></i>
      No events found for this league or matching your filters.
    </div>
  </div>
</template>