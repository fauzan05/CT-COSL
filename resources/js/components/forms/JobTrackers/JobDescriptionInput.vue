<template>
    <div class="mb-5">
        <div class="flex justify-between items-center mb-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Job Description
            </label>
            <div class="flex items-center gap-2">
                <!-- reset button -->
                <button @click="selectedJobDescription = ''" type="button"
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
                <button @click="addDescription" type="button"
                    class="px-3 py-1 text-xs bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Description
                </button>
            </div>
        </div>

        <!-- Headless UI Listbox (Dropdown) -->
        <Listbox v-model="selectedJobDescription">
            <div class="relative">
                <ListboxButton
                    class="relative w-full cursor-default rounded-lg bg-white dark:bg-gray-700 py-2 pl-3 pr-10 text-left border border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900 transition-all duration-200 sm:text-sm">
                    <span class="block truncate text-gray-900 dark:text-white">
                        {{ selectedJobDescription || placeholder }}
                    </span>
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                    </span>
                </ListboxButton>

                <transition leave-active-class="transition duration-100 ease-in" leave-from-class="opacity-100"
                    leave-to-class="opacity-0">
                    <ListboxOptions
                        class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-gray-700 py-1 text-base shadow-lg ring-1 ring-gray-200 ring-opacity-5 focus:outline-none sm:text-sm z-10">
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
                            <li class="cursor-default select-none py-2 pl-10 pr-4 text-gray-500 dark:text-gray-400">
                                No options available
                            </li>
                        </ListboxOption>
                    </ListboxOptions>
                </transition>
            </div>
        </Listbox>

        <!-- Add Option Input -->
        <div v-if="showAddOption" class="mt-2 flex gap-2">
            <input v-model="newOption" type="text" placeholder="Enter new job description"
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

        <!-- Selected Descriptions List -->
        <SelectedDescriptionsList :descriptions="modelValue" @remove="removeDescription" />
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
import SelectedDescriptionsList from './SelectedDescriptionsList.vue'
import { useToast } from 'vue-toastification'

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
const placeholder = ref('Select job description')
const listOptions = ref([])

const selectedJobDescription = ref('')

// UI States
const showAddOption = ref(false)
const showManageOptions = ref(false)
const newOption = ref('')

// Methods
const addNewOption = async () => {
    if (newOption.value.trim()) {
        try {
            await axios.post(`${baseUrl}/api/job-tracker-master/job-descriptions`, {
                description: newOption.value.trim()
            })
            
            await fetchAllJobDescriptions() // Refresh the list after adding
            toast.success('Job description option added successfully!')
            
            newOption.value = ''
            showAddOption.value = false
        } catch (error) {
            console.error('Error adding job description option:', error)
            toast.error('Failed to add job description option.')
        }
    }
}

const cancelAddOption = () => {
    newOption.value = ''
    showAddOption.value = false
}

const addDescription = () => {
    if (selectedJobDescription.value && !props.modelValue.includes(selectedJobDescription.value)) {
        const updatedDescriptions = [...props.modelValue, selectedJobDescription.value]
        emit('update:modelValue', updatedDescriptions)

        // Clear the selected job description after adding
        selectedJobDescription.value = ''
    }
}

const removeDescription = (index) => {
    const updatedDescriptions = [...props.modelValue]
    updatedDescriptions.splice(index, 1)
    emit('update:modelValue', updatedDescriptions)
}

const updateOption = async ({ index, oldValue, newValue }) => {
    const newDescriptionName = typeof newValue === 'object' ? newValue.description : newValue
    
    try {
        await axios.put(`${baseUrl}/api/job-tracker-master/job-descriptions/${listOptions.value[index].id}`, {
            description: newDescriptionName
        })
        
        // Update selected items in modelValue if they match the old value
        const updatedModelValue = props.modelValue.map(desc => 
            desc === oldValue ? newDescriptionName : desc
        )
        emit('update:modelValue', updatedModelValue)
        
        // Update selected description if it matches
        if (selectedJobDescription.value === oldValue) {
            selectedJobDescription.value = newDescriptionName
        }
        
        await fetchAllJobDescriptions() // Refresh the list after updating
        toast.success('Job description option updated successfully!')
        
    } catch (error) {
        console.error('Error updating job description option:', error)
        toast.error('Failed to update job description option.')
    }
}

const removeOption = async (index) => {
    const optionToRemove = listOptions.value[index].value
    
    try {
        await axios.delete(`${baseUrl}/api/job-tracker-master/job-descriptions/${listOptions.value[index].id}`)
        
        // Remove from modelValue if exists
        const updatedDescriptions = props.modelValue.filter(desc => desc !== optionToRemove)
        emit('update:modelValue', updatedDescriptions)
        
        // Clear selected if it matches
        if (selectedJobDescription.value === optionToRemove) {
            selectedJobDescription.value = ''
        }
        
        await fetchAllJobDescriptions() // Refresh the list after removing
        toast.success('Job description option removed successfully!')
        
    } catch (error) {
        console.error('Error removing job description option:', error)
        toast.error('Failed to remove job description option.')
    }
}

const fetchAllJobDescriptions = async () => {
    try {
        const response = await axios.get(`${baseUrl}/api/job-tracker-master/job-descriptions`)
        
        if (response.data && Array.isArray(response.data)) {
            // Sort by description alphabetically
            response.data.sort((a, b) => a.description.localeCompare(b.description))
            
            listOptions.value = response.data.map(desc => ({
                id: desc.id,
                value: desc.description,
                label: desc.description
            }))

            // Clean up selected description if not in list
            if (selectedJobDescription.value && !listOptions.value.some(opt => opt.value === selectedJobDescription.value)) {
                selectedJobDescription.value = ''
            }

            // Clean up modelValue - remove any items that are no longer in the options
            if (Array.isArray(props.modelValue) && props.modelValue.length > 0) {
                const validDescriptions = props.modelValue.filter(desc => 
                    typeof desc === 'string' && 
                    desc.trim() !== '' && 
                    listOptions.value.some(opt => opt.value === desc)
                )
                
                // Only emit if there are changes
                if (validDescriptions.length !== props.modelValue.length || 
                    !validDescriptions.every((desc, index) => desc === props.modelValue[index])) {
                    emit('update:modelValue', validDescriptions)
                }
            }
        }
    } catch (error) {
        console.error('Error fetching job descriptions:', error)
        toast.error('Failed to fetch job descriptions.')
    }
}

onMounted(async () => {
    await fetchAllJobDescriptions()
})
</script>