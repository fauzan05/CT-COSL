<template>
    <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Manage Dropdown Options:</h4>

        <!-- All Options -->
        <div class="space-y-2">
            <div v-if="listOptions.length > 0" v-for="(option, index) in listOptions" :key="option.value"
                class="flex items-center gap-2 p-2 bg-white dark:bg-gray-700 rounded border border-gray-200 dark:border-gray-600">

                <input v-if="editingIndex === index" v-model="editingValue" type="text"
                    class="flex-1 px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded focus:outline-none focus:ring-1 focus:ring-blue-500"
                    @keyup.enter="saveEdit(index)" @keyup.escape="cancelEdit">

                <span v-else class="flex-1 text-sm text-gray-700 dark:text-gray-300">
                    {{ option.label }}
                </span>

                <div class="flex gap-1">
                    <template v-if="editingIndex === index">
                        <!-- Tombol Save -->
                        <button @click="saveEdit(index)" type="button"
                            class="inline-flex me-2 items-center px-1.5 py-1.5 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-md hover:bg-green-100 dark:hover:bg-green-900/50 transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>

                        <!-- Tombol Cancel -->
                        <button @click="cancelEdit" type="button"
                            class="inline-flex me-2 items-center px-1.5 py-1.5 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-md hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </template>
                    <template v-else>
                        <button @click="startEdit(index, option.label)" type="button"
                            class="inline-flex me-2 items-center px-1.5 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-md hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button @click="handleRemoveOption(index)" type="button"
                            class="inline-flex items-center px-1.5 py-1.5 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-md hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </template>
                </div>
            </div>
            <div v-else class="text-sm text-gray-500 dark:text-gray-400">
                No options available. Add new options below.
            </div>
        </div>

        <div class="mt-3 flex justify-end">
            <button @click="$emit('close')" type="button"
                class="px-3 py-1 text-xs bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-colors">
                Close
            </button>
        </div>
    </div>
</template>
  
<script setup>
import { ref, defineProps, defineEmits } from 'vue'

// Props & Emits
const props = defineProps({
    listOptions: {
        type: Array,
        required: true
    }
})

const emit = defineEmits([
    'update-option',
    'remove-option',
    'close'
])

// Edit states
const editingIndex = ref(null)
const editingValue = ref('')

// Edit methods
const startEdit = (index, value) => {
    editingIndex.value = index
    editingValue.value = value
}

const saveEdit = (index) => {
    if (editingValue.value.trim()) {
        const oldValue = props.listOptions[index].value
        const newValue = editingValue.value.trim()

        emit('update-option', { index, oldValue, newValue })
    }
    cancelEdit()
}

const handleRemoveOption = (index) => {
    emit('remove-option', index)
}

const cancelEdit = () => {
    editingIndex.value = null
    editingValue.value = ''
}
</script>