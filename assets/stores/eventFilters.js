import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import apiPublic from '/assets/api/apiPublic.js'
import { formatDateKeywordLabel } from '/assets/helpers/formatters.js'

// helper function to format keyword labels


export const useEventFiltersStore = defineStore('eventFilters', () => {
  const isLoading = ref(false)
  const errorMessage = ref(null)

  const searchName = ref('')
  const selectedDateKeyword = ref('')
  const dateKeywords = ref([])

  // Options with label for frontend display
const dateKeywordOptions = computed(() => [
  { value: '', label: 'Any date' },
  ...dateKeywords.value.map(value => ({
    value,
    label: formatDateKeywordLabel(value),
  }))
])

  const clearFilters = () => {
    searchName.value = ''
    selectedDateKeyword.value = ''
  }

  const fetchDateKeywords = async () => {
    dateKeywords.value = await getDateKeywords()
  }

  const getDateKeywords = async () => {
    isLoading.value = true
    try {
      const response = await apiPublic().get('/api/date-filter-keywords')
      return response.data
    } catch (error) {
      errorMessage.value = error.message
      return []
    } finally {
      isLoading.value = false
    }
  }

  return {
    searchName,
    selectedDateKeyword,
    dateKeywordOptions,
    fetchDateKeywords,
    clearFilters,
    isLoading,
    errorMessage,
  }
})
