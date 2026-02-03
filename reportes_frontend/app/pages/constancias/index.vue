<script setup lang="ts">
import StudentsTable from "~/components/StudentTable.vue";
import UiSearchInput from "~/components/UiSearchInput.vue";
import UiPagination from "~/components/UiPagination.vue";

const {
  students,
  isLoading,
  error,
  meta,
  fetchStudents
} = useStudents();

console.log("Students data in constancias.vue:", students.value);

const searchTerm = ref("");
const currentPage = ref(1);
const perPage = ref(15);

onMounted(async () => {
  console.log('onMounted: fetching students with page=1, perPage=15, searchTerm=""');
  await fetchStudents({ page: 1, perPage: 15, searchTerm: '' });
});

const debouncedSearch = useDebounceFn(async () => {
  currentPage.value = 1;
  await fetchStudents({
    page: 1,
    perPage: perPage.value,
    searchTerm: searchTerm.value || '',
  });
}, 500);

watch(searchTerm, debouncedSearch);

const handlePageChange = async (page: number) => {
  currentPage.value = page;
  await fetchStudents({
    page,
    perPage: perPage.value,
    searchTerm: searchTerm.value || '',
  });
};

const handlePerPageChange = async () => {
  currentPage.value = 1;
  await fetchStudents({
    page: 1,
    perPage: perPage.value,
    searchTerm: searchTerm.value || '',
  });
};

const handleRowClick = (carnet: string) => {
  navigateTo(`/constancias/${carnet}`);
};
</script>

<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Gestión de Constancias de Notas</h1>
        <p class="mt-2 text-sm text-gray-600">
          Consulta, descarga y envía constancias por correo
        </p>
      </div>

      <div v-if="error" class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <p class="text-sm text-red-700">{{ error }}</p>
      </div>

      <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between">
        <UiSearchInput v-model="searchTerm" placeholder="Buscar por carnet, nombre..." />

        <div class="flex items-center gap-2">
          <label for="perPage" class="text-sm font-medium text-gray-700">Mostrar:</label>
          <select id="perPage" v-model="perPage"
            class="rounded-md border border-gray-300 bg-white py-2 px-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            @change="handlePerPageChange">
            <option :value="10">10</option>
            <option :value="15">15</option>
            <option :value="25">25</option>
            <option :value="50">50</option>
          </select>
        </div>
      </div>

      <StudentsTable :students="students" :is-loading="isLoading" :meta="meta" @row-click="handleRowClick" />

      <!-- Control de paginación fuera de la tabla -->
      <div v-if="!isLoading && students.length > 0" class="mt-6">
        <UiPagination :meta="meta" @page-change="handlePageChange" />
      </div>
    </div>
  </div>
</template>
