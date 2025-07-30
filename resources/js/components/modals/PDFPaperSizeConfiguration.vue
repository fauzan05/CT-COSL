<!-- PDF Paper Size Configuration Modal -->
<template>
    <!-- PDF Paper Size Modal -->
    <TransitionRoot appear :show="isPaperSizeModalOpen" as="template">
        <Dialog as="div" @close="closeModal" class="relative z-50">
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
                            class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800/80 p-6 text-left align-middle shadow-xl transition-all">
                            <!-- Close Button -->
                            <button @click="closeModal"
                                class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors duration-200 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                                aria-label="Close modal">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <!-- Modal Title -->
                            <DialogTitle as="h3"
                                class="text-lg font-medium leading-6 text-gray-900 mb-4 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                PDF Export Settings
                            </DialogTitle>

                            <!-- Modal Content -->
                            <div class="mt-4 space-y-4">
                                <p class="text-gray-700 dark:text-gray-200 text-sm">
                                    Select the paper size for your PDF export:
                                </p>

                                <!-- Paper Size Options -->
                                <div class="space-y-3">
                                    <div class="flex items-center space-x-3">
                                        <input id="a4" v-model="selectedPaperSize" type="radio" value="A4"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <label for="a4"
                                            class="text-sm font-medium text-gray-700 dark:text-gray-200 cursor-pointer">
                                            A4 (210 × 297 mm)
                                        </label>
                                    </div>

                                    <div class="flex items-center space-x-3">
                                        <input id="f4" v-model="selectedPaperSize" type="radio" value="F4"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <label for="f4"
                                            class="text-sm font-medium text-gray-700 dark:text-gray-200 cursor-pointer">
                                            F4 (210 × 330 mm)
                                        </label>
                                    </div>

                                    <div class="flex items-center space-x-3">
                                        <input id="letter" v-model="selectedPaperSize" type="radio" value="Letter"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <label for="letter"
                                            class="text-sm font-medium text-gray-700 dark:text-gray-200 cursor-pointer">
                                            Letter (216 × 279 mm)
                                        </label>
                                    </div>

                                    <div class="flex items-center space-x-3">
                                        <input id="legal" v-model="selectedPaperSize" type="radio" value="Legal"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <label for="legal"
                                            class="text-sm font-medium text-gray-700 dark:text-gray-200 cursor-pointer">
                                            Legal (216 × 356 mm)
                                        </label>
                                    </div>

                                    <div class="flex items-center space-x-3">
                                        <input id="custom" v-model="selectedPaperSize" type="radio" value="Custom"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <label for="custom"
                                            class="text-sm font-medium text-gray-700 dark:text-gray-200 cursor-pointer">
                                            Custom Size
                                        </label>
                                    </div>
                                </div>

                                <!-- Custom Size Inputs -->
                                <div v-if="selectedPaperSize === 'Custom'"
                                    class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg space-y-3">
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label for="customWidth"
                                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Width (mm)
                                            </label>
                                            <input id="customWidth" v-model.number="customSize.width" type="number" min="50"
                                                max="2000"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white text-sm"
                                                placeholder="210">
                                        </div>
                                        <div>
                                            <label for="customHeight"
                                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Height (mm)
                                            </label>
                                            <input id="customHeight" v-model.number="customSize.height" type="number"
                                                min="50" max="2000"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white text-sm"
                                                placeholder="297">
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Enter custom dimensions in millimeters (mm)
                                    </p>
                                </div>

                                <!-- Orientation -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                        Orientation:
                                    </label>
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center space-x-2">
                                            <input id="p" v-model="orientation" type="radio" value="P"
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                            <label for="p"
                                                class="text-sm text-gray-700 dark:text-gray-200 cursor-pointer">
                                                Portrait
                                            </label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input id="l" v-model="orientation" type="radio" value="L"
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                            <label for="l"
                                                class="text-sm text-gray-700 dark:text-gray-200 cursor-pointer">
                                                Landscape
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Actions -->
                            <div class="mt-6 flex justify-center space-x-3">
                                <button type="button"
                                    class="inline-flex justify-center cursor-pointer rounded-md border dark:text-white/75 border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:hover:bg-gray-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
                                    @click="closeModal">
                                    Cancel
                                </button>
                                <button type="button" :disabled="isExporting || !isValidConfiguration"
                                    @click="handleExportPDF"
                                    class="inline-flex justify-center cursor-pointer rounded-md border border-transparent px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span v-if="!isExporting"><i class="fas fa-file-pdf mr-1.5"></i> Export PDF</span>
                                    <span v-else class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Exporting...
                                    </span>
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
  
<script setup>
import { ref, computed, watch } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'

// Props
const props = defineProps({
    isPaperSizeModalOpen: {
        type: Boolean,
        default: false
    }
})

// Emits
const emit = defineEmits(['close-modal', 'export-pdf'])

// Reactive data
const selectedPaperSize = ref('A4')
const orientation = ref('P')
const customSize = ref({
    width: 210,
    height: 297
})
const isExporting = ref(false)

// Computed
const isValidConfiguration = computed(() => {
    if (selectedPaperSize.value === 'Custom') {
        return customSize.value.width > 0 && customSize.value.height > 0
    }
    return true
})

// Methods
const closeModal = () => {
    emit('close-modal')
}

const handleExportPDF = async () => {
    if (!isValidConfiguration.value) return

    isExporting.value = true

    try {
        const paperConfig = {
            size: selectedPaperSize.value,
            orientation: orientation.value,
            ...(selectedPaperSize.value === 'Custom' && {
                customSize: {
                    width: customSize.value.width,
                    height: customSize.value.height
                }
            })
        }

        emit('export-pdf', paperConfig)

        // Simulate export process
        // await new Promise(resolve => setTimeout(resolve, 2000))

        // closeModal()
    } catch (error) {
        console.error('Export failed:', error)
    } finally {
        isExporting.value = false
    }
}

// Watch for paper size changes to reset custom values
watch(selectedPaperSize, (newSize) => {
    if (newSize !== 'Custom') {
        // Reset custom size to A4 default when switching away from custom
        customSize.value = {
            width: 210,
            height: 297
        }
    }
})
</script>