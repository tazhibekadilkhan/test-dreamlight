<template>
    <button @click="openModal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        Загрузить новый файл
    </button>
    <transition name="modal">
        <div v-if="showModal" class="modal-mask" @click="closeModalOutside">
            <div class="modal-wrapper" @click.stop>
                <div class="modal-container">
                    <h2 class="text-xl font-bold mb-4">Загрузка файла</h2>
                    <button @click="closeModal" class="absolute top-2 right-2 text-gray-600 hover:text-gray-900">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <form @submit.prevent="uploadFile()">
                        <FileNameInput />
                        <FileInput />
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Загрузить</button>
                    </form>
                </div>
            </div>
        </div>
    </transition>

    <div v-if="isLoading" class="loader">
        Загрузка файла...
    </div>
</template>

<script setup>
import { ref, defineExpose, defineProps, defineEmits } from "vue";
import FileNameInput from "@/Components/FileNameInput.vue";
import FileInput from "@/Components/FileInput.vue";
import axios from "axios";

const fileError = ref('');

const props = defineProps([
    'uploadFile',
    'fileName',
    'showLoader',
    'hideLoader',
    'files',
    'isLoading',
    'currentPageFilesCountVar',
    'totalFilesCountVar',
]);

const showModal = ref(false);
const files = ref('');
const currentPageFilesCountVarVar = ref(props.currentPageFilesCountVar);
const totalFilesCountVarVar = ref(props.totalFilesCountVar);
const isLoading = ref(false);

const emit = defineEmits(['addCounter'])
const uploadFile = async () => {
    try {
        const fileInput = document.querySelector('#file');
        const file = fileInput.files[0];

        const formData = new FormData();

        const fileNameInput = document.querySelector('#name');
        let fileName = fileNameInput.value.trim();

        if (fileName && !/\.[^/.]+$/.test(fileName)) {
            const fileExtension = file.name.split('.').pop();
            fileName += `.${fileExtension}`;
        }

        formData.append('name', fileName || file.name);
        formData.append('file', file);

        showLoader(); // Show loader before making the request

        const response = await axios.post('/files', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        hideLoader(); // Hide loader after successful upload

        props.files.data.unshift(response.data.file);

        emit('addCounter');

        closeModal();
    } catch (error) {
        hideLoader(); // Hide loader if there's an error
        console.error('Ошибка загрузки файла:', error);
    }
};

const openModal = () => {
    showModal.value = true;
};
const closeModal = () => {
    showModal.value = false;
};
const closeModalOutside = (event) => {
    if (event.target.classList.contains('modal-mask')) {
        closeModal();
    }
};

const showLoader = () => {
    isLoading.value = true;
};
const hideLoader = () => {
    isLoading.value = false;
};

defineExpose({
    fileError,
    files,
    isLoading,
    currentPageFilesCountVarVar,
    totalFilesCountVarVar,
});
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
    margin-left: 0!important;
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
.loader {
    /* Add your loader styling here */
    background: rgba(255, 255, 255, 0.7);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
</style>
