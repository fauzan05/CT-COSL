<template>
    <div class="mb-5">
        <div class="flex justify-between items-center mb-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Customer
            </label>
            <div class="flex items-center gap-2">
                <!-- reset button -->
                <button @click="selectedCustomer = ''" type="button"
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
        <Listbox v-model="selectedCustomer">
            <div class="relative">
                <ListboxButton
                    class="relative w-full cursor-default rounded-lg bg-white dark:bg-gray-700 py-2 pl-3 pr-10 text-left border border-gray-300 dark:border-gray-600 focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm">
                    <span class="block truncate text-gray-900 dark:text-white">
                        {{ selectedCustomer || placeholder }}
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
            <input v-model="newOption" type="text" placeholder="Enter new customer"
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
import { ref, defineProps, defineEmits, onMounted, watch } from 'vue'
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

// Props & Emits - FIXED: Ubah dari Array ke String
const props = defineProps({
    modelValue: {
        type: String,  // ← UBAH DARI Array KE String
        default: ''
    }
})

const baseUrl = import.meta.env.VITE_API_URL
const emit = defineEmits(['update:modelValue'])
const toast = useToast()

// Dropdown options
const placeholder = ref('Select Customer')
const listOptions = ref([])
const selectedCustomer = ref('')

// UI States
const showAddOption = ref(false)
const showManageOptions = ref(false)
const newOption = ref('')

// TAMBAHKAN: Watch untuk menerima nilai dari parent
watch(() => props.modelValue, (newValue) => {
    selectedCustomer.value = newValue || ''
}, { immediate: true })

// TAMBAHKAN: Watch untuk mengirim nilai ke parent
watch(selectedCustomer, (newValue) => {
    emit('update:modelValue', newValue)
})

// Methods
const addNewOption = async () => {
    if (newOption.value.trim()) {
        try {
            await axios.post(`${baseUrl}/api/job-tracker-master/customers`, {
                customer_name: newOption.value.trim()
            })

            fetchAllCustomers() // Refresh the list options from the API
            toast.success('Customer option added successfully!')
            
            newOption.value = ''
            showAddOption.value = false
        } catch (error) {
            console.error('Error adding customer option:', error)
            toast.error('Failed to add customer option.')
        }
    }
}

const cancelAddOption = () => {
    newOption.value = ''
    showAddOption.value = false
}

const updateOption = async ({ index, oldValue, newValue }) => {
    const newCustomerName = typeof newValue === 'object' ? newValue.customer_name : newValue
        
    try {
        await axios.put(`${baseUrl}/api/job-tracker-master/customers/${listOptions.value[index].id}`, {
            customer_name: newCustomerName
        })
        
        listOptions.value[index] = {
            id: listOptions.value[index].id,
            value: newCustomerName,
            label: newCustomerName
        }
        
        if (selectedCustomer.value === oldValue) {
            selectedCustomer.value = newCustomerName
        }

        fetchAllCustomers()
        toast.success('Customer option updated successfully!')
    } catch (error) {
        console.error('Error updating customer option:', error)
        toast.error('Failed to update customer option.')
    }
}

const removeOption = async (index) => {
    const optionToRemove = listOptions.value[index].value

    try {
        await axios.delete(`${baseUrl}/api/job-tracker-master/customers/${listOptions.value[index].id}`)
        
        listOptions.value.splice(index, 1)
        
        if (selectedCustomer.value === optionToRemove) {
            selectedCustomer.value = ''
        }
        
        toast.success('Customer option removed successfully!')
    } catch (error) {
        console.error('Error removing customer option:', error)
        toast.error('Failed to remove customer option.')
    }
}

const fetchAllCustomers = async () => {
    try {
        const response = await axios.get(`${baseUrl}/api/job-tracker-master/customers`)
        if (response.data && Array.isArray(response.data)) {
            listOptions.value = []
            response.data.sort((a, b) => a.customer_name.localeCompare(b.customer_name))
            
            listOptions.value = response.data.map(cust => ({
                id: cust.id,
                value: cust.customer_name,
                label: cust.customer_name
            }))

            // PERBAIKI: Pastikan selectedCustomer tetap valid setelah fetch
            if (props.modelValue && listOptions.value.some(opt => opt.value === props.modelValue)) {
                selectedCustomer.value = props.modelValue
            } else if (!listOptions.value.some(opt => opt.value === selectedCustomer.value)) {
                selectedCustomer.value = ''
            }
        }
    } catch (error) {
        console.error('Error fetching customers:', error)
    }
}

onMounted(async () => {
    await fetchAllCustomers()
})
</script>