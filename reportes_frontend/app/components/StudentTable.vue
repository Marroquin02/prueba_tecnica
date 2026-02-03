<script setup lang="ts">
import type { Student, PaginationMeta } from "~/types/students";

defineProps<{
  students: Student[];
  isLoading: boolean;
  meta: PaginationMeta;
}>();

const emit = defineEmits<{
  "row-click": [carnet: string];
}>();
</script>

<template>
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Carnet
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Nombre Completo
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              CUM
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Carrera
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Facultad
            </th>
            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
              Materias Cursadas
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <StudentTableSkeleton v-if="isLoading" />
          <template v-else-if="students.length > 0">
            <StudentTableRow v-for="student in students" :key="student.carnet" :student="student"
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
