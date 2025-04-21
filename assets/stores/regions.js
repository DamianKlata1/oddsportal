import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useRegionsStore = defineStore('regions', () => {
    const regions = ref([])

    async function fetchRegionsForSport(sportId) {
        // zamień na prawdziwe API
        regions.value = await getRegionsForSport(sportId)
    }

    async function getRegionsForSport(sportId) {
        if (sportId === 1) {
            return [
                { name: 'Poland', logo: '🇵🇱', leagues: ['Ekstraklasa'] },
                { name: 'England', logo: '🇬🇧', leagues: ['Premier League'] }
            ]
        }
        if (sportId === 2) {
            return [
                { name: 'USA', logo: '🇺🇸', leagues: ['NBA'] }
            ]
        }
        return []
    }

    return { regions, fetchRegionsForSport }
})