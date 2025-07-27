<template>
    <div v-if="nitrogen_personnels.length > 0" class="mt-3">
        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Added Nitrogen Personnel:
        </h4>
        <div class="space-y-2">
            <div v-for="(nitrogen_personnel, index) in nitrogen_personnels" :key="index"
                class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">

                <span class="text-sm text-gray-700 dark:text-gray-300">
                    {{ nitrogen_personnel }}
                </span>

                <button @click="$emit('remove', index)" type="button"
                    class="ml-2 w-5 h-5 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors duration-200 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 flex-shrink-0">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>
  
<script setup>
import { defineProps, defineEmits } from 'vue'

// Props & Emits
const props = defineProps({
    nitrogen_personnels: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['remove'])
</script>