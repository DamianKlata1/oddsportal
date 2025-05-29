import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useLeagueStore = defineStore('league', () => {
    const selectedLeague = ref(null)

    function selectLeague(league) {
        selectedLeague.value = league
    }
    return { selectedLeague, selectLeague }
}, )