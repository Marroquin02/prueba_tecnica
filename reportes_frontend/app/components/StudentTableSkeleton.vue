<template>
    <tr v-for="n in 5" :key="n" class="animate-pulse">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="h-4 bg-gray-200 rounded w-20" />
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="h-4 bg-gray-200 rounded w-40" />
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="h-4 bg-gray-200 rounded w-12" />
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="h-4 bg-gray-200 rounded w-48" />
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="h-4 bg-gray-200 rounded w-32" />
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <div class="h-4 bg-gray-200 rounded w-8 mx-auto" />
        </td>
    </tr>
</template>
