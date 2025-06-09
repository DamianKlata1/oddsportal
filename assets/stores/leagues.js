import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useRegionsStore } from './regions'
import useUserStore from './user.js'
import { useRouter } from 'vue-router'
import apiPrivate from '../api/apiPrivate.js'

export const useLeaguesStore = defineStore('leagues', () => {
    const selectedLeague = ref(null)
    const favoriteLeagues = ref([])
    const isLoading = ref(false)
    const errorMessage = ref(null)

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
    const isFavorite = (league) => {
        return favoriteLeagues.value.some(favLeague => favLeague.id === league.id)
    }
    const addFavoriteLeague = async (league) => {
        try {
            const response = await apiPrivate().put('/api/me/favorite-leagues/' + league.id)
            if (!isFavorite(league)) {
                favoriteLeagues.value.push(league)
            }
        } catch (error) {
            errorMessage.value = error.message
            return []
        }
    }
    const removeFavoriteLeague = async (league) => {
        try {
            const response = await apiPrivate().delete('/api/me/favorite-leagues/' + league.id)
            favoriteLeagues.value = favoriteLeagues.value.filter(
                favLeague => favLeague.id !== league.id
            )
        } catch (error) {
            errorMessage.value = error.message
            return []
        }
    }
    const fetchFavoriteLeagues = async () => {
        favoriteLeagues.value = await getFavoriteLeagues()
    }
    const getFavoriteLeagues = async () => {
        isLoading.value = true
        try {
            const response = await apiPrivate().get('/api/me/favorite-leagues')
            return response.data
        } catch (error) {
            if (error.response && error.response.status === 401) {
                useUserStore().resetState()
                useRouter().push({ name: 'login' })
            } else {
                errorMessage.value = error.message
            }
            return []
        } finally {
            isLoading.value = false
        }
    }

    return { selectedLeague, selectLeague, selectById, favoriteLeagues, isFavorite, fetchFavoriteLeagues, addFavoriteLeague, removeFavoriteLeague }
},
    {
        persist: {
            paths: [''],
        }
    })