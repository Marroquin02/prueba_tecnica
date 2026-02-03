<script setup lang="ts">
import type { Student } from '~/types/students'

const props = defineProps<{
    student: Student
}>()

const emit = defineEmits<{
    'row-click': [carnet: string]
}>()

const handleRowClick = () => {
    emit('row-click', props.student.carnet)
}
</script>

<template>
    <tr class="hover:bg-blue-50 cursor-pointer transition-colors" @click="handleRowClick">
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
            {{ student.carnet }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
            {{ student.full_name }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
            {{ student.cum.toFixed(2) }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
            {{ student.career }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
            {{ student.faculty }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center font-medium">
            {{ student.subjects_count }}
        </td>
    </tr>
</template>
