<template>
    <div class="mb-4">
        <label for="file" class="block text-gray-700 font-bold mb-2">Выберите файл:</label>
        <input type="file" id="file" name="file" class="border rounded-md p-2 w-full" @change="validateFile">
        <img class="preview" v-if="preview" :src="preview">
        <span v-if="fileError" class="text-red-500">{{ fileError }}</span>
    </div>
</template>

<script setup>
import { ref, defineExpose } from "vue";

const fileError = ref('Файл не выбран');
const preview = ref(null);

const validateFile = (event) => {
    const file = event.target.files[0];
    preview.value = URL.createObjectURL(file)
    const maxSize = 8 * 1024 * 1024; // 8MB in bytes
    if (file && file.size > maxSize) {
        fileError.value = 'Размер файла должен быть не более 8MB';
        event.target.value = ''; // Clear the file input
    } else {
        fileError.value = '';
    }
};

defineExpose({
    fileError,
})
</script>

<style>
    .preview{
        width: 300px;
        height: 200px;
        margin: 50px auto;
        object-fit: cover;
        border-radius: 10px;
    }
</style>
