import { defineStore } from 'pinia'
import { ref } from 'vue'
import apiPublic from '/assets/api/apiPublic.js'

export const useBetRegionsStore = defineStore('betRegions', () => {
    const betRegions = ref([])
    const selectedBetRegion = ref(null)
    const isLoading = ref(false)
    const errorMessage = ref(null)

    async function fetchBetRegions() {
        betRegions.value = await getBetRegions()
    }

    async function getBetRegions() {
        isLoading.value = true
                try {
                    const response = await apiPublic().get('/api/bet-regions')
                    return response.data
                } catch (error) {
                    errorMessage.value = error.message
                    return []
                } finally {
                    isLoading.value = false
                }
    }
    return { betRegions, fetchBetRegions, selectedBetRegion, isLoading, errorMessage }
}, {
    persist: {
        paths: ['selectedBetRegion'], // only persist the data, not loading/error
    }
})