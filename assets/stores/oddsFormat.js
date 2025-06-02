import { defineStore } from 'pinia'
import { ref } from 'vue'
import apiPublic from '/assets/api/apiPublic.js'

export const useOddsFormatStore = defineStore('oddsFormat', () => {
    const formats = ref([])
    const selectedFormat = ref(null)
    const isLoading = ref(false)
    const isLoaded = ref(false)
    const errorMessage = ref(null)

    const selectByName = (name) => {
        const format = formats.value.find(f => f.name === name)
        if (format) {
            selectedFormat.value = format
        } else {
            selectedFormat.value = null
        }
    }

    async function fetchFormats() {
        if (isLoaded.value || isLoading.value || formats.value.length > 0) {
            return 
        }
        formats.value = await getFormats()
    }

    async function getFormats() {
        isLoading.value = true
        try {
            const response = await apiPublic().get('/api/price-formats');
            isLoaded.value = true
            return response.data
        } catch (error) {
            errorMessage.value = error.message
            return []
        } finally {
            isLoading.value = false
        }
    }
    return { formats, selectByName, selectedFormat, fetchFormats, isLoading, errorMessage }
}, {
    persist: {
        paths: ['selectedFormat'], // only persist the data, not loading/error
    }
})