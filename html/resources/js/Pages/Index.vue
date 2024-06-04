<template>
    <div class="bg-gray-100">
        <div class="container">
            <Head title="File Storage"/>
            <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
                <h1 class="text-3xl font-bold mb-10">Тестовое задание для FullStack Web Developer (PHP/JS)</h1>

                <div class="w-full flex flex-row space-x-8 justify-between mt-8">

                    <Modal :fileName="fileName"
                           :files="files"
                           :currentPageFilesCountVar="currentPageFilesCountVar"
                           :totalFilesCountVar="totalFilesCountVar"
                           @addCounter="addCounter"
                    />

                    <form @submit.prevent="submitSearch" class="max-w-md w-full">
                        <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input v-model="searchTerm" placeholder="Поиск по названию" type="search" id="default-search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                            <button type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Поиск</button>
                        </div>
                    </form>
                </div>

                <div class="w-full" v-if="files.data.length">
                    <FileTable :files="files" :openDeleteModal="openDeleteModal" />

                    <div class="mt-8 text-right">
                        <p class="text-sm">Файлов на текущей странице: {{ currentPageFilesCountVar }}</p>
                        <p class="text-sm">Общее кол-во файлов: {{ totalFilesCountVar }}</p>
                    </div>
                </div>

                <div v-else class="mt-8">
                    <p class="text-sm">Файлы по данному запросу не найдены</p>
                </div>

                <div class="mt-2" v-if="files.last_page > 1">
                    <pagination :links="files.links" :totalFilesCount="totalFilesCount" :currentPageFilesCount="currentPageFilesCount" />
                </div>



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
                                <button @click="deleteFile(fileIdToDelete)" class="text-sm mt-4 bg-red-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Да
                                </button>
                                <button @click="closeDeleteModal" class="text-sm mt-4 ml-2 bg-gray-400 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Отмена
                                </button>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import { ref, defineProps } from 'vue';
import { Head } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue'
import FileTable from "@/Components/FileTable.vue";
import Modal from "@/Components/Modal.vue";

const props = defineProps({
    files: {
        type: Object,
        default: () => [],
    },
    totalFilesCount: Number,
    currentPageFilesCount: Number,
    lastPage: Number
});

const currentPageFilesCountVar = ref(props.currentPageFilesCount);
const totalFilesCountVar = ref(props.totalFilesCount);
const lastPage = ref(props.lastPage);
const showDeleteModal = ref(false);
const fileName = ref('');
const fileIdToDelete = ref(null);
const fileNameToDelete = ref(null);
let fileIdx = ref(null);
const fileError = ref('');


const openDeleteModal = (fileId, fileName, idx) => {
    fileIdToDelete.value = fileId;
    fileNameToDelete.value = fileName;
    showDeleteModal.value = true;
    console.log(idx);
    fileIdx.value = idx;
};

const addCounter = () => {
    currentPageFilesCountVar.value += 1;
    totalFilesCountVar.value += 1;
}

const closeDeleteModal = () => {
    showDeleteModal.value = false;
};

const closeDeleteModalOutside = (event) => {
    if (event.target.classList.contains('modal-mask')) {
        closeDeleteModal();
    }
};

const deleteFile = async (fileId) => {
    try {
        const response = await axios.delete(`/files/${fileId}`);

        props.files.data.splice(fileIdx.value, 1);

        currentPageFilesCountVar.value -= 1;
        totalFilesCountVar.value -= 1;

        closeDeleteModal();
    } catch (error) {
        console.error('Error deleting file:', error);
    }
};

const searchTerm = ref('');

const submitSearch = async () => {
    try {
        const response = await fetch(`/files/fetch?search=${searchTerm.value}`);
        const data = await response.json();
        props.files.data = data.files.data;
        props.files.links = data.files.links;
        props.files.last_page = data.files.last_page;

        const params = new URLSearchParams(window.location.search);
        params.delete('page');

        if (params.get('search') && !searchTerm.value) {
            params.delete('search')
        } else params.set('search', searchTerm.value);



        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.pushState({}, '', newUrl);
        currentPageFilesCountVar.value = data.currentPageFilesCount;
    } catch (error) {
        console.error('Error fetching filtered files:', error);
    }
};

const populateSearchTerm = () => {
    const params = new URLSearchParams(window.location.search);
    if (params.has('name')) {
        searchTerm.value = params.get('name');
    }
};

populateSearchTerm();
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
