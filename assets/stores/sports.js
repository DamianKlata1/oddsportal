
import { defineStore } from 'pinia'
import { ref } from 'vue'
import apiPublic from '/assets/api/apiPublic.js';


export const useSportsStore = defineStore('sports', () => {
    const sports = ref([])
    const selectedSport = ref(null)
    const isLoading = ref(false)
    const isLoaded = ref(false)
    const errorMessage = ref(null)

    async function fetchSports() {
        if (isLoaded.value) return
        sports.value = await getSportsFromApi()
    }

    function selectSport(sport) {
        selectedSport.value = sport
    }
    function selectById(sportId) {
        selectedSport.value = sports.value.find(sport => sport.id === sportId) || null
    }

    async function getSportsFromApi() {
        isLoading.value = true
        try {
            const response = await apiPublic().get('/api/sports')
            isLoaded.value = true
            return response.data
        } catch (error) {
            errorMessage.value = error.message
            return []
        } finally {
            isLoading.value = false
        }
    }

    return {sports, fetchSports, selectSport, selectById, selectedSport, isLoading, errorMessage}
}, {
    persist: {
        paths: ['selectedSport'], // only persist the data, not loading/error
    }
})