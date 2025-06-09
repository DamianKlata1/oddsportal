import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import apiPublic from '/assets/api/apiPublic.js'
import { formatDateKeywordLabel } from '/assets/helpers/formatters.js'

export const useEventFiltersStore = defineStore('eventFilters', () => {
  const isLoading = ref(false)
  const isLoaded = ref(false)
  const errorMessage = ref(null)

  const searchName = ref('')
  const selectedDateKeyword = ref('')
  const dateKeywords = ref([])
  
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

  async function fetchDateKeywords() {
    if (isLoaded.value || isLoading.value || dateKeywords.value.length > 0) return
    dateKeywords.value = await getDateKeywords()
  }

  const getDateKeywords = async () => {
    isLoading.value = true
    try {
      const response = await apiPublic().get('/api/date-filter-keywords')
      isLoaded.value = true
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
}
)
