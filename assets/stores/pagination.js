import { defineStore } from 'pinia'
import { ref } from 'vue'

export const usePaginationStore = defineStore('pagination', () => {
  const currentPage = ref(1)
  const totalPages = ref(1)

  function resetPage() {
    currentPage.value = 1
  }

  function setTotalPages(pages) {
    totalPages.value = pages
  }

  function setPage(page) {
    if (page >= 1) {
      currentPage.value = page
    }
  }

  return {
    currentPage,
    totalPages,
    resetPage,
    setTotalPages,
    setPage,
  }
})