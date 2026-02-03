<script setup lang="ts">
import type { Student, PaginationMeta } from "~/types/students";

const props = defineProps<{
  students: Student[];
  isLoading: boolean;
  meta: PaginationMeta;
}>();

const emit = defineEmits<{
  "row-click": [carnet: string];
}>();


const sortColumn = ref<string>("carnet");
const sortDirection = ref<"asc" | "desc" | null>(null);


const sortedStudents = computed(() => {
  const students = [...props.students];

  if (!sortDirection.value) {
    return students;
  }

  students.sort((a, b) => {
    let comparison = 0;

    switch (sortColumn.value) {
      case "carnet":

        comparison = parseInt(a.carnet) - parseInt(b.carnet);
        break;
      case "name":
        comparison = a.full_name.localeCompare(b.full_name);
        break;
      case "cum":
        comparison = (a.cum || 0) - (b.cum || 0);
        break;
      case "career":
        comparison = (a.career || '').localeCompare(b.career || '');
        break;
      case "faculty":
        comparison = (a.faculty || '').localeCompare(b.faculty || '');
        break;
      case "subjects_count":
        comparison = (a.subjects_count || 0) - (b.subjects_count || 0);
        break;
    }

    return sortDirection.value === "asc" ? comparison : -comparison;
  });

  return students;
});

const handleSort = (column: string) => {
  if (sortColumn.value === column) {

    if (sortDirection.value === "asc") {
      sortDirection.value = "desc";
    } else if (sortDirection.value === "desc") {
      sortDirection.value = null;
    } else {
      sortDirection.value = "asc";
    }
  } else {

    sortColumn.value = column;
    sortDirection.value = "asc";
  }
};

const getSortIcon = (column: string) => {
  if (sortColumn.value !== column) return null;

  if (sortDirection.value === "asc") return "↑";
  if (sortDirection.value === "desc") return "↓";
  return null;
};
</script>

<template>
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" @click="handleSort('carnet')"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
              Carnet {{ getSortIcon('carnet') }}
            </th>
            <th scope="col" @click="handleSort('name')"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
              Nombre Completo {{ getSortIcon('name') }}
            </th>
            <th scope="col" @click="handleSort('cum')"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
              CUM {{ getSortIcon('cum') }}
            </th>
            <th scope="col" @click="handleSort('career')"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
              Carrera {{ getSortIcon('career') }}
            </th>
            <th scope="col" @click="handleSort('faculty')"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
              Facultad {{ getSortIcon('faculty') }}
            </th>
            <th scope="col" @click="handleSort('subjects_count')"
              class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
              Materias Cursadas {{ getSortIcon('subjects_count') }}
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <StudentTableSkeleton v-if="isLoading" />
          <template v-else-if="sortedStudents.length > 0">
            <StudentTableRow v-for="student in sortedStudents" :key="student.carnet" :student="student"
              @row-click="emit('row-click', $event)" />
          </template>
          <tr v-else>
            <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
              No se encontraron estudiantes
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
