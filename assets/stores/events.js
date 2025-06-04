import { defineStore } from 'pinia'
import { ref } from 'vue'
import apiPublic from '/assets/api/apiPublic.js'
import { usePaginationStore } from '/assets/stores/pagination.js'


export const useEventsStore = defineStore('events', () => {
  const events = ref([])
  const isLoading = ref(false)
  const errorMessage = ref(null)

  async function fetchEvents(
    leagueId = null,
    betRegion = 'eu',
    priceFormat = 'decimal',
    page = 1,
    limit = 10,
    nameFilter = '',
    dateKeywordFilter = ''
  ) {
    events.value = await getEvents(leagueId, betRegion, priceFormat, page, limit, nameFilter, dateKeywordFilter)
  }

  async function getEvents(leagueId, betRegion, priceFormat, page, limit, nameFilter, dateKeywordFilter) {
    isLoading.value = true
    const paginationStore = usePaginationStore()

    try {
      const params = new URLSearchParams();
      if (betRegion) {
        params.append('betRegion', betRegion);
      }
      if (priceFormat) {
        params.append('priceFormat', priceFormat);
      }
      params.append('page', page);
      params.append('limit', limit);

      if (leagueId) {
        params.append('leagueId', leagueId);
      }
      if (nameFilter) {
        params.append('name', nameFilter);
      }
      if (dateKeywordFilter) { // If a keyword is selected (not an empty string)
        params.append('date', dateKeywordFilter); // Backend expects 'date' query param
      }
      const response = await apiPublic().get(`/api/events`, { params })
      const data = response.data
      paginationStore.setTotalPages(data.pagination.pages || 1)
      return data.events || []
    } catch (error) {
      errorMessage.value = error.message
      return []
    } finally {
      isLoading.value = false
    }
  }

  return { events, fetchEvents, isLoading, errorMessage }
}, {
  persist: {
    paths: [''],
  }
})