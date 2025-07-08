<template>
    <div
        class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white rounded-xl shadow-sm p-4 border border-gray-100 dark:bg-slate-800/50 dark:border-gray-600">

        <div class="text-sm text-gray-500 dark:text-gray-400 text-center md:text-left">
            <span v-if="from && to && total">
                Showing {{ from }} to {{ to }} of {{ total }} items
            </span>
            <span v-else>
                No items to display.
            </span>
        </div>

        <div class="flex items-center justify-center space-x-2">
            <button @click="$emit('prev')" :disabled="currentPage <= 1"
                class="px-3 py-2 bg-gray-100 text-gray-600 rounded-lg disabled:opacity-50 hover:bg-gray-200 transition-colors">
                Previous
            </button>

            <span class="px-3 py-2 bg-blue-600 text-white rounded-lg">
                {{ currentPage }}
            </span>

            <button @click="$emit('next')" :disabled="currentPage >= lastPage"
                class="px-3 py-2 bg-gray-100 text-gray-600 rounded-lg disabled:opacity-50 hover:bg-gray-200 transition-colors">
                Next
            </button>
        </div>
    </div>
</template>
  
<script setup>
defineProps({
    currentPage: {
        type: Number,
        required: true
    },
    lastPage: {
        type: Number,
        required: true
    },
    from: {
        type: Number,
        required: false,
        default: null
    },
    to: {
        type: Number,
        required: false,
        default: null
    },
    total: {
        type: Number,
        required: false,
        default: null
    }
});
</script>
  