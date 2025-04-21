
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useSportsStore = defineStore('sports', () => {
    const sports = ref([])
    const selectedSport = ref(null)

    async function fetchSports() {
        sports.value = await getSportsFromApi()
        if (sports.value.length > 0) {
            selectedSport.value = sports.value[0]
        }
    }

    function selectSport(sport) {
        selectedSport.value = sport
    }

    // Mock API — zamień na prawdziwe
    async function getSportsFromApi() {
        return [
            {id: 1, name: 'Football', icon: 'https://www.thesportsdb.com/images/icons/sports/soccer.png'},
            {id: 2, name: 'Basketball', icon: 'https://www.thesportsdb.com/images/icons/sports/soccer.png'},
            {id: 3, name: 'Tennis', icon: 'https://www.thesportsdb.com/images/icons/sports/soccer.png'}
        ]
    }

    return {sports, selectedSport, fetchSports, selectSport}
})