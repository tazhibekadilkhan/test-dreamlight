<template>
    <div>

        <div class="flex flex-wrap mt-2">
            <template v-for="(link, p) in links" :key="p">
                <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                     v-html="link.label"/>
                <a v-else
                   :class="{ 'disabled bg-blue-700 text-white': link.active }" :href="link.url"
                   class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
                   v-html="link.label"/>
            </template>
        </div>

    </div>
</template>
<script>
export default {
    props: {
        links: Array,
        totalFilesCount: Number,
        currentPageFilesCount: Number,
    }
}
</script>


<style>

    .disabled:hover {
        background-color: rgb(29 78 216);
    }
</style>
