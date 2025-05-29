import { defineStore } from 'pinia'
import { ref } from 'vue'
import apiPublic from '/assets/api/apiPublic.js'

export const useOddsFormatStore = defineStore('oddsFormat', () => {
    const formats = ref([])
    const selectedFormat = ref(null)
    const isLoading = ref(false)
    const errorMessage = ref(null)

    async function fetchFormats() {
        formats.value = await getFormats()
    }

    async function getFormats() {
        isLoading.value = true
                try {
                    const response = await apiPublic().get('/api/price-formats');
                    return response.data
                } catch (error) {
                    errorMessage.value = error.message
                    return []
                } finally {
                    isLoading.value = false
                }
    }
    return { formats, selectedFormat, fetchFormats, isLoading, errorMessage }
}, {
    persist: {
        paths: ['selectedFormat'], // only persist the data, not loading/error
    }
})