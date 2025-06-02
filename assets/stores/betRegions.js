import { defineStore } from 'pinia'
import { ref } from 'vue'
import apiPublic from '/assets/api/apiPublic.js'

export const useBetRegionsStore = defineStore('betRegions', () => {
    const betRegions = ref([])
    const selectedBetRegion = ref(null)
    const isLoading = ref(false)
    const isLoaded = ref(false)
    const errorMessage = ref(null)

    const selectByName = (name) => {
        const region = betRegions.value.find(r => r.name === name)
        if (region) {
            selectedBetRegion.value = region
        } else {
            selectedBetRegion.value = null
        }
    }

    async function fetchBetRegions() {
        if (isLoaded.value || isLoading.value || betRegions.value.length > 0) {
            return
        }
        betRegions.value = await getBetRegions()
    }


    async function getBetRegions() {
        isLoading.value = true
        try {
            const response = await apiPublic().get('/api/bet-regions')
            isLoaded.value = true
            return response.data
        } catch (error) {
            errorMessage.value = error.message
            return []
        } finally {
            isLoading.value = false
        }
    }
    return { betRegions, fetchBetRegions, selectByName, selectedBetRegion, isLoading, errorMessage }
}, {
    persist: {
        paths: ['selectedBetRegion'],
    }
})