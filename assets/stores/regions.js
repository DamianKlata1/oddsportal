import { defineStore } from 'pinia'
import { ref } from 'vue'
import apiPublic from '/assets/api/apiPublic.js'

export const useRegionsStore = defineStore('regions', () => {
    const regions = ref([])
    const selectedRegion = ref(null)
    const isLoading = ref(false)
    const errorMessage = ref(null)

    function selectRegion(region) {
        selectedRegion.value = region
    }

    async function fetchRegionsForSport(sportId) {
        regions.value = await getRegionsForSport(sportId)
       
    }

    async function getRegionsForSport(sportId) {
        isLoading.value = true
        try {
            const response = await apiPublic().get('/api/sports/' + sportId + '/regions')
            return response.data
        } catch (error) {
            errorMessage.value = error.message
            return []
        } finally {
            isLoading.value = false
        }
    }


    return { regions, fetchRegionsForSport, selectRegion, selectedRegion, isLoading, errorMessage }
},)