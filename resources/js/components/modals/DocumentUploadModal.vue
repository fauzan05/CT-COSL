<template>
    <!-- modal create/update document -->
    <TransitionRoot appear :show="isDocumentModalOpen" as="template">
        <Dialog as="div" @close="closeModal" class="relative z-50">
            <!-- Background overlay -->
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
                leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95">
                        <DialogPanel
                            class="relative w-full max-w-4xl transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800/80 p-6 text-left align-middle shadow-xl transition-all">
                            <!-- Header Section -->
                            <div class="flex justify-between items-center mb-6">
                                <DialogTitle as="h3" class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ titleModal }}
                                </DialogTitle>
                                <button @click="closeModal"
                                    class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors duration-200">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <form class="space-y-6">
                                <!-- Document Form Section -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg space-y-4">
                                    <!-- Document Name -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                            Document Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" v-model="documentForm.name"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            :class="{ 'border-red-500': errors.name }" required
                                            placeholder="Enter document name">
                                        <p v-if="errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400">{{
                                            errors.name }}</p>
                                    </div>

                                    <!-- Description -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                            Description
                                        </label>
                                        <textarea v-model="documentForm.description" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                            :class="{ 'border-red-500': errors.description }"
                                            placeholder="Enter document description (optional)"></textarea>
                                        <p v-if="errors.description" class="mt-1 text-sm text-red-600 dark:text-red-400">{{
                                            errors.description }}</p>
                                    </div>

                                    <!-- File Upload -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                            Upload Documents <span class="text-red-500">*</span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400 font-normal">(Max 10
                                                files)</span>
                                        </label>

                                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md hover:border-blue-400 dark:hover:border-blue-500 transition-colors duration-200"
                                            :class="{ 'border-red-500': errors.documents, 'border-blue-400 bg-blue-50 dark:bg-blue-900/20': isDragOver }"
                                            @drop="handleDrop" @dragover="handleDragOver" @dragleave="handleDragLeave">
                                            <div class="space-y-1 text-center">
                                                <div
                                                    class="mx-auto h-12 w-12 text-gray-400 flex items-center justify-center">
                                                    <svg class="h-8 w-8" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <svg class="h-4 w-4 ml-1 -mt-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                </div>

                                                <!-- Upload State -->
                                                <div>
                                                    <div
                                                        class="flex items-center justify-center text-sm text-gray-600 dark:text-gray-400">
                                                        <label for="file-upload"
                                                            class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                            <span class="p-2 text-center">Upload files</span>
                                                            <input id="file-upload" name="file-upload" type="file"
                                                                class="sr-only" @change="handleFileSelect"
                                                                accept=".pdf,.doc,.docx,.odt,.rtf,.txt" ref="fileInput"
                                                                multiple
                                                                :disabled="documentForm.documents.length >= maxFiles">
                                                        </label>
                                                        <p class="pl-1 my-2">or drag and drop</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        PDF, DOC, DOCX, ODT, RTF, TXT up to 15MB each (Max {{ maxFiles }}
                                                        files)
                                                    </p>
                                                    <p v-if="documentForm.documents.length > 0"
                                                        class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                                        {{ documentForm.documents.length }}/{{ maxFiles }} files selected
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <p v-if="errors.documents" class="mt-1 text-sm text-red-600 dark:text-red-400">{{
                                            errors.documents }}</p>
                                    </div>
                                </div>

                                <!-- Existing Documents Table -->
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Uploaded Documents
                                        </h4>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                                <tr>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        No</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        File</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        Updated At</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        Action</th>
                                                </tr>
                                            </thead>
                                            <tbody v-if="loadingDocuments"
                                                class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                <tr v-for="n in 3" :key="n">
                                                    <td v-for="col in 4" :key="col" class="px-6 py-4">
                                                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"
                                                            :class="col === 1 ? 'w-8' : col === 2 || col === 3 ? 'w-32' : 'w-24'">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tbody v-else
                                                class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                <tr v-for="(document, index) in documentForm.documents" :key="document.id"
                                                    class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ index
                                                        + 1 }}</td>
                                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                                        <div class="flex items-center space-x-2">
                                                            <svg class="w-4 h-4 text-gray-400" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            <span>{{ document.filename || document.name }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{
                                                        document.updated_at ? formatDate(document.updated_at) :
                                                        'Waiting for upload' }}</td>
                                                    <td class="px-6 py-4 text-sm">
                                                        <div class="flex items-center space-x-2">
                                                            <button @click="downloadDocument(document)" type="button"
                                                                class="inline-flex items-center px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-lg transition-all duration-200 group">
                                                                <svg class="w-4 h-4 mr-1.5" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                </svg>
                                                                <span class="text-sm font-medium">Download</span>
                                                            </button>
                                                            <button @click="deleteDocument(index)" type="button"
                                                                class="inline-flex items-center px-2.5 py-1.5 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 rounded-lg transition-all duration-200 group">
                                                                <svg class="w-4 h-4 mr-1.5" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                                <span class="text-sm font-medium">Delete</span>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Footer Actions -->
                                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <button type="button"
                                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                                        @click="closeModal">
                                        Cancel
                                    </button>
                                    <button type="button" @click="submitDocument"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                        :disabled="loading">
                                        <span v-if="!loading">{{ titleModalButton }}</span>
                                        <span v-else class="flex items-center">
                                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4" />
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                            </svg>
                                            {{ loading ? 'Uploading...' : 'Processing...' }}
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
  
<script setup>
import { ref, reactive, computed, watch } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'

// Props
const props = defineProps({
    isDocumentModalOpen: {
        type: Boolean,
        default: false
    },
    isCreating: {
        type: Boolean,
        default: true
    },
    documents: {
        type: Array,
        default: () => []
    },
    loading: {
        type: Boolean,
        default: false
    },
    loadingDocuments: {
        type: Boolean,
        default: false
    },
    modelValue: {
        type: Object,
        default: () => ({
            name: '',
            description: '',
            documents: []
        })
    },
    documentForm: {
        type: Object,
        default: () => ({
            name: '',
            description: '',
            documents: []
        })
    }
})

// Emits
const emit = defineEmits(['close', 'delete', 'download', 'update:documentForm', 'submit'])

// Reactive data
const isDragOver = ref(false)
const fileInput = ref(null)

const documentForm = reactive({
    name: props.modelValue.name || '',
    description: props.modelValue.description || '',
    documents: props.modelValue.documents || []
})

const errors = reactive({
    name: '',
    description: '',
    documents: ''
})

// Computed properties
const titleModal = computed(() => {
    return props.isCreating ? 'Upload New Document' : 'Manage Documents'
})

const titleModalButton = computed(() => {
    return props.isCreating ? 'Create Document' : 'Update Document'
})

// File validation
const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.oasis.opendocument.text', 'application/rtf', 'text/plain']
const allowedExtensions = ['pdf', 'doc', 'docx', 'odt', 'rtf', 'txt']
const maxFileSize = 15360 * 1024 // 15MB in bytes
const maxFiles = 10

// Methods
const closeModal = () => {
    emit('close')
}

const resetForm = () => {
    documentForm.name = ''
    documentForm.description = ''
    documentForm.documents = []
    clearErrors()

    // Emit updates
    emit('update:documentForm', { ...documentForm })
}

const clearErrors = () => {
    errors.name = ''
    errors.description = ''
    errors.documents = ''
}

const validateFiles = (files) => {
    clearErrors()

    if (!files || files.length === 0) {
        errors.documents = 'Please select at least one document to upload'
        return false
    }

    if (documentForm.documents.length + files.length > maxFiles) {
        errors.documents = `Maximum ${maxFiles} files allowed. You can select ${maxFiles - documentForm.documents.length} more files.`
        return false
    }

    for (let file of files) {
        // Check file size
        if (file.size > maxFileSize) {
            errors.documents = `File "${file.name}" exceeds 15MB limit`
            return false
        }

        // Check file type
        const fileExtension = file.name.split('.').pop().toLowerCase()
        const isValidType = allowedTypes.includes(file.type) || allowedExtensions.includes(fileExtension)

        if (!isValidType) {
            errors.documents = `File "${file.name}" is not a valid format. Only PDF, DOC, DOCX, ODT, RTF, and TXT files are allowed`
            return false
        }

        // Check for duplicate files
        const isDuplicate = documentForm.documents.some(existingFile =>
            existingFile.name === file.name && existingFile.size === file.size
        )

        if (isDuplicate) {
            errors.documents = `File "${file.name}" is already selected`
            return false
        }
    }

    return true
}

const submitDocument = () => {
    if (validateForm()) {
        emit('submit', { ...documentForm })
    }
}

const handleFileSelect = (event) => {
    const files = Array.from(event.target.files)
    if (files.length > 0 && validateFiles(files)) {
        // let newFiles = files.map(file => ({
        //     id: Date.now() + Math.random().toString(36).substring(2, 15), // Unique ID for each file
        //     name: file.name,
        //     size: file.size,
        //     type: file.type,
        //     lastModified: file.lastModified,
        //     filename: file.name,
        //     updated_at: 'Waiting for upload',
        //     is_current: false
        // }))
        // selectedFiles.value.push(...newFiles)
        documentForm.documents.push(...files)
    } else {
        event.target.value = ''
    }
}

const handleDrop = (event) => {
    event.preventDefault()
    isDragOver.value = false

    const files = Array.from(event.dataTransfer.files)
    if (files.length > 0 && validateFiles(files)) {
        // let newFiles = files.map(file => ({
        //     id: Date.now() + Math.random().toString(36).substring(2, 15), // Unique ID for each file
        //     name: file.name,
        //     size: file.size,
        //     type: file.type,
        //     lastModified: file.lastModified,
        //     filename: file.name,
        //     updated_at: 'Waiting for upload',
        //     is_current: false
        // }))
        // selectedFiles.value.push(...newFiles)
        documentForm.documents.push(...files)
    }
}

const handleDragOver = (event) => {
    event.preventDefault()
    isDragOver.value = true
}

const handleDragLeave = () => {
    isDragOver.value = false
}

const validateForm = () => {
    clearErrors()
    let isValid = true

    if (!documentForm.name.trim()) {
        errors.name = 'Document name is required'
        isValid = false
    }

    if (documentForm.documents.length === 0 && props.isCreating) {
        errors.documents = 'Please select at least one document to upload'
        isValid = false
    }

    return isValid
}

// Expose validateForm method
const validate = () => validateForm()

// Expose methods to parent component
defineExpose({
    validate,
    resetForm,
    clearErrors
})

const deleteDocument = (index) => {
    documentForm.documents.splice(index, 1)
}

const downloadDocument = (document) => {
    emit('download', document)
}

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes'
    const k = 1024
    const sizes = ['Bytes', 'KB', 'MB', 'GB']
    const i = Math.floor(Math.log(bytes) / Math.log(k))
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

const formatDate = (dateString) => {
    if (!dateString) return '-'
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

// Watch for prop changes
watch(() => props.modelValue, (newValue) => {
    documentForm.name = newValue.name || ''
    documentForm.description = newValue.description || ''
    documentForm.documents = newValue.documents || []
}, { deep: true })

// watch(() => props.selectedFilesValue, (newValue) => {
//     selectedFiles.value = [...newValue]
// }, { deep: true })

// Watch for form changes and emit updates
watch(documentForm, (newValue) => {
    emit('update:documentForm', { ...newValue })
}, { deep: true })


watch(() => props.documentForm, (newValue) => {
    documentForm.name = newValue.name || ''
    documentForm.description = newValue.description || ''
    documentForm.documents = newValue.documents || []
}, { deep: true })
</script>