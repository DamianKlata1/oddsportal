<script setup>
import { watch, ref } from 'vue'
import { useEventsStore } from '/assets/stores/events.js'
import { useBetRegionsStore } from '/assets/stores/betRegions.js'
import { useOddsFormatStore } from '/assets/stores/oddsFormat'
import { usePaginationStore } from '/assets/stores/pagination'
import { useEventFiltersStore } from '/assets/stores/eventFilters.js'
import { useLeaguesStore } from '/assets/stores/leagues'
import { useSportsStore } from '/assets/stores/sports.js'
import { useUrlSync } from '/assets/composables/useUrlSync.js';
import { formatRelativeTime } from '/assets/helpers/formatters.js'
import { useI18n } from 'vue-i18n';
import BasePagination from '/assets/components/Pagination.vue'
import debounce from 'lodash/debounce'
import EventCard from './EventCard.vue'
import { PAGE_LIMIT } from '/assets/config/pagination'
import Toast from '/assets/tools/Toast.js';

const { t, locale } = useI18n();

const stores = {
  eventsStore: useEventsStore(),
  eventFiltersStore: useEventFiltersStore(),
  betRegionsStore: useBetRegionsStore(),
  oddsFormatStore: useOddsFormatStore(),
  paginationStore: usePaginationStore(),
  leaguesStore: useLeaguesStore(),
  sportsStore: useSportsStore(),
};
const shouldFetchOnPageChange = ref(true)
const refreshingEventId = ref(null);

const tryFetchEvents = async () => {
  await stores.eventsStore.fetchEvents({
    leagueId: stores.leaguesStore.selectedLeague?.id ?? null,
    sportId: stores.sportsStore.selectedSport?.id ?? null,
    betRegion: stores.betRegionsStore.selectedBetRegion?.name,
    priceFormat: stores.oddsFormatStore.selectedFormat,
    page: stores.paginationStore.currentPage,
    limit: PAGE_LIMIT,
    nameFilter: stores.eventFiltersStore.searchName || '',
    dateKeywordFilter: stores.eventFiltersStore.selectedDateKeyword || '',
  })
}
const { isApplyingUrlToStores, pushUrlQuery } = useUrlSync(stores, tryFetchEvents);

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


const handleRefreshOutcomes = async (event) => {
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
}



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
          <EventCard :key="event.id" :event="event" :handleRefreshOutcomes="handleRefreshOutcomes"
            :refreshingEventId="refreshingEventId" />
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