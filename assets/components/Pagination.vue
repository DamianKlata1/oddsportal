<script setup>
import { usePaginationStore } from '/assets/stores/pagination'
const paginationStore = usePaginationStore()
const goToPage = (page) => {
  paginationStore.setPage(page)
}
</script>

<template>
  <div
    v-if="paginationStore.totalPages > 1"
    class="d-flex justify-content-center align-items-center my-4"
  >
    <button
      class="btn btn-outline-secondary me-2"
      :disabled="paginationStore.currentPage <= 1"
      @click="goToPage(paginationStore.currentPage - 1)"
    >
      Previous
    </button>

    <span>Page {{ paginationStore.currentPage }} of {{ paginationStore.totalPages }}</span>

    <button
      class="btn btn-outline-secondary ms-2"
      :disabled="paginationStore.currentPage >= paginationStore.totalPages"
      @click="goToPage(paginationStore.currentPage + 1)"
    >
      Next
    </button>
  </div>
</template>
