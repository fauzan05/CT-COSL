<template>
    <div>
        <!-- Header with label and buttons -->
        <div class="flex items-center justify-between mb-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                CT Personnel
            </label>

            <!-- Action buttons positioned on the right -->
            <div class="flex items-center gap-2">
                <button @click="addNewPersonnel" type="button"
                    class="px-3 py-1 text-xs bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add CT Personnel
                </button>
            </div>
        </div>

        <!-- Container for multiple inputs -->
        <div class="space-y-2 w-auto">
            <div v-for="(depth, index) in ctPersonnels" :key="index" class="flex items-center gap-2 w-auto">
                <input v-model.number="ctPersonnels[index].value" type="text"
                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    :placeholder="`Enter CT Personnel Name ${index + 1}`" @input="emitValues" />

                <!-- Remove button (only show if more than 1 input) -->
                <button v-if="ctPersonnels.length > 1" @click="removeCtPersonnel(index)" type="button"
                    class="w-5 h-5 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors duration-200 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 flex-shrink-0">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>
  
<script setup>
import { ref, watch } from 'vue'

// Props untuk v-model support
const props = defineProps({
    modelValue: {
        type: Array,
        default: () => [{ value: '' }]
    }
})

// Emit untuk v-model support
const emit = defineEmits(['update:modelValue'])

// Reactive array untuk menyimpan nilai-nilai max depth
const ctPersonnels = ref([])

// Initialize dengan props
const initializeMaxPersonnels = () => {
    if (props.modelValue && props.modelValue.length > 0) {
        // Pastikan setiap item memiliki struktur yang benar
        ctPersonnels.value = props.modelValue.map(item => {
            if (typeof item === 'object' && item.hasOwnProperty('value') && item.hasOwnProperty('unit')) {
                return { ...item }
            } else {
                // Jika format lama (hanya angka), convert ke format baru
                return { value: '' }
            }
        })
    } else {
        ctPersonnels.value = [{ value: '' }]
    }
}

// Initialize on mount
initializeMaxPersonnels()

// Watch untuk perubahan modelValue dari parent
watch(() => props.modelValue, () => {
    initializeMaxPersonnels()
}, { deep: true })

// Function untuk menambah input baru
const addNewPersonnel = () => {
    ctPersonnels.value.push({ value: '' })
    emitValues()
}

// Function untuk menghapus input tertentu
const removeCtPersonnel = (index) => {
    if (ctPersonnels.value.length > 1) {
        ctPersonnels.value.splice(index, 1)
        emitValues()
    }
}

// Function untuk emit values ke parent component
const emitValues = () => {
    // Filter out any invalid values and ensure proper structure
    const validPersonnels = ctPersonnels.value
        .map(depth => {
            const value = depth.value ? depth.value.trim() : ''
            return { value }
        })
        .filter(depth => depth.value >= 0)

    emit('update:modelValue', validPersonnels)
}
</script>