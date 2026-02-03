<script setup lang="ts">
interface PaginationMeta {
  current_page: number
  last_page: number
  per_page: number
  total: number
  from: number
  to: number
}

const props = defineProps<{
  meta: PaginationMeta
}>()

const emit = defineEmits<{
  'page-change': [page: number]
}>()

const currentPage = computed(() => props.meta.current_page)
const lastPage = computed(() => props.meta.last_page)

const pages = computed(() => {
  const total = lastPage.value
  const current = currentPage.value
  const delta = 2
  const range: number[] = []

  for (let i = Math.max(2, current - delta); i <= Math.min(total - 1, current + delta); i++) {
    range.push(i)
  }

  if (current - delta > 2) {
    range.unshift(-1)
  }
  if (current + delta < total - 1) {
    range.push(-1)
  }

  range.unshift(1)
  if (total > 1) {
    range.push(total)
  }

  return range
})

const goToPage = (page: number) => {
  if (page >= 1 && page <= lastPage.value && page !== currentPage.value) {
    emit('page-change', page)
  }
}
</script>

<template>
  <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
    <div class="flex flex-1 justify-between sm:hidden">
      <button :disabled="currentPage === 1"
        class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
        @click="goToPage(currentPage - 1)">
        Anterior
      </button>
      <button :disabled="currentPage === lastPage"
        class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
        @click="goToPage(currentPage + 1)">
        Siguiente
      </button>
    </div>

    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700">
          Mostrando
          <span class="font-medium">{{ meta.from }}</span>
          a
          <span class="font-medium">{{ meta.to }}</span>
          de
          <span class="font-medium">{{ meta.total }}</span>
          resultados
        </p>
      </div>
      <div>
        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm">
          <button :disabled="currentPage === 1"
            class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:opacity-50"
            @click="goToPage(currentPage - 1)">
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                clip-rule="evenodd" />
            </svg>
          </button>

          <template v-for="(page, index) in pages" :key="index">
            <span v-if="page === -1"
              class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300">
              ...
            </span>
            <button v-else :class="[
              'relative inline-flex items-center px-4 py-2 text-sm font-semibold ring-1 ring-inset ring-gray-300 hover:bg-gray-50',
              page === currentPage ? 'z-10 bg-blue-600 text-white' : 'text-gray-900'
            ]" @click="goToPage(page)">
              {{ page }}
            </button>
          </template>

          <button :disabled="currentPage === lastPage"
            class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:opacity-50"
            @click="goToPage(currentPage + 1)">
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                clip-rule="evenodd" />
            </svg>
          </button>
        </nav>
      </div>
    </div>
  </div>
</template>
