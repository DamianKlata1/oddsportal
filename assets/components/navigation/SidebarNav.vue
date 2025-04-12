<template>

    <!-- Sidebar -->
    <div class="bg-light p-3 border-end" style="width: 250px; height: 100vh; overflow-y: auto;">
      <h5 class="mb-3">Kraje</h5>
      <div v-for="(country, index) in countries" :key="index">
        <button
            class="btn w-100 text-start mb-2"
            :class="{'btn-outline-primary': expandedCountry !== index, 'btn-primary': expandedCountry === index}"
            @click="toggleCountry(index)"
        >
          {{ country.name }}
        </button>
        <div v-if="expandedCountry === index" class="ms-3 mt-2">
          <ul class="list-unstyled">
            <li v-for="(league, lIndex) in country.leagues" :key="lIndex">
              <a href="#" class="text-decoration-none">{{ league }}</a>
            </li>
          </ul>
        </div>
      </div>
    </div>


</template>
<script setup>
import { ref } from 'vue'

const countries = ref([
  { name: 'Polska 🇵🇱', leagues: ['Ekstraklasa', 'I Liga', 'II Liga'] },
  { name: 'Anglia 🇬🇧', leagues: ['Premier League', 'Championship', 'League One'] },
  { name: 'Niemcy 🇩🇪', leagues: ['Bundesliga', '2. Bundesliga'] },
])

const expandedCountry = ref(null)

function toggleCountry(index) {
  expandedCountry.value = expandedCountry.value === index ? null : index
}
</script>
