<template>
    <div class="mb-5">
        <div class="flex justify-between items-center mb-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                District
            </label>
            <div class="flex items-center gap-2">
                <!-- reset button -->
                <button @click="selectedDistrict = ''" type="button"
                    class="px-3 py-1 text-xs bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-colors flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
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

        <!-- Headless UI Listbox (Dropdown) -->
        <Listbox v-model="selectedDistrict">
            <div class="relative">
                <ListboxButton
                    class="relative w-full cursor-default rounded-lg bg-white dark:bg-gray-700 py-2 pl-3 pr-10 text-left border border-gray-300 dark:border-gray-600 focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm">
                    <span class="block truncate text-gray-900 dark:text-white">
                        {{ selectedDistrict || placeholder }}
                    </span>
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                    </span>
                </ListboxButton>

                <transition leave-active-class="transition duration-100 ease-in" leave-from-class="opacity-100"
                    leave-to-class="opacity-0">
                    <ListboxOptions
                        class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-gray-700 py-1 text-base shadow-lgring-opacity-5 focus:outline-none sm:text-sm z-10">
                        <ListboxOption v-if="listOptions.length > 0" v-slot="{ active, selected }" v-for="option in listOptions" :key="option.id"
                            :value="option.value" as="template">
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
                        <ListboxOption v-if="listOptions.length === 0" as="template">
                            <li class="cursor-default select-none py-2 px-4 text-gray-500 dark:text-gray-400">
                                No options available
                            </li>
                        </ListboxOption>
                    </ListboxOptions>
                </transition>
            </div>
        </Listbox>

        <!-- Add Option Input -->
        <div v-if="showAddOption" class="mt-2 flex gap-2">
            <input v-model="newOption" type="text" placeholder="Enter new district"
                class="flex-1 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                @keyup.enter="addNewOption">
            <button @click="addNewOption" type="button"
                class="px-3 py-2 text-sm bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                Add
            </button>
            <button @click="cancelAddOption" type="button"
                class="px-3 py-2 text-sm bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                Cancel
            </button>
        </div>

        <!-- Manage Options Panel -->
        <ManageOptionsPanel v-if="showManageOptions" :list-options="listOptions" @update-option="updateOption"
            @remove-option="removeOption" @close="showManageOptions = false" />
    </div>
</template>
  
<script setup>
import { ref, defineProps, defineEmits, onMounted } from 'vue'
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
        type: Array,
        default: () => []
    }
})
const baseUrl = import.meta.env.VITE_API_URL
const emit = defineEmits(['update:modelValue'])
const toast = useToast()

// Dropdown options - hanya satu list
const placeholder = ref('Select district')
const listOptions = ref([])

const selectedDistrict = ref('')

// UI States
const showAddOption = ref(false)
const showManageOptions = ref(false)
const newOption = ref('')

// Methods
const addNewOption = async () => {
    if (newOption.value.trim()) {
        try {
            const response = await axios.post(`${baseUrl}/api/job-tracker-master/bj-districts`, {
                district_name: newOption.value.trim()
            })
            
            fetchAllDistricts() // Refresh the list options from the API
            
            console.log(`District option "${newOption.value}" added successfully!`)
            toast.success('District option added successfully!')
            
            newOption.value = ''
            showAddOption.value = false
        } catch (error) {
            console.error('Error adding district option:', error)
            toast.error('Failed to add district option.')
        }
    }
}

const cancelAddOption = () => {
    newOption.value = ''
    showAddOption.value = false
}

const updateOption = async ({ index, oldValue, newValue }) => {
    // Extract district name from newValue object or use it directly if it's a string
    const newDistrictName = typeof newValue === 'object' ? newValue.district_name : newValue
        
    try {
        await axios.put(`${baseUrl}/api/job-tracker-master/bj-districts/${listOptions.value[index].id}`, {
            district_name: newDistrictName.trim()
        })

        fetchAllDistricts() // Refresh the list options from the API

        toast.success('District option updated successfully!')
    } catch (error) {
        console.error('Error updating district option:', error)
        toast.error('Failed to update district option.')
    }
}

const removeOption = async (index) => {
    try {
        await axios.delete(`${baseUrl}/api/job-tracker-master/bj-districts/${listOptions.value[index].id}`)
        
        fetchAllDistricts() // Refresh the list options from the API
        
        toast.success('District option removed successfully!')
    } catch (error) {
        console.error('Error removing district option:', error)
        toast.error('Failed to remove district option.')
    }
}

const fetchAllDistricts = async () => {
    try {
        const response = await axios.get(`${baseUrl}/api/job-tracker-master/bj-districts`)
        
        if (response.data && Array.isArray(response.data)) {
            // reset listOptions
            listOptions.value = []
            // Sort by district_name alphabetically
            response.data.sort((a, b) => a.district_name.localeCompare(b.district_name))
            
            listOptions.value = response.data.map(cust => ({
                id: cust.id,
                value: cust.district_name,
                label: cust.district_name
            }))

            if (listOptions.value.length < 1 || !listOptions.value.some(opt => opt.value === selectedDistrict.value)) {
                selectedDistrict.value = ''
            }
        }
    } catch (error) {
        console.error('Error fetching districts:', error)
        toast.error('Failed to fetch districts.')
    }
}

onMounted(async () => {
    // Fetch all districts from the API first
    await fetchAllDistricts()
    
    // Initialize selectedDistrict with the first option if available
    if (listOptions.value.length > 0) {
        selectedDistrict.value = listOptions.value[0].value
    }
})
</script>