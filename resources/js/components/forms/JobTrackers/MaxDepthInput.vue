<template>
    <div>
        <!-- Header with label and buttons -->
        <div class="flex items-center justify-between mb-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Max Depth
            </label>

            <!-- Action buttons positioned on the right -->
            <div class="flex items-center gap-2">
                <button @click="addNewDepth" type="button"
                    class="px-3 py-1 text-xs bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Max. Depth
                </button>
            </div>
        </div>

        <!-- Container for multiple inputs -->
        <div class="space-y-2 w-auto">
            <div v-for="(depth, index) in maxDepths" :key="index" class="flex items-center gap-2 w-auto">
                <input v-model.number="maxDepths[index].value" type="number" step="0.1" min="0"
                    class="flex-1 px-3 py-2 h-9.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    :placeholder="`Enter max depth ${index + 1}`" @input="emitValues" />

                <!-- Unit dropdown using Headless UI -->
                <Listbox v-model="maxDepths[index].unit" @update:model-value="emitValues">
                    <div class="relative min-w-[150px]">
                        <ListboxButton
                            class="relative w-full cursor-default rounded-md bg-white dark:bg-gray-700 py-2 pl-3 pr-8 text-left border border-gray-300 dark:border-gray-600 focus:outline-none focus-visible:border-blue-500 focus-visible:ring-2 focus-visible:ring-blue-500 sm:text-sm">
                            <span class="block truncate text-gray-900 dark:text-white">
                                {{ maxDepths[index].unit }}
                            </span>
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon class="h-4 w-4 text-gray-400" aria-hidden="true" />
                            </span>
                        </ListboxButton>

                        <transition leave-active-class="transition duration-100 ease-in" leave-from-class="opacity-100"
                            leave-to-class="opacity-0">
                            <ListboxOptions
                                class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-gray-700 py-1 text-base shadow-lg ring-opacity-5 focus:outline-none sm:text-sm z-10">
                                <ListboxOption v-for="unit in unitOptions" :key="unit" v-slot="{ active, selected }" 
                                    :value="unit" as="template">
                                    <li :class="[
                                        active ? 'bg-blue-100 dark:bg-gray-600 text-blue-900 dark:text-white' : 'text-gray-900 dark:text-gray-300',
                                        'relative cursor-default select-none py-2 pl-8 pr-4',
                                    ]">
                                        <span :class="[selected ? 'font-medium' : 'font-normal', 'block truncate']">
                                            {{ unit }}
                                        </span>
                                        <span v-if="selected"
                                            class="absolute inset-y-0 left-0 flex items-center pl-2 text-blue-600 dark:text-blue-400">
                                            <CheckIcon class="h-4 w-4" aria-hidden="true" />
                                        </span>
                                    </li>
                                </ListboxOption>
                            </ListboxOptions>
                        </transition>
                    </div>
                </Listbox>

                <!-- Remove button (only show if more than 1 input) -->
                <button v-if="maxDepths.length > 1" @click="removeDepth(index)" type="button"
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
import {
    Listbox,
    ListboxButton,
    ListboxOption,
    ListboxOptions,
} from '@headlessui/vue'
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'

// Props untuk v-model support
const props = defineProps({
    modelValue: {
        type: Array,
        default: () => [{ value: 0, unit: 'ft' }]
    }
})

// Emit untuk v-model support
const emit = defineEmits(['update:modelValue'])

// Unit options
const unitOptions = ref(['ft', 'mm', 'cm', 'm', 'inch'])

// Reactive array untuk menyimpan nilai-nilai max depth
const maxDepths = ref([])

// Initialize dengan props
const initializeMaxDepths = () => {
    if (props.modelValue && props.modelValue.length > 0) {
        // Pastikan setiap item memiliki struktur yang benar
        maxDepths.value = props.modelValue.map(item => {
            if (typeof item === 'object' && item.hasOwnProperty('value') && item.hasOwnProperty('unit')) {
                return { ...item }
            } else {
                // Jika format lama (hanya angka), convert ke format baru
                return { value: parseFloat(item) || 0, unit: 'ft' }
            }
        })
    } else {
        maxDepths.value = [{ value: 0, unit: 'ft' }]
    }
}

// Initialize on mount
initializeMaxDepths()

// Watch untuk perubahan modelValue dari parent
watch(() => props.modelValue, () => {
    initializeMaxDepths()
}, { deep: true })

// Function untuk menambah input baru
const addNewDepth = () => {
    maxDepths.value.push({ value: 0, unit: 'ft' })
    emitValues()
}

// Function untuk menghapus input tertentu
const removeDepth = (index) => {
    if (maxDepths.value.length > 1) {
        maxDepths.value.splice(index, 1)
        emitValues()
    }
}

// Function untuk emit values ke parent component
const emitValues = () => {
    // Filter out any invalid values and ensure proper structure
    const validDepths = maxDepths.value
        .map(depth => {
            const value = parseFloat(depth.value)
            return {
                value: isNaN(value) ? 0 : Math.max(0, value),
                unit: depth.unit || 'ft'
            }
        })
        .filter(depth => depth.value >= 0)

    emit('update:modelValue', validDepths)
}
</script>