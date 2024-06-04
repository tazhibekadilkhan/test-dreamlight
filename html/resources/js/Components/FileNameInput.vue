<template>
    <div class="mb-4">
        <label for="name" class="block text-gray-700 font-bold mb-2">Название файла (необязательно):</label>
        <input type="text" id="name" name="name" :value="fileName" class="border rounded-md p-2 w-full">
    </div>
</template>

<script>
export default {
    props: {
        fileName: String
    }
}
</script>
