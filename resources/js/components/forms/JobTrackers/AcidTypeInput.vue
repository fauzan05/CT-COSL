<template>
    <div>
        <!-- Header with label and buttons -->
        <div class="flex items-center justify-between mb-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Acid Type ({{ acidTypes.length }})
            </label>

            <!-- Action buttons positioned on the right -->
            <div class="flex items-center gap-2">
                <button @click="addNewType" type="button"
                    class="px-3 py-1 text-xs bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Acid Type
                </button>
            </div>
        </div>

        <!-- Container for multiple inputs -->
        <div class="space-y-2 w-auto">
            <div v-for="(acid_type, index) in acidTypes" :key="`acid_type-${index}`" class="flex items-center gap-2 w-auto">
                <input 
                    v-model="acid_type.value" 
                    type="text"
                    class="flex-1 px-3 py-2 border h-9 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    :placeholder="`Enter Acid Type ${index + 1}`" 
                    @input="handleInput(index, $event)" 
                />

                <!-- Remove button (only show if more than 1 input) -->
                <button 
                    v-if="acidTypes.length > 1" 
                    @click="removeType(index)" 
                    type="button"
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
import { ref, watch, nextTick } from 'vue'

// Props untuk v-model support
const props = defineProps({
    modelValue: {
        type: Array,
        default: () => [{ value: '' }]
    }
})

// Emit untuk v-model support
const emit = defineEmits(['update:modelValue'])

// Reactive array untuk menyimpan nilai-nilai Acid Type
const acidTypes = ref([])
const isInternalUpdate = ref(false)

// Initialize dengan props
const initializeAcidTypes = () => {
    if (props.modelValue && props.modelValue.length > 0) {
        acidTypes.value = props.modelValue.map(item => {
            if (typeof item === 'object' && item.hasOwnProperty('value')) {
                return { value: item.value || '' }
            } else {
                return { value: item || '' }
            }
        })
    } else {
        acidTypes.value = [{ value: '' }]
    }
}

// Initialize on mount
initializeAcidTypes()

// Watch untuk perubahan modelValue dari parent - hanya jika bukan dari internal update
watch(() => props.modelValue, (newValue) => {
    if (!isInternalUpdate.value) {
        initializeAcidTypes()
    }
    isInternalUpdate.value = false
}, { deep: true })

// Function untuk menambah input baru
const addNewType = () => {
    acidTypes.value.push({ value: '' })
    emitValues()
}

// Function untuk menghapus input tertentu
const removeType = (index) => {
    if (acidTypes.value.length > 1) {
        acidTypes.value.splice(index, 1)
        emitValues()
    }
}

// Handle input changes
const handleInput = (index, event) => {
    acidTypes.value[index].value = event.target.value
    emitValues()
}

// Function untuk emit values ke parent component
const emitValues = () => {
    isInternalUpdate.value = true
    
    // Emit semua values, termasuk yang kosong
    const allTypes = acidTypes.value.map(acid_type => ({
        value: acid_type.value || ''
    }))
    
    emit('update:modelValue', allTypes)
}
</script>