<template>
    <head>
        <Title>Thread</Title>
    </head>
    <!-- modal create/update thread -->
    <TransitionRoot appear :show="isThreadModalOpen" as="template">
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
                            class="relative w-full max-w-6xl transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800/80 p-6 text-left align-middle shadow-xl transition-all">
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

                            <form @submit.prevent="saveThread" class="space-y-6">
                                <!-- Input Section -->
                                <div
                                    class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                            Type
                                        </label>
                                        <input type="text" v-model="threadForm.type"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                            Top Connection
                                        </label>
                                        <input type="text" v-model="threadFormSize.top_connection"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                            Bottom Connection
                                        </label>
                                        <input type="text" v-model="threadFormSize.bottom_connection"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    <div class="flex items-end">
                                        <button type="button" @click="addSize"
                                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
                                            :disabled="addSizeLoading">
                                            <span v-if="!addSizeLoading">
                                                {{ editingSizeIndex !== null ? 'Update Size' : 'Add Size' }}
                                            </span>
                                            <span v-else class="flex items-center justify-center">
                                                <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                        stroke-width="4" />
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                                </svg>
                                                Processing...
                                            </span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Table Section -->
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white">List Sizes</h4>
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
                                                        Top Connection</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        Bottom Connection</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        Updated At</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        Updated By</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        Action</th>
                                                </tr>
                                            </thead>
                                            <tbody v-if="loadingAllSizes"
                                                class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                <tr v-for="n in 3" :key="n">
                                                    <td class="px-6 py-4">
                                                        <div
                                                            class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-8 animate-pulse">
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div
                                                            class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-32 animate-pulse">
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div
                                                            class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-32 animate-pulse">
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div
                                                            class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-24 animate-pulse">
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div
                                                            class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-24 animate-pulse">
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div
                                                            class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-20 animate-pulse">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tbody v-else
                                                class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                <tr v-for="(threadSize, index) in listThreadSizes" :key="threadSize.id"
                                                    class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ index
                                                        + 1 }}</td>

                                                    <!-- Top Connection -->
                                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                                        <template v-if="editingRowIndex === index">
                                                            <input type="text"
                                                                v-model="listThreadSizes[index].top_connection"
                                                                class="w-full px-2 py-1 border rounded dark:bg-gray-700 dark:text-white" />
                                                        </template>
                                                        <template v-else>
                                                            {{ threadSize.top_connection }}
                                                        </template>
                                                    </td>

                                                    <!-- Bottom Connection -->
                                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                                        <template v-if="editingRowIndex === index">
                                                            <input type="text"
                                                                v-model="listThreadSizes[index].bottom_connection"
                                                                class="w-full px-2 py-1 border rounded dark:bg-gray-700 dark:text-white" />
                                                        </template>
                                                        <template v-else>
                                                            {{ threadSize.bottom_connection }}
                                                        </template>
                                                    </td>

                                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{
                                                        formatDate(threadSize.updated_at) }}</td>
                                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{
                                                        threadSize.updated_by_name }}</td>

                                                    <!-- Action -->
                                                    <td class="px-6 py-4 text-sm">
                                                        <div class="flex items-center space-x-2">
                                                            <template v-if="editingRowIndex === index">
                                                                <!-- Save Button -->
                                                                <button @click="saveThreadSize(index)"
                                                                    class="inline-flex items-center px-2.5 py-1.5 bg-green-50 hover:bg-green-100 dark:bg-green-900/20 dark:hover:bg-green-900/40 text-green-600 dark:text-green-400 rounded-lg transition-all duration-200 group">
                                                                    <svg class="w-4 h-4 mr-1.5 transition-transform group-hover:scale-110"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                                    </svg>
                                                                    <span class="text-sm font-medium">Save</span>
                                                                </button>
                                                            </template>
                                                            <template v-else>
                                                                <!-- Edit Button -->
                                                                <button @click="editThreadSize(threadSize, index)"
                                                                    class="inline-flex items-center px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 rounded-lg transition-all duration-200 group">
                                                                    <svg class="w-4 h-4 mr-1.5 transition-transform group-hover:scale-110"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                    </svg>
                                                                    <span class="text-sm font-medium">Edit</span>
                                                                </button>
                                                            </template>

                                                            <!-- Delete Button -->
                                                            <button @click="deleteThreadSize(index)"
                                                                class="inline-flex items-center px-2.5 py-1.5 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 rounded-lg transition-all duration-200 group">
                                                                <svg class="w-4 h-4 mr-1.5 transition-transform group-hover:scale-110"
                                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                        :disabled="loading">
                                        <span v-if="!loading">{{ titleModalButton }}</span>
                                        <span v-else class="flex items-center">
                                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4" />
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                            </svg>
                                            Processing...
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
    <!-- Main Content -->
    <div class="p-6 bg-gray-50 min-h-screen dark:bg-slate-900/50 dark:text-gray-100 rounded-xl">
        <!-- Header Section with improved styling -->
        <div class="mb-8 bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Thread Management</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your thread inventory and specifications</p>
                </div>
                <button @click="openThreadModal(null)"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg flex items-center space-x-2 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Add New Thread</span>
                </button>
            </div>
        </div>

        <!-- Table Section with improved styling -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Thread List</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage and monitor your thread inventory</p>
            </div>

            <!-- Table Content -->
            <div class="p-6">
                <!-- Skeleton Loading -->
                <div v-if="isLoading" class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th v-for="header in ['No', 'Type', 'Total Size', 'Updated At', 'Updated By', 'Action']"
                                    :key="header"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ header }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="i in 5" :key="i" class="animate-pulse">
                                <td v-for="(width, index) in ['w-8', 'w-32', 'w-16', 'w-24', 'w-28', 'w-12']" :key="index"
                                    class="px-6 py-4">
                                    <div :class="['h-4 bg-gray-200 dark:bg-gray-700 rounded', width]"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Actual Content -->
                <div v-else class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th v-for="header in ['No', 'Type', 'Total Size', 'Updated At', 'Updated By', 'Action']"
                                    :key="header"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ header }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="(thread, index) in listThreads" :key="thread.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ (pagination.current_page - 1) * 10 + index + 1 }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ thread.type }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ thread.total_sizes }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ formatDate(thread.updated_at) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ thread.updated_by_name }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <button @click="openThreadModal(thread)"
                                        class="inline-flex items-center px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-md hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors duration-150">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Improved Pagination -->
                <div class="flex items-center justify-between mt-6 px-4">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Showing page {{ pagination.current_page }} of {{ pagination.last_page }}
                    </div>
                    <div class="flex space-x-2">
                        <button
                            class="px-4 py-2 text-sm font-medium rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-150"
                            :disabled="pagination.current_page === 1" @click="goToPage(pagination.current_page - 1)">
                            Previous
                        </button>
                        <button
                            class="px-4 py-2 text-sm font-medium rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-150"
                            :disabled="pagination.current_page === pagination.last_page"
                            @click="goToPage(pagination.current_page + 1)">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script setup>
/* --------------------------------- IMPORTS --------------------------------- */
import { ref, computed, watch, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useToast } from 'vue-toastification';
import draggable from 'vuedraggable';
import { useCurrentUserStore } from '@/stores/CurrentUser';

import {
    TabGroup, TabList, Tab, TabPanels, TabPanel,
    TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle,
    Listbox, ListboxButton, ListboxOptions, ListboxOption,
    Switch, SwitchGroup, SwitchLabel,
    Combobox, ComboboxInput, ComboboxButton, ComboboxOptions, ComboboxOption
} from '@headlessui/vue';

import {
    ChevronUpDownIcon, CheckIcon, PlusIcon, ClipboardDocumentIcon
} from '@heroicons/vue/20/solid';

const listThreads = ref([])
const pagination = ref({
    current_page: 1,
    last_page: 1
})

const threadForm = ref({
    type: '',
})

const isLoading = ref(false)
const componentListLoading = ref(false)
const isThreadModalOpen = ref(false)
const titleModal = ref('Add Thread')
const titleModalButton = ref('Save Thread')
const addSizeLoading = ref(false)
const listThreadSizes = ref([])
const threadFormSize = ref({
    top_connection: '',
    bottom_connection: ''
})
const selectedThread = ref(null)
const editingSizeIndex = ref(null)
const loading = ref(false)
const editingRowIndex = ref(null)

function closeModal() {
    isThreadModalOpen.value = false
    resetForm()
}
function resetForm() {
    threadForm.value = { type: '' }
    threadFormSize.value = { top_connection: '', bottom_connection: '' }
    listThreadSizes.value = []
    editingSizeIndex.value = null
}

function editThreadSize(_, index) {
    editingRowIndex.value = index
}

function saveThreadSize(index) {
    editingRowIndex.value = null
}
const currentUserStore = useCurrentUserStore();

// Add or Update size
function addSize() {
    if (!threadFormSize.value.top_connection || !threadFormSize.value.bottom_connection) return

    addSizeLoading.value = true

    setTimeout(() => {
        if (editingSizeIndex.value !== null) {
            // UPDATE MODE
            listThreadSizes.value[editingSizeIndex.value] = {
                ...listThreadSizes.value[editingSizeIndex.value],
                top_connection: threadFormSize.value.top_connection,
                bottom_connection: threadFormSize.value.bottom_connection,
                updated_at: threadFormSize.value.updated_at || new Date().toISOString(),
                updated_by_name: threadFormSize.value.updated_by_name || 'Current User'
            }
        } else {
            // ADD MODE
            listThreadSizes.value.push({
                id: 0,
                top_connection: threadFormSize.value.top_connection,
                bottom_connection: threadFormSize.value.bottom_connection,
                updated_at: new Date().toISOString(),
                updated_by_name: currentUserStore.user ? currentUserStore.user.fullname : 'Current User',
            })
        }

        // clear input
        threadFormSize.value = { top_connection: '', bottom_connection: '' }
        editingSizeIndex.value = null
        addSizeLoading.value = false
    }, 500)
}

// Remove size
function deleteThreadSize(index) {
    listThreadSizes.value.splice(index, 1)
}

async function fetchThreads(page = 1) {
    try {
        isLoading.value = true
        const response = await axios.get(`/api/threads?page=${page}`)
        listThreads.value = response.data.data
        pagination.value = {
            current_page: response.data.current_page,
            last_page: response.data.last_page
        }
    } catch (error) {
        console.error(error)
    } finally {
        isLoading.value = false
    }
}
const loadingAllSizes = ref(false)
async function fetchThreadSizes(threadId) {
    try {
        loadingAllSizes.value = true
        const response = await axios.get(`/api/threads/${threadId}/sizes`)
        listThreadSizes.value = response.data.data
    } catch (error) {
        console.error(error)
    } finally {
        loadingAllSizes.value = false
    }
}

const openThreadModal = async (thread) => {
    // Logic to open modal for adding or editing thread
    // This can be implemented using a modal component or a simple alert for demonstration
    if (thread) {
        isThreadModalOpen.value = true
        titleModal.value = 'Edit Thread'
        titleModalButton.value = 'Update Thread'
        selectedThread.value = thread
        threadForm.value.type = thread.type
        fetchThreadSizes(thread.id)
    } else {
        isThreadModalOpen.value = true
        titleModal.value = 'Add Thread'
    }
}

const saveThread = async () => {
    loading.value = true
    try {
        let data = {
            type: threadForm.value.type,
            sizes: listThreadSizes.value
        }

        if (selectedThread.value) {
            // Update existing thread
            data.id = selectedThread.value.id
            const response = await axios.put(`/api/threads/${selectedThread.value.id}`, data)
            if (response.status === 200) {
                useToast().success('Thread updated successfully!')
            }
        } else {
            const response = await axios.post('/api/threads', data)
            if (response.status === 201) {
                // Reset form and close modal
                useToast().success('Thread saved successfully!')
            }
        }
        resetForm()
        fetchThreads(pagination.value.current_page)
        closeModal()
    } catch (error) {
        console.error(error)
        useToast().error('Failed to save thread.')
    } finally {
        loading.value = false
    }
}

const formatDate = (utcDateString) => {
    const date = new Date(utcDateString);
    const options = {
        year: 'numeric', month: 'short', day: 'numeric',
        hour: '2-digit', minute: '2-digit', hour12: false,
        timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone,
    };
    return date.toLocaleString('en-US', options).replace(',', '').replace(',', ' at');
};

function goToPage(page) {
    if (page < 1 || page > pagination.value.last_page) return
    fetchThreads(page)
}

onMounted(async () => {
    if (!currentUserStore.user) {
        await currentUserStore.fetchUser();
    }
    fetchThreads()
})
</script>
  