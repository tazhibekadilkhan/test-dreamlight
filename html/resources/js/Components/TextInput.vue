<script setup>

const fileName = defineProps({
    type: String
});

</script>

<template>
    <input
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :value="fileName"
        ref="input"
    />
</template>
