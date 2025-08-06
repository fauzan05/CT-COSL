<template>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mt-6 space-y-3 md:space-y-0 px-4">
        <!-- Per Page Selector -->
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500 dark:text-gray-400">Show</span>
            <Listbox v-model="currentPerPage" @update:modelValue="handlePerPageChange">
                <div class="relative">
                    <ListboxButton
                        class="relative w-20 cursor-default rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 py-1.5 pl-3 pr-8 text-left text-sm text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        {{ currentPerPage }}
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                        </span>
                    </ListboxButton>

                    <ListboxOptions
                        class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-gray-800 py-1 text-sm shadow-lg ring-opacity-5 focus:outline-none z-50">

                        <ListboxOption v-for="option in perPageOptions" :key="option" :value="option"
                            v-slot="{ active, selected }">
                            <li :class="[
                                'cursor-default select-none relative py-2 pl-3 pr-9',
                                active ? 'bg-blue-50 dark:bg-blue-900/40' : '',
                                selected ? 'font-semibold text-blue-600 dark:text-blue-300' : 'text-gray-900 dark:text-gray-200'
                            ]">
                                {{ option }}
                                <span v-if="selected"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-blue-600 dark:text-blue-300">
                                    ✓
                                </span>
                            </li>
                        </ListboxOption>
                    </ListboxOptions>
                </div>
            </Listbox>
            <span class="text-sm text-gray-500 dark:text-gray-400">entries</span>
        </div>

        <!-- Pagination Controls -->
        <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-500 dark:text-gray-400">
                Showing page {{ pagination.current_page }} of {{ pagination.last_page }}
            </span>

            <div class="flex items-center space-x-1">
                <!-- First Page -->
                <button @click="goToPage(1)"
                    class="p-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="pagination.current_page === 1">
                    <ChevronDoubleLeftIcon class="h-4 w-4" />
                </button>

                <!-- Previous -->
                <button @click="goToPage(pagination.current_page - 1)"
                    class="p-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="pagination.current_page === 1">
                    <ChevronLeftIcon class="h-4 w-4" />
                </button>

                <!-- Page Numbers -->
                <div class="flex space-x-1">
                    <template v-for="pageNumber in displayedPages" :key="pageNumber">
                        <button v-if="pageNumber !== '...'" @click="goToPage(pageNumber)" :class="[
                            'px-3 py-1 rounded-md text-sm font-medium',
                            pagination.current_page === pageNumber
                                ? 'bg-blue-500 text-white'
                                : 'border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50'
                        ]">
                            {{ pageNumber }}
                        </button>
                        <span v-else class="px-2 py-1 text-gray-500">...</span>
                    </template>
                </div>

                <!-- Next -->
                <button @click="goToPage(pagination.current_page + 1)"
                    class="p-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="pagination.current_page === pagination.last_page">
                    <ChevronRightIcon class="h-4 w-4" />
                </button>

                <!-- Last Page -->
                <button @click="goToPage(pagination.last_page)"
                    class="p-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="pagination.current_page === pagination.last_page">
                    <ChevronDoubleRightIcon class="h-4 w-4" />
                </button>
            </div>
        </div>
    </div>
</template>
  
<script setup>
import { computed, ref, watch } from 'vue'
import {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from '@headlessui/vue'
import {
    ChevronUpDownIcon,
    ChevronDoubleLeftIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    ChevronDoubleRightIcon,
} from '@heroicons/vue/24/outline'

// Props
const props = defineProps({
    pagination: {
        type: Object,
        required: true,
        validator: (value) => {
            return value &&
                typeof value.current_page === 'number' &&
                typeof value.last_page === 'number'
        }
    },
    perPage: {
        type: Number,
        default: 10
    },
    perPageOptions: {
        type: Array,
        default: () => [5, 10, 25, 50, 100]
    },
    maxDisplayedPages: {
        type: Number,
        default: 5
    }
})

// Emits
const emit = defineEmits(['page-changed', 'per-page-changed'])

// Local state
const currentPerPage = ref(props.perPage)

// Watch for prop changes
watch(() => props.perPage, (newValue) => {
    currentPerPage.value = newValue
})

// Computed
const displayedPages = computed(() => {
    const current = props.pagination.current_page
    const last = props.pagination.last_page
    const maxDisplayed = props.maxDisplayedPages

    if (last <= maxDisplayed) {
        return Array.from({ length: last }, (_, i) => i + 1)
    }

    const pages = []
    const half = Math.floor(maxDisplayed / 2)

    let start = Math.max(1, current - half)
    let end = Math.min(last, current + half)

    // Adjust if we're near the beginning
    if (current <= half) {
        end = Math.min(last, maxDisplayed)
    }

    // Adjust if we're near the end
    if (current > last - half) {
        start = Math.max(1, last - maxDisplayed + 1)
    }

    // Add first page if not included
    if (start > 1) {
        pages.push(1)
        if (start > 2) {
            pages.push('...')
        }
    }

    // Add range
    for (let i = start; i <= end; i++) {
        pages.push(i)
    }

    // Add last page if not included
    if (end < last) {
        if (end < last - 1) {
            pages.push('...')
        }
        pages.push(last)
    }

    return pages
})

// Methods
const goToPage = (page) => {
    if (page >= 1 && page <= props.pagination.last_page && page !== props.pagination.current_page) {
        emit('page-changed', page)
    }
}

const handlePerPageChange = (value) => {
    currentPerPage.value = value
    emit('per-page-changed', value)
}

// Expose methods for parent component
defineExpose({
    goToPage,
    handlePerPageChange
})
</script>