
import { defineStore } from 'pinia'
import { ref } from 'vue'
import apiPublic from '/assets/api/apiPublic.js';


export const useSportsStore = defineStore('sports', () => {
    const sports = ref([])
    const selectedSport = ref(null)
    const isLoading = ref(false)
    const errorMessage = ref(null)

    async function fetchSports() {
        sports.value = await getSportsFromApi()
        if (sports.value.length > 0) {
            selectedSport.value = sports.value[0]
        }
    }

    function selectSport(sport) {
        selectedSport.value = sport
    }

    async function getSportsFromApi() {
        isLoading.value = true
        try {
            const response = await apiPublic().get('/sports')
            return response.data
        } catch (error) {
            errorMessage.value = error.message
            return []
        } finally {
            isLoading.value = false
        }
    }

    return {sports, fetchSports, selectSport, selectedSport, isLoading, errorMessage}
})