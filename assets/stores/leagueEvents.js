import { defineStore } from 'pinia'
import { ref } from 'vue'
import apiPublic from '/assets/api/apiPublic.js'

export const useLeagueEventsStore = defineStore('leagueEvents', () => {
    const leagueEvents = ref([])
    const isLoading = ref(false)
    const errorMessage = ref(null)

    async function fetchLeagueEvents(leagueId, betRegion) {
        leagueEvents.value = await getLeagueEvents(leagueId, betRegion)
    }

    async function getLeagueEvents(leagueId, betRegion) {
        isLoading.value = true
        try {
            const response = await apiPublic().get(`/api/events/${leagueId}`, {
                params: {
                    betRegion: betRegion,
                    market: 'h2h',
                    priceFormat: 'decimal',
                }
            })
            return response.data
        } catch (error) {
            errorMessage.value = error.message
            return []
        } finally {
            isLoading.value = false
        }
    }

    return { leagueEvents, fetchLeagueEvents, isLoading, errorMessage }
})