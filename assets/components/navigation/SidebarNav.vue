<template>
  <div class=" p-3 border-end" style="width: 300px; height: 100vh; overflow-y: auto;">
    <h5 class="mb-3">Countries</h5>

    <!-- Wyszukiwarka -->
    <input v-model="searchQuery" type="text" class="form-control mb-3" placeholder="Search..." />

    <div class="accordion" id="countryAccordion">
      <div class="accordion-item" v-for="(region, index) in regionsStore.regions" :key="region.name">
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
                <span class="me-2"><img :src="region.logoPath" alt="league logo"></span>
                <a href="#" class="text-decoration-none text-success" @click.prevent="goToLeagueEvents(1)">
                  {{
                    league.name
                  }}</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { useSportsStore } from '/assets/stores/sports.js';
import { useRegionsStore } from "/assets/stores/regions.js";
import { useRouter } from 'vue-router';

const router = useRouter()
const sportsStore = useSportsStore()
const regionsStore = useRegionsStore()

watch(
  () => sportsStore.selectedSport,
  async (sport) => {
    if (sport) {
      await regionsStore.fetchRegionsForSport(sport.id)
    }
  },
  { immediate: true }
)

const goToLeagueEvents = (leagueId) => {
  router.push({ name: 'league_events', params: { leagueId } })
}

const searchQuery = ref('')


// const filteredEvents = computed(() => {
//   if (!searchQuery.value) return regions.value
//   const query = searchQuery.value.toLowerCase()
//   return regions.value.filter(c =>
//       c.name.toLowerCase().includes(query)
//   )
// })
</script>
