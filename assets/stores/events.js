import { defineStore } from 'pinia'
import { ref } from 'vue'
import apiPublic from '/assets/api/apiPublic.js'
import { usePaginationStore } from '/assets/stores/pagination.js'


export const useEventsStore = defineStore('events', () => {
  const events = ref([])
  const isLoading = ref(false)
  const errorMessage = ref(null)

  async function refreshEventOdds(eventId, betRegion, priceFormat) {
    try {
      const params = new URLSearchParams({ betRegion, priceFormat });
      const response = await apiPublic().get(`/api/events/${eventId}/best-outcomes`, { params });

      const responseData = response.data;
      const newOutcomes = responseData.outcomes;
      const eventIndex = events.value.findIndex(e => e.id === eventId);
      if (eventIndex !== -1) {
        events.value[eventIndex].bestOutcomes = newOutcomes;
      }
      return responseData;
    } catch (error) {
      errorMessage.value = error.message;
      throw error; 
    }
  }
  async function fetchEvents({
    leagueId = null,
    sportId = null,
    betRegion = 'eu',
    priceFormat = 'decimal',
    page = 1,
    limit = 10,
    nameFilter = '',
    dateKeywordFilter = ''
  }) {
    events.value = await getEvents({ leagueId, sportId, betRegion, priceFormat, page, limit, nameFilter, dateKeywordFilter })
  }

  async function getEvents({ leagueId, sportId, betRegion, priceFormat, page, limit, nameFilter, dateKeywordFilter }) {
    isLoading.value = true
    const paginationStore = usePaginationStore()

    try {
      const params = new URLSearchParams();
      if (betRegion) {
        params.append('betRegion', betRegion);
      }
      if (sportId) {
        params.append('sportId', sportId);
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
      if (dateKeywordFilter && dateKeywordFilter !== "upcoming") {
        params.append('date', dateKeywordFilter);
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

  return { events, fetchEvents, isLoading, errorMessage, refreshEventOdds }
}, {
  persist: {
    paths: [''],
  }
})