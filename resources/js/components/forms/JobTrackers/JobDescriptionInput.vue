<template>
    <div class="mb-5">
        <div class="flex justify-between items-center mb-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Job Description
            </label>
            <div class="flex gap-2">
                <button @click="showAddOption = true" type="button"
                    class="px-3 py-1 text-xs bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-colors">
                    Add Option
                </button>
                <button @click="showManageOptions = !showManageOptions" type="button"
                    class="px-3 py-1 text-xs bg-purple-500 hover:bg-purple-600 text-white rounded-md transition-colors">
                    Manage Options
                </button>
                <button @click="addDescription" type="button"
                    class="px-3 py-1 text-xs bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors">
                    Add Description
                </button>
            </div>
        </div>

        <select v-model="selectedJobDescription" required
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="" disabled>{{ placeholder }}</option>
            <option v-for="option in listOptions" :key="option.value" :value="option.value">
                {{ option.label }}
            </option>
        </select>

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
import { ref, defineProps, defineEmits } from 'vue'
import ManageOptionsPanel from './ManageOptionsPanel.vue'
import SelectedDescriptionsList from './SelectedDescriptionsList.vue'

// Props & Emits
const props = defineProps({
    modelValue: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['update:modelValue'])

// Dropdown options - hanya satu list
const placeholder = ref('Select job description')
const listOptions = ref([
    { value: 'Software Engineer', label: 'Software Engineer' },
    { value: 'Frontend Developer', label: 'Frontend Developer' },
    { value: 'Backend Developer', label: 'Backend Developer' },
    { value: 'Full Stack Developer', label: 'Full Stack Developer' },
    { value: 'DevOps Engineer', label: 'DevOps Engineer' },
    { value: 'Data Scientist', label: 'Data Scientist' },
    { value: 'Product Manager', label: 'Product Manager' },
    { value: 'UI/UX Designer', label: 'UI/UX Designer' },
    { value: 'QA Engineer', label: 'QA Engineer' },
    { value: 'System Administrator', label: 'System Administrator' }
])

const selectedJobDescription = ref('')

// UI States
const showAddOption = ref(false)
const showManageOptions = ref(false)
const newOption = ref('')

// Methods
const addNewOption = () => {
    if (newOption.value.trim()) {
        listOptions.value.push({
            value: newOption.value.trim(),
            label: newOption.value.trim()
        })
        newOption.value = ''
        showAddOption.value = false
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
        selectedJobDescription.value = ''
    }
}

const removeDescription = (index) => {
    const updatedDescriptions = [...props.modelValue]
    updatedDescriptions.splice(index, 1)
    emit('update:modelValue', updatedDescriptions)
}

const updateOption = ({ index, oldValue, newValue }) => {
    // Update the option
    listOptions.value[index] = {
        value: newValue,
        label: newValue
    }

    // Update in parent's job descriptions
    updateDescriptionValue(oldValue, newValue)

    // Update selected if it matches
    if (selectedJobDescription.value === oldValue) {
        selectedJobDescription.value = newValue
    }
}

const removeOption = (index) => {
    const optionToRemove = listOptions.value[index].value

    // Remove from list options
    listOptions.value.splice(index, 1)

    // Remove from parent's job descriptions
    const updatedDescriptions = props.modelValue.filter(desc => desc !== optionToRemove)
    emit('update:modelValue', updatedDescriptions)

    // Clear selected if it matches
    if (selectedJobDescription.value === optionToRemove) {
        selectedJobDescription.value = ''
    }
}

const updateDescriptionValue = (oldValue, newValue) => {
    const updatedDescriptions = props.modelValue.map(desc =>
        desc === oldValue ? newValue : desc
    )
    emit('update:modelValue', updatedDescriptions)
}
</script>