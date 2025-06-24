<script setup>
import { onMounted, watch, ref, nextTick } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useEventsStore } from '/assets/stores/events.js'
import { useBetRegionsStore } from '/assets/stores/betRegions.js'
import { useOddsFormatStore } from '/assets/stores/oddsFormat'
import { usePaginationStore } from '/assets/stores/pagination'
import { useEventFiltersStore } from '/assets/stores/eventFilters.js'
import { useLeaguesStore } from '/assets/stores/leagues'
import { useSportsStore } from '/assets/stores/sports.js'
import { formatDateTime } from '/assets/helpers/formatters.js'
import BasePagination from '/assets/components/Pagination.vue'
import debounce from 'lodash/debounce'
import isEqual from 'lodash/isEqual'
import Toast from '/assets/tools/Toast.js';
import { useI18n } from 'vue-i18n';
import { formatRelativeTime } from '../helpers/formatters.js'

const { t, locale } = useI18n();

const route = useRoute()
const router = useRouter()

const stores = {
  eventsStore: useEventsStore(),
  eventFiltersStore: useEventFiltersStore(),
  betRegionsStore: useBetRegionsStore(),
  oddsFormatStore: useOddsFormatStore(),
  paginationStore: usePaginationStore(),
  leaguesStore: useLeaguesStore(),
  sportsStore: useSportsStore(),
};
const isApplyingUrlToStores = ref(false);
const shouldFetchOnPageChange = ref(true)

const refreshingEventId = ref(null);


const buildQueryParams = () => {
  const query = {};
  if (stores.leaguesStore.selectedLeague?.id) {
    query.league = String(stores.leaguesStore.selectedLeague.id);
  }
  if (stores.sportsStore.selectedSport?.id) {
    query.sport = String(stores.sportsStore.selectedSport.id);
  }
  if (stores.eventFiltersStore.searchName) {
    query.search = stores.eventFiltersStore.searchName;
  }
  if (stores.eventFiltersStore.selectedDateKeyword) {
    query.date = stores.eventFiltersStore.selectedDateKeyword;
  }
  if (stores.paginationStore.currentPage > 1) {
    query.page = String(stores.paginationStore.currentPage);
  }
  return query;
};

const pushUrlQuery = () => {
  if (isApplyingUrlToStores.value) return;
  const newQuery = buildQueryParams();
  // console.log('Pushing URL query:', newQuery,'to', route.query);
  if (!isEqual(newQuery, route.query)) {
    router.push({ query: newQuery }).catch(err => {
      if (err.name !== 'NavigationDuplicated') console.error('Router push error:', err);
    });
  }
};

const replaceUrlQuery = () => {
  const newQuery = buildQueryParams();
  if (!isEqual(newQuery, route.query)) {
    router.replace({ query: newQuery }).catch(err => {
      if (err.name !== 'NavigationDuplicated') console.error('Router replace error:', err);
    });
  }
};




const tryFetchEvents = async () => {

  await stores.eventsStore.fetchEvents({
    leagueId: stores.leaguesStore.selectedLeague?.id ?? null,
    sportId: stores.sportsStore.selectedSport?.id ?? null,
    betRegion: stores.betRegionsStore.selectedBetRegion?.name,
    priceFormat: stores.oddsFormatStore.selectedFormat,
    page: stores.paginationStore.currentPage,
    limit: 10,
    nameFilter: stores.eventFiltersStore.searchName || '',
    dateKeywordFilter: stores.eventFiltersStore.selectedDateKeyword || '',
  })
}


const debouncedFetchEvents = debounce(tryFetchEvents, 500) //0,5s debounce delay
const debouncedPushUrlQuery = debounce(pushUrlQuery, 500) //0,5s debounce delay

watch(
  [
    () => stores.betRegionsStore.selectedBetRegion,
    () => stores.oddsFormatStore.selectedFormat,
    () => stores.eventFiltersStore.selectedDateKeyword,
    () => stores.eventFiltersStore.searchName,
  ],
  () => {
    if (isApplyingUrlToStores.value) return;
    shouldFetchOnPageChange.value = false
    stores.paginationStore.resetPage();
    debouncedFetchEvents()
    debouncedPushUrlQuery()
  }
)
watch(
  () => route.query,
  (newQuery) => {
    const currentGeneratedQuery = buildQueryParams();

    if (!isEqual(newQuery, currentGeneratedQuery)) {
      // console.log('URL changed externally, applying to stores:', newQuery);
      applyQueryToStores(newQuery);
    }
  },
  { deep: true }
);

watch(
  () => stores.leaguesStore.selectedLeague?.id,
  (newLeagueId, oldLeagueId) => {
    if (newLeagueId !== oldLeagueId) {
      if (isApplyingUrlToStores.value) return;
      shouldFetchOnPageChange.value = false
      stores.paginationStore.resetPage();
      tryFetchEvents()
      pushUrlQuery()
    }
  }
)
watch(
  () => stores.sportsStore.selectedSport?.id,
  (newSportId, oldSportId) => {
    if (newSportId !== oldSportId) {
      if (isApplyingUrlToStores.value) return;
      shouldFetchOnPageChange.value = false
      stores.paginationStore.resetPage();
      stores.leaguesStore.selectById(null);
      tryFetchEvents()
      pushUrlQuery()
    }
  }
)

watch(() => stores.paginationStore.currentPage, (newPage, oldPage) => {
  if (isApplyingUrlToStores.value) return;
  if (newPage !== oldPage) {
    if (shouldFetchOnPageChange.value) tryFetchEvents();
    pushUrlQuery();
  }
})

const applyQueryToStores = async (querySource) => {
  isApplyingUrlToStores.value = true;

  if (stores.eventFiltersStore.dateKeywords?.length <= 1) await stores.eventFiltersStore.fetchDateKeywords();

  const urlLeagueId = querySource.league ? parseInt(querySource.league, 10) : null;
  if (stores.leaguesStore.selectedLeague?.id !== urlLeagueId) {
    stores.leaguesStore.selectById(urlLeagueId);
  }

  const urlSportId = querySource.sport ? parseInt(querySource.sport, 10) : null;
  if (stores.sportsStore.selectedSport?.id !== urlSportId) {
    stores.sportsStore.selectById(urlSportId);
  }

  stores.eventFiltersStore.searchName = querySource.search || '';

  const dateOption = stores.eventFiltersStore.dateKeywords.find(opt => opt === querySource.date);
  stores.eventFiltersStore.selectedDateKeyword = dateOption ? querySource.date : 'upcoming';

  const urlPage = querySource.page ? parseInt(querySource.page, 10) : 1;
  if (stores.paginationStore.currentPage !== urlPage) {
    stores.paginationStore.setPage(urlPage);
  }

  // Use nextTick to ensure all state updates from above are processed before clearing the flag
  await nextTick();
  isApplyingUrlToStores.value = false;

  replaceUrlQuery();
  tryFetchEvents();
};

onMounted(async () => {
  await applyQueryToStores(route.query);
});

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


const handleRefreshOdds = async (event) => {
  if (refreshingEventId.value !== null) return;

  refreshingEventId.value = event.id;

  try {
    const selectedRegion = stores.betRegionsStore.selectedBetRegion?.name;
    const selectedFormat = stores.oddsFormatStore.selectedFormat;

    const response = await stores.eventsStore.refreshEventOdds(event.id, selectedRegion, selectedFormat);
    if (response && response.syncStatus.syncRequired) {
      Toast(t('odds_refreshed_successfully'), 'success');
    } else if (response && !response.syncStatus.syncRequired) {
      const timeUntil = formatRelativeTime(response.syncStatus.nextUpdateAllowedAt, locale.value);
      Toast(t('odds_refresh_too_soon', { time: timeUntil }), 'info');
    }
  } catch (error) {
    const errorMessage = error.response?.data?.message || t('odds_refreshing_error');
    Toast(errorMessage, 'error');
  } finally {
    refreshingEventId.value = null;
  }
};

</script>

<template>
  <div class="container my-4">
    <h4 class="mb-4">{{ $t(stores.eventFiltersStore.selectedDateKeyword || 'upcoming') }}: {{
      stores.leaguesStore.selectedLeague?.name
      || '' }}</h4>
    <div v-if="stores.eventsStore.isLoading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">{{ $t('loading') }}</span>
      </div>
    </div>
    <div v-else-if="stores.eventsStore.events.length > 0">
      <div class="row g-4">
        <div v-for="event in stores.eventsStore.events" :key="event.id" class="col-md-6 col-lg-4">
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
                <p class="card-text text-muted mb-2" :title="formatDateTime(event.commenceTime)">
                  <i class="bi bi-calendar-event"></i> {{ formatRelativeTime(event.commenceTime, locale) }}
                </p>
              </div>

              <div class="mt-auto">
                <div class="d-flex justify-content-between align-items-center mt-3">
                  <h6 class="mb-0">{{ $t('best_odds') }}:</h6>

                  <button class="btn btn-sm btn-outline-success p-1" @click="handleRefreshOdds(event)"
                    :disabled="refreshingEventId === event.id" data-bs-toggle="tooltip" data-bs-placement="top"
                    :title="$t('refresh')">

                    <span v-if="refreshingEventId === event.id" class="spinner-border spinner-border-sm" role="status"
                      aria-hidden="true"></span>

                    <i v-else class="bi bi-arrow-clockwise"></i>
                  </button>
                </div>
                <div v-if="event.bestOutcomes && event.bestOutcomes.length > 0">
                  <div v-if="!visibleOutcomesCount[event.id]">
                    {{ initVisibleOutcomes(event.id) }}
                  </div>

                  <ul class="list-unstyled">
                    <li v-for="(outcome, index) in event.bestOutcomes.slice(0, visibleOutcomesCount[event.id])"
                      :key="outcome.name + outcome.bookmaker.name + outcome.price" class="mb-1">
                      <strong>{{ outcome.name }}</strong> -
                      <span class="text-success">{{ outcome.price }}</span>
                      <small class="text-muted">({{ outcome.bookmaker.name }})</small>
                      <br />
                      <small class="text-muted" :title="formatDateTime(outcome.lastUpdate)">{{ $t('updated') }}: {{
                        formatRelativeTime(outcome.lastUpdate, locale) }}</small>
                    </li>
                  </ul>

                  <div class="mt-2 d-flex gap-2">
                    <button v-if="event.bestOutcomes.length > visibleOutcomesCount[event.id]"
                      class="btn btn-sm btn-outline-primary" @click="showMoreOutcomes(event.id)">
                      {{ $t('show_more') }}
                    </button>
                    <button v-if="visibleOutcomesCount[event.id] > 5" class="btn btn-sm btn-outline-secondary"
                      @click="showLessOutcomes(event.id)">
                      {{ $t('show_less') }}
                    </button>
                  </div>
                </div>
                <div v-else class="text-muted fst-italic">
                  {{ $t('no_odds_available_for_this_event') }}
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
      {{ $t('no_events_found') }}
    </div>
  </div>
</template>