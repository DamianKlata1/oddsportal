<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { useRouter } from 'vue-router';
import { useSportsStore } from '/assets/stores/sports.js';
import { useRegionsStore } from "/assets/stores/regions.js";
import { useLeaguesStore } from '/assets/stores/leagues.js';
import useUserStore from '/assets/stores/user.js';
import * as bootstrap from 'bootstrap'

const searchQuery = ref('')
const sportsStore = useSportsStore()
const regionsStore = useRegionsStore()
const leaguesStore = useLeaguesStore()
const userStore = useUserStore()
const router = useRouter()

onMounted(async () => {
  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
  tooltipTriggerList.forEach(el => {
    new bootstrap.Tooltip(el)
  })
  if (userStore.isAuth) {
    await leaguesStore.fetchFavoriteLeagues()
  }

})
watch(
  () => sportsStore.selectedSport,
  async (sport) => {
    if (sport) {
      await regionsStore.fetchRegionsForSport(sport.id)
    }
  },
  { immediate: true }
)


const filteredRegions = computed(() => {
  const query = searchQuery.value.toLowerCase().trim()

  return regionsStore.regions
    .map(region => {
      const filteredLeagues = region.leagues.filter(league =>
        league.name.toLowerCase().includes(query)
      )
      const matchesRegion = region.name.toLowerCase().includes(query)

      if (filteredLeagues.length > 0 || matchesRegion) {
        return {
          ...region,
          leagues: matchesRegion ? region.leagues : filteredLeagues
        }
      }

      return null
    })
    .filter(Boolean)
})

const handleToggleFavorite = (league) => {
  if (!userStore.isAuth) {
    router.push({ name: 'login' })
    return
  }

  if (leaguesStore.isFavorite(league)) {
    leaguesStore.removeFavoriteLeague(league)
  } else {
    leaguesStore.addFavoriteLeague(league)
  }
}




</script>
<template>
  <div class=" p-3 border-end" style="width: 300px; height: 100vh; overflow-y: auto;">
    <h5 class="mb-2">⭐ {{ $t('favorite_leagues') }}</h5>
    <ul v-if="leaguesStore.favoriteLeagues.length > 0" class="list-unstyled mb-4">
      <li v-for="league in leaguesStore.favoriteLeagues" :key="league.id">
        <a href="#" @click.prevent="leaguesStore.selectLeague(league)">
          <span class="me-2">
            <img :src="league.logoPath" alt="">
          </span>
          {{ league.name }}
        </a>
        <i class="bi" :class="leaguesStore.isFavorite(league) ? 'bi-star-fill text-warning' : 'bi-star'" role="button"
          data-bs-toggle="tooltip" data-bs-placement="top"
          :title="leaguesStore.isFavorite(league) ? $t('remove_from_favorites') : $t('add_to_favorites')"
          @click="handleToggleFavorite(league)" />
      </li>
    </ul>
    <div v-else class="text-muted fst-italic">
      {{ $t('no_favorite_leagues_available') }}
    </div>
    <hr class="mb-3">
    <h5 class="mb-3">{{ $t('choose_region') }}</h5>
    <input v-model="searchQuery" type="text" class="form-control mb-3" :placeholder="`${$t('search')}...`" />

    <div v-if="regionsStore.regions.length > 0" class="accordion" id="countryAccordion">
      <div class="accordion-item" v-for="(region, index) in filteredRegions" :key="region.name">
        <h2 class="accordion-header" :id="'heading' + index">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            :data-bs-target="'#collapse' + index" aria-expanded="false" :aria-controls="'collapse' + index">
            <span class="me-2"><img :src="region.logoPath" alt="region logo"></span> {{ region.name }}
          </button>
        </h2>
        <div :id="'collapse' + index" class="accordion-collapse collapse" :aria-labelledby="'heading' + index"
          data-bs-parent="#countryAccordion">
          <div class="accordion-body">
            <ul class="list-unstyled mb-0">
              <li v-for="league in region.leagues" :key="league">
                <span class="me-2"><img :src="league.logoPath" alt="league logo"></span>
                <a href="#" class="text-decoration-none text-success me-2"
                  @click.prevent="leaguesStore.selectLeague(league)">
                  {{
                    league.name
                  }}</a>
                <i class="bi" :class="leaguesStore.isFavorite(league) ? 'bi-star-fill text-warning' : 'bi-star'"
                  role="button" data-bs-toggle="tooltip" data-bs-placement="top"
                  :title="leaguesStore.isFavorite(league) ? $t('remove_from_favorites') : $t('add_to_favorites')"
                  @click="handleToggleFavorite(league)" />
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="text-muted fst-italic">
      {{ $t('no_regions_available_for_this_sport') }}
    </div>
  </div>
</template>
