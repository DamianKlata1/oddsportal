import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useRegionsStore } from './regions'

export const useLeagueStore = defineStore('league', () => {
    const selectedLeague = ref(null)

    function selectLeague(league) {
        selectedLeague.value = league
    }
    const selectById = (id) => {
        const allLeagues = useRegionsStore().regions.flatMap(region => region.leagues || [])

        const league = allLeagues.find(league => league.id === id)

        if (league) {
            selectedLeague.value = league
        } else {
            selectedLeague.value = null
        }
    }
    return { selectedLeague, selectLeague, selectById }
},
{
    persist: {
        paths: [''],
    }
})