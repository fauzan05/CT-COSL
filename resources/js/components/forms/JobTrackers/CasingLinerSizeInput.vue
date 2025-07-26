<template>
    <div class="mb-5">
        <div class="flex justify-between items-center mb-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Casing/Liner Size
            </label>
            <div class="flex items-center gap-2">
                <!-- reset button -->
                <button @click="resetSelection" type="button"
                    class="px-3 py-1 text-xs bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-colors flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                    Reset
                </button>
                <button @click="showAddOption = true" type="button"
                    class="px-3 py-1 text-xs bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-colors flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Option
                </button>
                <button @click="showManageOptions = !showManageOptions" type="button"
                    class="px-3 py-1 text-xs bg-purple-500 hover:bg-purple-600 text-white rounded-md transition-colors flex items-center gap-1">
                    <i class="fa-solid fa-sliders"></i>
                    Manage Options
                </button>
            </div>
        </div>

        <!-- Size and Unit Selection -->
        <div class="grid grid-cols-3 gap-2">
            <!-- Size Dropdown -->
            <div class="col-span-2">
                <Listbox v-model="selectedCasingLinerSize">
                    <div class="relative">
                        <ListboxButton
                            class="relative w-full cursor-default rounded-lg bg-white dark:bg-gray-700 py-2 pl-3 pr-10 text-left border border-gray-300 dark:border-gray-600 focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm">
                            <span class="block truncate text-gray-900 dark:text-white">
                                {{ selectedCasingLinerSize || sizePlaceholder }}
                            </span>
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                            </span>
                        </ListboxButton>

                        <transition leave-active-class="transition duration-100 ease-in" leave-from-class="opacity-100"
                            leave-to-class="opacity-0">
                            <ListboxOptions
                                class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-gray-700 py-1 text-base shadow-lg ring-opacity-5 focus:outline-none sm:text-sm z-10">
                                <ListboxOption v-if="sizeOptions.length > 0" v-slot="{ active, selected }"
                                    v-for="option in sizeOptions" :key="option.id" :value="option.value" as="template">
                                    <li :class="[
                                        active ? 'bg-blue-100 dark:bg-gray-600 text-blue-900 dark:text-white' : 'text-gray-900 dark:text-gray-300',
                                        'relative cursor-default select-none py-2 pl-10 pr-4',
                                    ]">
                                        <span :class="[
                                            selected ? 'font-medium' : 'font-normal',
                                            'block truncate',
                                        ]">{{ option.label }}</span>
                                        <span v-if="selected"
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 text-blue-600 dark:text-blue-400">
                                            <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                        </span>
                                    </li>
                                </ListboxOption>
                                <ListboxOption v-if="sizeOptions.length === 0" as="template">
                                    <li class="cursor-default select-none py-2 px-4 text-gray-500 dark:text-gray-400">
                                        No sizes available
                                    </li>
                                </ListboxOption>
                            </ListboxOptions>
                        </transition>
                    </div>
                </Listbox>
            </div>

            <!-- Unit Dropdown -->
            <div class="col-span-1">
                <Listbox v-model="selectedUnit">
                    <div class="relative">
                        <ListboxButton
                            class="relative w-full cursor-default rounded-lg bg-white dark:bg-gray-700 py-2 pl-3 pr-10 text-left border border-gray-300 dark:border-gray-600 focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm">
                            <span class="block truncate text-gray-900 dark:text-white">
                                {{ selectedUnit || unitPlaceholder }}
                            </span>
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                            </span>
                        </ListboxButton>

                        <transition leave-active-class="transition duration-100 ease-in" leave-from-class="opacity-100"
                            leave-to-class="opacity-0">
                            <ListboxOptions
                                class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-gray-700 py-1 text-base shadow-lg ring-opacity-5 focus:outline-none sm:text-sm z-10">
                                <ListboxOption v-slot="{ active, selected }" v-for="unit in unitOptions" :key="unit.value"
                                    :value="unit.value" as="template">
                                    <li :class="[
                                        active ? 'bg-blue-100 dark:bg-gray-600 text-blue-900 dark:text-white' : 'text-gray-900 dark:text-gray-300',
                                        'relative cursor-default select-none py-2 pl-10 pr-4',
                                    ]">
                                        <span :class="[
                                            selected ? 'font-medium' : 'font-normal',
                                            'block truncate',
                                        ]">{{ unit.label }}</span>
                                        <span v-if="selected"
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 text-blue-600 dark:text-blue-400">
                                            <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                        </span>
                                    </li>
                                </ListboxOption>
                            </ListboxOptions>
                        </transition>
                    </div>
                </Listbox>
            </div>
        </div>

        <!-- Add Option Input -->
        <div v-if="showAddOption" class="mt-2">
            <div class="grid grid-cols-3 gap-2 mb-2">
                <input v-model="newOptionSize" type="text" placeholder="Enter size (e.g., 9 5/8)"
                    class="col-span-2 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    @keyup.enter="addNewOption">
                <div class="flex gap-2">
                    <button @click="addNewOption" type="button"
                        class="px-3 py-2 text-sm bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                        Add
                    </button>
                    <button @click="cancelAddOption" type="button"
                        class="px-3 py-2 text-sm bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        <!-- Manage Options Panel -->
        <ManageOptionsPanel v-if="showManageOptions" :list-options="sizeOptions" @update-option="updateOption"
            @remove-option="removeOption" @close="showManageOptions = false" />
    </div>
</template>
  
<script setup>
import { ref, defineProps, defineEmits, onMounted, watch, computed } from 'vue'
import {
    Listbox,
    ListboxButton,
    ListboxOption,
    ListboxOptions,
} from '@headlessui/vue'
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'
import ManageOptionsPanel from './ManageOptionsPanel.vue'
import { useToast } from 'vue-toastification'
import axios from 'axios'

// Props & Emits
const props = defineProps({
    modelValue: {
        type: Object,
        default: () => ({
            size: '',
            unit: '',
            label: ''
        })
    }
})
const baseUrl = import.meta.env.VITE_API_URL
const emit = defineEmits(['update:modelValue'])
const toast = useToast()

// Dropdown options
const sizePlaceholder = ref('Select size')
const unitPlaceholder = ref('Select unit')
const sizeOptions = ref([])

// Unit options - predefined common units
const unitOptions = ref([
    { value: 'in', label: 'inches (in)' },
    { value: 'mm', label: 'millimeters (mm)' },
    { value: 'cm', label: 'centimeters (cm)' },
    { value: 'ft', label: 'feet (ft)' },
    { value: 'OD', label: 'OD (Outer Diameter)' },
    { value: 'ID', label: 'ID (Inner Diameter)' }
])

const selectedCasingLinerSize = ref('')
const selectedUnit = ref(unitOptions.value[0].value) // Default to first unit

// UI States
const showAddOption = ref(false)
const showManageOptions = ref(false)
const newOptionSize = ref('')

// Computed property for complete selection
const completeSelection = computed(() => {
    if (selectedCasingLinerSize.value && selectedUnit.value) {
        return {
            size: selectedCasingLinerSize.value,
            unit: selectedUnit.value,
            label: `${selectedCasingLinerSize.value} ${selectedUnit.value}`
        }
    }
    return {
        size: '',
        unit: '',
        label: ''
    }
})

// Methods
const resetSelection = () => {
    selectedCasingLinerSize.value = ''
    selectedUnit.value = unitOptions.value[0].value // Reset to first unit
}

const addNewOption = async () => {
    if (newOptionSize.value.trim()) {
        try {
            const response = await axios.post(`${baseUrl}/api/job-tracker-master/casing-liner-sizes`, {
                size: newOptionSize.value.trim()
            })

            await fetchAllCasingLinerSizes() // Refresh the list options from the API

            toast.success('Casing/liner size option added successfully!')

            newOptionSize.value = ''
            showAddOption.value = false
        } catch (error) {
            console.error('Error adding casing/liner size option:', error)
            toast.error('Failed to add casing/liner size option.')
        }
    } else {
        toast.warning('Please enter size.')
    }
}

const cancelAddOption = () => {
    newOptionSize.value = ''
    showAddOption.value = false
}

const updateOption = async ({ index, oldValue, newValue }) => {
    // Extract casing/liner size name from newValue object or use it directly if it's a string
    const newCasingLinerSizeName = typeof newValue === 'object' ? newValue.size : newValue

    try {
        await axios.put(`${baseUrl}/api/job-tracker-master/casing-liner-sizes/${sizeOptions.value[index].id}`, {
            size: newCasingLinerSizeName.trim()
        })

        await fetchAllCasingLinerSizes() // Refresh the list options from the API

        toast.success('Casing/liner size option updated successfully!')
    } catch (error) {
        console.error('Error updating casing/liner size option:', error)
        toast.error('Failed to update casing/liner size option.')
    }
}

const removeOption = async (index) => {
    try {
        await axios.delete(`${baseUrl}/api/job-tracker-master/casing-liner-sizes/${sizeOptions.value[index].id}`)

        await fetchAllCasingLinerSizes() // Refresh the list options from the API

        toast.success('Casing/liner size option removed successfully!')
    } catch (error) {
        console.error('Error removing casing/liner size option:', error)
        toast.error('Failed to remove casing/liner size option.')
    }
}

const fetchAllCasingLinerSizes = async () => {
    try {
        const response = await axios.get(`${baseUrl}/api/job-tracker-master/casing-liner-sizes`)
        if (response.data && Array.isArray(response.data)) {
            sizeOptions.value = [] // Reset options before populating

            // Sort by size numbering
            response.data.sort((a, b) => {
                const sizeA = parseFloat(a.size.replace(/[^0-9.]/g, '')) || 0
                const sizeB = parseFloat(b.size.replace(/[^0-9.]/g, '')) || 0
                return sizeA - sizeB
            })

            sizeOptions.value = response.data.map(cas => ({
                id: cas.id,
                value: cas.size,
                unit: cas.unit || '',
                label: cas.size
            }))

            // Reset selection if current selection is not in the updated list
            if (sizeOptions.value.length < 1 || !sizeOptions.value.some(opt => opt.value === selectedCasingLinerSize.value)) {
                selectedCasingLinerSize.value = ''
            }
        }
    } catch (error) {
        console.error('Error fetching casing/liner sizes:', error)
        toast.error('Failed to fetch casing/liner sizes.')
    }
}

onMounted(async () => {
    await fetchAllCasingLinerSizes()

    // Initialize with props value if available
    if (props.modelValue?.size) {
        selectedCasingLinerSize.value = props.modelValue.size
    }
    if (props.modelValue?.unit) {
        selectedUnit.value = props.modelValue.unit
    }

    // Set default unit if not specified
    if (!selectedUnit.value) {
        selectedUnit.value = unitOptions.value[0].value // Default to first unit
    }
})

// Watch for changes and emit to parent
watch(completeSelection, (newValue) => {
    emit('update:modelValue', newValue)
}, { deep: true })

// Watch for external prop changes
watch(() => props.modelValue, (newValue) => {
    if (newValue?.size !== selectedCasingLinerSize.value) {
        selectedCasingLinerSize.value = newValue?.size || ''
    }
    if (newValue?.unit !== selectedUnit.value) {
        selectedUnit.value = newValue?.unit || ''
    }
}, { deep: true })
</script>