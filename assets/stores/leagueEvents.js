import { defineStore } from 'pinia'
import { ref } from 'vue'
import apiPublic from '/assets/api/apiPublic.js'

export const useLeagueEventsStore = defineStore('leagueEvents', () => {
    const leagueEvents = ref([])
    const isLoading = ref(false)
    const errorMessage = ref(null)



    async function fetchLeagueEvents(leagueId) {
        leagueEvents.value = await getLeagueEvents(leagueId)
    }

    async function getLeagueEvents(leagueId) {
        isLoading.value = true
        try {
            const response = {
                //some fake data for testing
                data: [
                    {
                        id: 1, homeTeam: 'Real Madrid', awayTeam: 'Barcelona', date: '2023-10-01', bestOdds: {
                            home: {
                                bookmaker: {
                                    name: 'Betfair',
                                    logo: 'https://example.com/betfair-logo.png'
                                },
                                odds: {
                                    value: 2.5,
                                    lastUpdated: '2023-09-30T12:00:00Z'
                                }
                            }, 
                            draw: {
                                bookmaker: {
                                    name: 'William Hill',
                                    logo: 'https://example.com/william-hill-logo.png'
                                },
                                odds: {
                                    value: 3.2,
                                    lastUpdated: '2023-09-30T12:00:00Z'
                                }
                            },
                            away: {
                                bookmaker: {
                                    name: 'Bet365',
                                    logo: 'https://example.com/bet365-logo.png'
                                },
                                odds: {
                                    value: 3.0,
                                    lastUpdated: '2023-09-30T12:00:00Z'
                                }
                            },
                        }
                    },
                    {
                        id: 2, homeTeam: 'Manchester City', awayTeam: 'Liverpool', date: '2023-10-02', bestOdds: {
                            home: {
                                bookmaker: {
                                    name: 'Pinnacle',
                                    logo: 'https://example.com/pinnacle-logo.png'
                                },
                                odds: {
                                    value: 1.8,
                                    lastUpdated: '2023-09-30T12:00:00Z'
                                }
                            }, 
                            draw: {
                                bookmaker: {
                                    name: 'Ladbrokes',
                                    logo: 'https://example.com/ladbrokes-logo.png'
                                },
                                odds: {
                                    value: 3.5,
                                    lastUpdated: '2023-09-30T12:00:00Z'
                                }
                            },
                            away: {
                                bookmaker: {
                                    name: 'Coral',
                                    logo: 'https://example.com/coral-logo.png'
                                },
                                odds: {
                                    value: 4.0,
                                    lastUpdated: '2023-09-30T12:00:00Z'
                                }
                            },
                        }
                    }
                ]
            }
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