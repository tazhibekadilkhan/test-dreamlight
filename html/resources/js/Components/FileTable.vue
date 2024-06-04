<template>
    <div class="mt-8 rounded-2xl overflow-hidden">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-16 py-3">
                        <span class="sr-only">Image</span>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Size
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Extension
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="(file, idx) in files.data"
                    :key="idx"
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="p-4">
                        <img v-if="file.thumbnail" :src="file.thumbnail" class="w-16 md:w-32 max-w-full max-h-full" alt="Apple Watch">
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                        {{ file.name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ file.size }}
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                        {{ file.extension }}
                    </td>
                    <td class="px-6 py-4">
                        <a :href="file.path" download class="font-medium text-gray-600 dark:text-gray-500 hover:underline">Download</a>
                        <button @click="openDeleteModal(file.id, file.name, idx)" class="font-medium text-red-600 ml-1 dark:text-red-500 hover:underline">Remove</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    files: {
        type: Object,
        default: () => [],
    },
    openDeleteModal: Function,
});

</script>

<style scoped>
#trash:hover {
    cursor: pointer;
}
</style>
