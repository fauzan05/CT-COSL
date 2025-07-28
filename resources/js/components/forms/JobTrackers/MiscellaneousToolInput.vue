<template>
    <div class="mb-5">
        <div class="flex justify-between items-center mb-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Miscellaneous Tool
            </label>
            <div class="flex items-center gap-2">
                <!-- reset button -->
                <button @click="selectedMiscellaneousTool = ''" type="button"
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
                <button @click="addMiscellaneousTool" type="button"
                    class="px-3 py-1 text-xs bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Miscellaneous Tool
                </button>
            </div>
        </div>

        <!-- Headless UI Listbox (Dropdown) -->
        <Listbox v-model="selectedMiscellaneousTool">
            <div class="relative">
                <ListboxButton
                    class="relative w-full cursor-default rounded-lg bg-white dark:bg-gray-700 py-2 pl-3 pr-10 text-left border border-gray-300 dark:border-gray-600 focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm">
                    <span class="block truncate text-gray-900 dark:text-white">
                        {{ selectedMiscellaneousTool || placeholder }}
                    </span>
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                    </span>
                </ListboxButton>

                <transition leave-active-class="transition duration-100 ease-in" leave-from-class="opacity-100"
                    leave-to-class="opacity-0">
                    <ListboxOptions
                        class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-gray-700 py-1 text-base shadow-lg ring ring-gray-200 ring-opacity-5 focus:outline-none sm:text-sm z-10">
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
            <input v-model="newOption" type="text" placeholder="Enter new Miscellaneous Tool"
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

        <!-- Selected Miscellaneous Tool List -->
        <SelectedMiscellaneousToolsList :miscellaneous_tools="modelValue" @remove="removeMiscellaneousTool" />
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
import SelectedMiscellaneousToolsList from './SelectedMiscellaneousToolsList.vue'
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
const placeholder = ref('Select Miscellaneous Tool')
const listOptions = ref([])

const selectedMiscellaneousTool = ref('')

// UI States
const showAddOption = ref(false)
const showManageOptions = ref(false)
const newOption = ref('')

// Methods
const addNewOption = async () => {
    if (newOption.value.trim()) {
        try {
            await axios.post(`${baseUrl}/api/job-tracker-master/miscellaneous-tools`, {
                miscellaneous_tool_name: newOption.value.trim()
            })
            
            await fetchAllMiscellaneousTools() // Refresh the list after adding
            toast.success('Miscellaneous Tool option added successfully!')
            
            newOption.value = ''
            showAddOption.value = false
        } catch (error) {
            console.error('Error adding Miscellaneous Tool option:', error)
            toast.error('Failed to add Miscellaneous Tool option.')
        }
    }
}

const cancelAddOption = () => {
    newOption.value = ''
    showAddOption.value = false
}

const addMiscellaneousTool = () => {
    if (selectedMiscellaneousTool.value) {
        const updatedMiscellaneousTools = [...props.modelValue, selectedMiscellaneousTool.value]
        emit('update:modelValue', updatedMiscellaneousTools)

        // Clear the selected Miscellaneous Tool after adding
        selectedMiscellaneousTool.value = ''
    }
}

const removeMiscellaneousTool = (index) => {
    const updatedMiscellaneousTools = [...props.modelValue]
    updatedMiscellaneousTools.splice(index, 1)
    emit('update:modelValue', updatedMiscellaneousTools)
}

const updateOption = async ({ index, oldValue, newValue }) => {
    const newMiscellaneousToolName = typeof newValue === 'object' ? newValue.miscellaneous_tool_name : newValue
    
    try {
        await axios.put(`${baseUrl}/api/job-tracker-master/miscellaneous-tools/${listOptions.value[index].id}`, {
            miscellaneous_tool_name: newMiscellaneousToolName
        })
        
        await fetchAllMiscellaneousTools() // Refresh the list after updating
        toast.success('Miscellaneous Tool option updated successfully!')
        
    } catch (error) {
        console.error('Error updating Miscellaneous Tool option:', error)
        toast.error('Failed to update Miscellaneous Tool option.')
    }
}

const removeOption = async (index) => {    
    try {
        await axios.delete(`${baseUrl}/api/job-tracker-master/miscellaneous-tools/${listOptions.value[index].id}`)
        
        await fetchAllMiscellaneousTools() // Refresh the list after removing
        toast.success('Miscellaneous Tool option removed successfully!')
        
    } catch (error) {
        console.error('Error removing Miscellaneous Tool option:', error)
        toast.error('Failed to remove Miscellaneous Tool option.')
    }
}

const fetchAllMiscellaneousTools = async () => {
    try {
        const response = await axios.get(`${baseUrl}/api/job-tracker-master/miscellaneous-tools`)
        
        if (response.data && Array.isArray(response.data)) {
            // Sort by tank name alphabetically
            response.data.sort((a, b) => a.miscellaneous_tool_name.localeCompare(b.miscellaneous_tool_name))
            
            listOptions.value = response.data.map(miscellaneous_tool => ({
                id: miscellaneous_tool.id,
                value: miscellaneous_tool.miscellaneous_tool_name,
                label: miscellaneous_tool.miscellaneous_tool_name
            }))

            // Clean up selected tank if not in list
            if (selectedMiscellaneousTool.value && !listOptions.value.some(opt => opt.value === selectedMiscellaneousTool.value)) {
                selectedMiscellaneousTool.value = ''
            }

            // Clean up modelValue - remove any items that are no longer in the options
            if (Array.isArray(props.modelValue) && props.modelValue.length > 0) {
                const miscellaneousTools = props.modelValue.filter(miscellaneous_tool => 
                    typeof miscellaneous_tool === 'string' && 
                    miscellaneous_tool.trim() !== '' && 
                    listOptions.value.some(opt => opt.value === miscellaneous_tool)
                )
                
                // Only emit if there are changes
                if (miscellaneousTools.length !== props.modelValue.length || 
                    !miscellaneousTools.every((miscellaneous_tool, index) => miscellaneous_tool === props.modelValue[index])) {
                    emit('update:modelValue', miscellaneousTools)
                }
            }
        }
    } catch (error) {
        console.error('Error fetching Miscellaneous Tool:', error)
    }
}

onMounted(async () => {
    await fetchAllMiscellaneousTools()
})
</script>