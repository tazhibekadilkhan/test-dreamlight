<template>
    <transition name="deleteModal">
        <div v-if="showDeleteModal" class="modal-mask" @click="closeDeleteModalOutside">
            <div class="modal-wrapper" @click.stop>
                <div class="modal-container text-center">
                    <p class="text-lg mb-4">Подтвердите удаление файла:</p>
                    <p class="text-xl font-bold">{{ fileNameToDelete }}</p>
                    <button @click="closeDeleteModal" class="absolute top-2 right-2 text-gray-600 hover:text-gray-900">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <button @click="deleteFile(fileIdToDelete)" class="text-sm mt-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Да
                    </button>
                    <button @click="closeDeleteModal" class="text-sm mt-4 ml-2 bg-gray-400 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Отмена
                    </button>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps(['showDeleteModal', 'closeDeleteModalOutside', 'closeDeleteModal', 'deleteFile', 'fileIdToDelete', 'fileNameToDelete']);

</script>

<style scoped>
.modal-mask {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 11;
}

.modal-wrapper {
    max-width: 600px;
    width: 100%;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style>
