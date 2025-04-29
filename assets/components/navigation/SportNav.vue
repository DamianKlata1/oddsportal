<script setup>
import { onMounted } from 'vue'
import { useSportsStore } from '/assets/stores/sports.js';

const store = useSportsStore()

onMounted(() => {
  store.fetchSports()
  console.log(store.sports)
})

function selectSport(sport) {
  store.selectSport(sport)
}
</script>

<template>
  <div class="d-flex gap-3 flex-wrap p-3">
    <div v-if="store.errorMessage">{{ store.errorMessage }}</div>
    <div
        v-for="sport in store.sports"
        :key="sport.id"
        @click="selectSport(sport)"
        class="card text-center p-2"
        :class="{ 'border-primary': store.selectedSport?.id === sport.id }"
        style="cursor: pointer; width: 120px;"
    >
      <img
          :src="sport.logoPath"
          alt="sport icon"
          style="height: 40px; object-fit: contain;"
      />
      <div class="mt-2">{{ sport.name }}</div>
    </div>
  </div>
</template>