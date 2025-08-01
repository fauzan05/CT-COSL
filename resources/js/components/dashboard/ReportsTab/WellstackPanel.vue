<template>
    <!-- modal create/update template -->
    <TransitionRoot appear :show="isTemplateModalOpen" as="template">
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
                            class="relative w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800/80 p-6 text-left align-middle shadow-xl transition-all">
                            <!-- Close Button -->
                            <button @click="closeModal"
                                class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors duration-200 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                                aria-label="Close modal">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4 dark:text-white">
                                {{ titleModal }}
                            </DialogTitle>
                            <form @submit.prevent="saveTemplate" class="flex flex-col gap-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
                                    <!-- Left Column -->
                                    <div class="flex flex-col">
                                        <!-- Name -->
                                        <div class="mb-4">
                                            <label for="name"
                                                class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                Name <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="name" v-model="templateForm.name"
                                                class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-600"
                                                required>
                                        </div>
                                        <!-- Client -->
                                        <div class="mb-4">
                                            <label for="client"
                                                class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                Client
                                            </label>
                                            <input type="text" id="client" v-model="templateForm.client"
                                                class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-600">
                                        </div>
                                        <!-- Field -->
                                        <div class="mb-4">
                                            <label for="field"
                                                class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                Field
                                            </label>
                                            <input type="text" id="field" v-model="templateForm.field"
                                                class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-600">
                                        </div>
                                        <!-- Well Name Number -->
                                        <div class="mb-4">
                                            <label for="well_name"
                                                class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                Well Name & Number
                                            </label>
                                            <input type="text" id="well_name" v-model="templateForm.well_name_number"
                                                class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-600">
                                        </div>
                                        <!-- Min Restriction -->
                                        <div class="mb-4">
                                            <label for="min_restriction"
                                                class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                Min Restriction
                                            </label>
                                            <input type="text" id="min_restriction" v-model="templateForm.min_restriction"
                                                class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-600">
                                        </div>
                                        <!-- KOP -->
                                        <div class="mb-4">
                                            <label for="kop"
                                                class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                KOP
                                            </label>
                                            <input type="text" id="kop" v-model="templateForm.kop"
                                                class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-600">
                                        </div>
                                        <!-- Category -->
                                        <div class="mb-4">
                                            <label for="category"
                                                class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                Category
                                            </label>
                                            <input type="text" id="category" v-model="templateForm.category"
                                                class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-600">
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="flex flex-col">
                                        <!-- BHP -->
                                        <div class="mb-4">
                                            <label for="bhp"
                                                class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                BHP
                                            </label>
                                            <input type="text" id="bhp" v-model="templateForm.bhp"
                                                class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-600">
                                        </div>
                                        <!-- BHST -->
                                        <div class="mb-4">
                                            <label for="bhst"
                                                class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                BHST
                                            </label>
                                            <input type="text" id="bhst" v-model="templateForm.bhst"
                                                class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-600">
                                        </div>
                                        <!-- SO -->
                                        <div class="mb-4">
                                            <label for="so"
                                                class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                S/O
                                            </label>
                                            <input type="text" id="so" v-model="templateForm.so"
                                                class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-600">
                                        </div>
                                        <!-- Supplier -->
                                        <div class="mb-4">
                                            <label for="supplier"
                                                class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                Supplier
                                            </label>
                                            <input type="text" id="supplier" v-model="templateForm.supplier"
                                                class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-600">
                                        </div>
                                        <!-- Date Drawn -->
                                        <div class="mb-4">
                                            <label for="date"
                                                class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                Date Drawn <span class="text-red-500">*</span>
                                            </label>
                                            <input type="date" id="date" v-model="templateForm.date_drawn"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-600 dark:text-white"
                                                required>
                                        </div>
                                        <!-- Drawn By -->
                                        <div class="mb-4">
                                            <label for="drawn_by"
                                                class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                Drawn By
                                            </label>
                                            <input type="text" id="drawn_by" v-model="templateForm.drawn_by"
                                                class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-600">
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex justify-center space-x-3">
                                    <button type="button"
                                        class="inline-flex justify-center cursor-pointer rounded-md border dark:text-white/75 border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:hover:bg-gray-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
                                        @click="closeModal">
                                        Cancel
                                    </button>
                                    <button type="submit" :disabled="loading"
                                        class="inline-flex justify-center cursor-pointer rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span v-if="!loading">{{ titleModalButton }}</span>
                                        <span v-else class="flex items-center">
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4">
                                                </circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
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
    <!-- modal create/update report -->
    <TransitionRoot appear :show="isReportingModalOpen" as="template">
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
                            class="relative w-full max-w-8xl h-auto transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800/80 p-6 text-left align-middle shadow-xl transition-all">
                            <!-- Close Button -->
                            <button @click="closeModal"
                                class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors duration-200 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                                aria-label="Close modal">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4 dark:text-white">
                                {{ titleModal }}
                            </DialogTitle>
                            <div class="flex flex-col gap-6">
                                <!-- Form Identity -->
                                <div class="gap-4">
                                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                                        Template Identity
                                    </h4>
                                    <div class="w-full flex flex-col justify-between">
                                        <!-- Baris Pertama -->
                                        <div class="flex gap-5 mb-4">
                                            <div class="w-1/6">
                                                <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                    Name
                                                </label>
                                                <input type="text" v-model="templateForm.name"
                                                    class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md"
                                                    disabled>
                                            </div>
                                            <div class="w-1/6">
                                                <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                    Client
                                                </label>
                                                <input type="text" v-model="templateForm.client"
                                                    class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md"
                                                    disabled>
                                            </div>
                                            <div class="w-1/6">
                                                <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                    Field
                                                </label>
                                                <input type="text" v-model="templateForm.field"
                                                    class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md"
                                                    disabled>
                                            </div>
                                            <div class="w-1/6">
                                                <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                    Well Name & Number
                                                </label>
                                                <input type="text" v-model="templateForm.well_name_number"
                                                    class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md"
                                                    disabled>
                                            </div>
                                            <div class="w-1/6">
                                                <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                    Min Restriction
                                                </label>
                                                <input type="text" v-model="templateForm.min_restriction"
                                                    class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md"
                                                    disabled>
                                            </div>
                                            <div class="w-1/6">
                                                <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                    KOP
                                                </label>
                                                <input type="text" v-model="templateForm.kop"
                                                    class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md"
                                                    disabled>
                                            </div>
                                        </div>

                                        <!-- Baris Kedua -->
                                        <div class="flex gap-5">
                                            <div class="w-1/6">
                                                <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                    Category
                                                </label>
                                                <input type="text" v-model="templateForm.category"
                                                    class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md"
                                                    disabled>
                                            </div>
                                            <div class="w-1/6">
                                                <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                    BHP
                                                </label>
                                                <input type="text" v-model="templateForm.bhp"
                                                    class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md"
                                                    disabled>
                                            </div>
                                            <div class="w-1/6">
                                                <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                    BHST
                                                </label>
                                                <input type="text" v-model="templateForm.bhst"
                                                    class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md"
                                                    disabled>
                                            </div>
                                            <div class="w-1/6">
                                                <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                    S/O
                                                </label>
                                                <input type="text" v-model="templateForm.so"
                                                    class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md"
                                                    disabled>
                                            </div>
                                            <div class="w-1/6">
                                                <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                    Supplier
                                                </label>
                                                <input type="text" v-model="templateForm.supplier"
                                                    class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md"
                                                    disabled>
                                            </div>
                                            <div class="w-1/6">
                                                <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                    Drawn By
                                                </label>
                                                <input type="text" v-model="templateForm.drawn_by"
                                                    class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md"
                                                    disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Navigation Selection Component  -->
                                <div
                                    class="bg-white w-full rounded-xl shadow-md p-4 sm:p-6 border border-gray-100 dark:bg-slate-800/50 dark:border-slate-700/50">
                                    <div class="flex flex-wrap gap-3 mb-4">
                                        <!-- Add Component Button -->
                                        <button :disabled="AddComponentLoading" @click="handleAddComponent"
                                            class="inline-flex items-center justify-center h-10 px-4 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                            <span v-if="!AddComponentLoading" class="flex items-center gap-2">
                                                <PlusIcon class="w-5 h-5" />
                                                <span class="font-medium">Add Component</span>
                                            </span>
                                            <span v-else class="flex items-center gap-2">
                                                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                        stroke-width="4" />
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                                </svg>
                                                <span class="font-medium">Loading...</span>
                                            </span>
                                        </button>

                                        <!-- Update Position Button -->
                                        <button :disabled="updatePositionLoading" @click="handleUpdatePosition"
                                            class="inline-flex items-center justify-center h-10 px-4 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                            <span v-if="!updatePositionLoading" class="flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                                </svg>
                                                <span class="font-medium">Update Position</span>
                                            </span>
                                            <span v-else class="flex items-center gap-2">
                                                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                        stroke-width="4" />
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                                </svg>
                                                <span class="font-medium">Loading...</span>
                                            </span>
                                        </button>

                                        <!-- Export PDF Button -->
                                        <button :disabled="exportPDFLoading" @click="handleExportPDF"
                                            class="inline-flex items-center justify-center h-10 px-4 bg-red-600 text-white rounded-lg shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                            <span v-if="!exportPDFLoading" class="flex items-center gap-2">
                                                <i class="fas fa-file-pdf mr-1.5"></i>
                                                <span class="font-medium">Export to PDF</span>
                                            </span>
                                            <span v-else class="flex items-center gap-2">
                                                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                        stroke-width="4" />
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                                </svg>
                                                <span class="font-medium">Loading...</span>
                                            </span>
                                        </button>
                                    </div>
                                    <div class="flex flex-col gap-4">
                                        <div class="flex flex-col sm:flex-row gap-4">
                                            <!-- Type -->
                                            <div class="w-full sm:w-1/3 flex flex-col">
                                                <label
                                                    class="text-sm font-medium text-gray-700 dark:text-white mb-1">Component
                                                    Type</label>
                                                <Combobox v-model="selectedType" class="w-full">
                                                    <div class="relative mt-1">
                                                        <div
                                                            class="relative w-full cursor-default overflow-hidden rounded-lg bg-white dark:bg-slate-800/50 text-left shadow-md border border-gray-300">
                                                            <ComboboxInput
                                                                class="w-full h-11 border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 dark:text-white bg-transparent focus:outline-none"
                                                                :displayValue="(type) => type?.name"
                                                                @change="queryTypes = $event.target.value"
                                                                placeholder="Search Component Type..." />
                                                            <ComboboxButton
                                                                class="absolute inset-y-0 right-0 flex items-center pr-2">
                                                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400"
                                                                    aria-hidden="true" />
                                                            </ComboboxButton>
                                                        </div>
                                                        <transition leave-active-class="transition duration-100 ease-in"
                                                            leave-from-class="opacity-100" leave-to-class="opacity-0">
                                                            <ComboboxOptions
                                                                class="absolute mt-1 z-40 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-slate-800 py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">

                                                                <div v-if="loading"
                                                                    class="relative cursor-default select-none px-4 py-2 text-gray-700 dark:text-gray-300">
                                                                    Loading...
                                                                </div>
                                                                <div v-else-if="types.length === 0"
                                                                    class="relative cursor-default select-none px-4 py-2 text-gray-700 dark:text-gray-300">
                                                                    Nothing found.
                                                                </div>

                                                                <ComboboxOption v-for="type in types" :key="type.id"
                                                                    :value="type" v-slot="{ selected, active }">
                                                                    <li
                                                                        :class="['relative cursor-default select-none py-2 pl-10 pr-4', active ? 'bg-blue-100 text-blue-900 dark:bg-gray-500 dark:text-white' : 'text-gray-900 dark:text-white']">
                                                                        <span
                                                                            :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                                                            {{ type.name }}
                                                                        </span>
                                                                        <span v-if="selected"
                                                                            class="absolute inset-y-0 left-0 flex items-center pl-3 text-blue-600 dark:text-white">
                                                                            <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                                                        </span>
                                                                    </li>
                                                                </ComboboxOption>
                                                            </ComboboxOptions>
                                                        </transition>
                                                    </div>
                                                </Combobox>
                                            </div>
                                            <!-- Component Tools -->
                                            <div class="w-full sm:w-1/3 flex flex-col">
                                                <label
                                                    class="text-sm font-medium text-gray-700 dark:text-white mb-1">Component
                                                    Tools</label>
                                                <Combobox v-model="selectedItem" class="w-full">
                                                    <div class="relative mt-1">
                                                        <div
                                                            class="relative w-full cursor-default overflow-hidden rounded-lg bg-white dark:bg-slate-800/50 text-left shadow-md border border-gray-300">
                                                            <ComboboxInput
                                                                class="w-full h-11 border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 dark:text-white bg-transparent focus:outline-none"
                                                                :displayValue="(item) => item?.name"
                                                                @change="queryItems = $event.target.value"
                                                                placeholder="Search Component Tools..." />
                                                            <ComboboxButton
                                                                class="absolute inset-y-0 right-0 flex items-center pr-2">
                                                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400"
                                                                    aria-hidden="true" />
                                                            </ComboboxButton>
                                                        </div>
                                                        <transition leave-active-class="transition duration-100 ease-in"
                                                            leave-from-class="opacity-100" leave-to-class="opacity-0">
                                                            <ComboboxOptions
                                                                class="absolute mt-1 z-40 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-slate-800 py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">

                                                                <div v-if="loading"
                                                                    class="relative cursor-default select-none px-4 py-2 text-gray-700 dark:text-gray-300">
                                                                    Loading...
                                                                </div>
                                                                <div v-else-if="items.length === 0"
                                                                    class="relative cursor-default select-none px-4 py-2 text-gray-700 dark:text-gray-300">
                                                                    Nothing found.
                                                                </div>

                                                                <ComboboxOption v-for="item in items" :key="item.id"
                                                                    :value="item" v-slot="{ selected, active }">
                                                                    <li :class="[
                                                                        'relative cursor-default select-none py-2 pl-10 pr-4',
                                                                        active ? 'bg-blue-100 text-blue-900 dark:bg-gray-500 dark:text-white' : 'text-gray-900 dark:text-white'
                                                                    ]">
                                                                        <span :class="[
                                                                            'block truncate',
                                                                            selected ? 'font-medium' : 'font-normal'
                                                                        ]">
                                                                            {{ item.name }}
                                                                        </span>
                                                                        <span v-if="selected"
                                                                            class="absolute inset-y-0 left-0 flex items-center pl-3 text-blue-600 dark:text-white">
                                                                            <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                                                        </span>
                                                                    </li>
                                                                </ComboboxOption>
                                                            </ComboboxOptions>
                                                        </transition>
                                                    </div>
                                                </Combobox>
                                            </div>
                                            <!-- Height PDF -->
                                            <div class="w-full sm:w-48 flex flex-col">
                                                <label class="text-sm font-medium text-gray-700 dark:text-white mb-1">Height
                                                    PDF (mm)</label>
                                                <input type="number" v-model="height_pdf"
                                                    class="w-full sm:w-48 h-11 mt-1 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-800/50 text-sm text-gray-900 dark:text-white px-3 py-2 focus:outline-none shadow-md"
                                                    placeholder="Height PDF (mm)" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Table Component -->
                            <div class="bg-white dark:bg-slate-800 rounded-xl my-5 shadow-md overflow-hidden">
                                <!-- Table container with fixed height and scroll -->
                                <div class="max-h-96 overflow-y-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <!-- Sticky Header -->
                                        <thead class="bg-gray-50 dark:bg-gray-800 sticky top-0">
                                            <tr>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                                                    No
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                                                    Image
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                                                    Description
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                                                    Serial Number
                                                </th>

                                                <!-- Height Column with Unit Dropdown -->
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                                                    <div class="flex items-center space-x-2">
                                                        <span>Height</span>
                                                        <Listbox v-model="selected_heightUnit">
                                                            <div class="relative">
                                                                <ListboxButton
                                                                    class="relative w-40 cursor-pointer rounded-md bg-white dark:bg-gray-700 py-1 px-2 text-left shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs">
                                                                    <span class="block truncate">{{ selected_heightUnit.name
                                                                    }}</span>
                                                                    <span
                                                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-1">
                                                                        <ChevronUpDownIcon class="h-3 w-3 text-gray-400"
                                                                            aria-hidden="true" />
                                                                    </span>
                                                                </ListboxButton>

                                                                <transition
                                                                    leave-active-class="transition duration-100 ease-in"
                                                                    leave-from-class="opacity-100"
                                                                    leave-to-class="opacity-0">
                                                                    <ListboxOptions
                                                                        class="absolute z-10 mt-1 max-h-32 w-40 overflow-auto rounded-md bg-white dark:bg-gray-700 py-1 text-xs shadow-lg ring-opacity-5 focus:outline-none">
                                                                        <ListboxOption v-slot="{ active, selected }"
                                                                            v-for="unit in heightUnits" :key="unit.value"
                                                                            :value="unit" as="template">
                                                                            <li :class="[
                                                                                active ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-900 dark:text-gray-100',
                                                                                'relative cursor-pointer select-none py-1 px-2'
                                                                            ]">
                                                                                <span v-if="selected"
                                                                                    class="absolute inset-y-0 right-0 flex items-center pr-1 text-blue-600 dark:text-blue-400">
                                                                                    <CheckIcon class="h-3 w-3"
                                                                                        aria-hidden="true" />
                                                                                </span>
                                                                                <span
                                                                                    :class="[selected ? 'font-medium' : 'font-normal', 'block truncate']">
                                                                                    {{ unit.name }}
                                                                                </span>
                                                                            </li>
                                                                        </ListboxOption>
                                                                    </ListboxOptions>
                                                                </transition>
                                                            </div>
                                                        </Listbox>
                                                    </div>
                                                </th>

                                                <!-- Weight Column with Unit Dropdown -->
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                                                    <div class="flex items-center space-x-2">
                                                        <span>Weight</span>
                                                        <Listbox v-model="selected_weightUnit">
                                                            <div class="relative">
                                                                <ListboxButton
                                                                    class="relative w-40 cursor-pointer rounded-md bg-white dark:bg-gray-700 py-1 px-2 text-left shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs">
                                                                    <span class="block truncate">{{ selected_weightUnit.name
                                                                    }}</span>
                                                                    <span
                                                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-1">
                                                                        <ChevronUpDownIcon class="h-3 w-3 text-gray-400"
                                                                            aria-hidden="true" />
                                                                    </span>
                                                                </ListboxButton>

                                                                <transition
                                                                    leave-active-class="transition duration-100 ease-in"
                                                                    leave-from-class="opacity-100"
                                                                    leave-to-class="opacity-0">
                                                                    <ListboxOptions
                                                                        class="absolute z-10 mt-1 max-h-32 w-40 overflow-auto rounded-md bg-white dark:bg-gray-700 py-1 text-xs shadow-lg ring-opacity-5 focus:outline-none">
                                                                        <ListboxOption v-slot="{ active, selected }"
                                                                            v-for="unit in weightUnits" :key="unit.value"
                                                                            :value="unit" as="template">
                                                                            <li :class="[
                                                                                active ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-900 dark:text-gray-100',
                                                                                'relative cursor-pointer select-none py-1 px-2'
                                                                            ]">
                                                                                <span v-if="selected"
                                                                                    class="absolute inset-y-0 right-0 flex items-center pr-1 text-blue-600 dark:text-blue-400">
                                                                                    <CheckIcon class="h-3 w-3"
                                                                                        aria-hidden="true" />
                                                                                </span>
                                                                                <span
                                                                                    :class="[selected ? 'font-medium' : 'font-normal', 'block truncate']">
                                                                                    {{ unit.name }}
                                                                                </span>
                                                                            </li>
                                                                        </ListboxOption>
                                                                    </ListboxOptions>
                                                                </transition>
                                                            </div>
                                                        </Listbox>
                                                    </div>
                                                </th>

                                                <!-- Pressure Rating Column with Unit Dropdown -->
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                                                    <div class="flex items-center space-x-2">
                                                        <span>Pressure Rating</span>
                                                        <Listbox v-model="selected_pressureRatingUnit">
                                                            <div class="relative">
                                                                <ListboxButton
                                                                    class="relative w-40 cursor-pointer rounded-md bg-white dark:bg-gray-700 py-1 px-2 text-left shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs">
                                                                    <span class="block truncate">{{
                                                                        selected_pressureRatingUnit.name }}</span>
                                                                    <span
                                                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-1">
                                                                        <ChevronUpDownIcon class="h-3 w-3 text-gray-400"
                                                                            aria-hidden="true" />
                                                                    </span>
                                                                </ListboxButton>

                                                                <transition
                                                                    leave-active-class="transition duration-100 ease-in"
                                                                    leave-from-class="opacity-100"
                                                                    leave-to-class="opacity-0">
                                                                    <ListboxOptions
                                                                        class="absolute z-10 mt-1 max-h-32 w-40 overflow-auto rounded-md bg-white dark:bg-gray-700 py-1 text-xs shadow-lg ring-opacity-5 focus:outline-none">
                                                                        <ListboxOption v-slot="{ active, selected }"
                                                                            v-for="unit in pressureRatingUnits"
                                                                            :key="unit.value" :value="unit" as="template">
                                                                            <li :class="[
                                                                                active ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-900 dark:text-gray-100',
                                                                                'relative cursor-pointer select-none py-1 px-2'
                                                                            ]">
                                                                                <span v-if="selected"
                                                                                    class="absolute inset-y-0 right-0 flex items-center pr-1 text-blue-600 dark:text-blue-400">
                                                                                    <CheckIcon class="h-3 w-3"
                                                                                        aria-hidden="true" />
                                                                                </span>
                                                                                <span
                                                                                    :class="[selected ? 'font-medium' : 'font-normal', 'block truncate']">
                                                                                    {{ unit.name }}
                                                                                </span>
                                                                            </li>
                                                                        </ListboxOption>
                                                                    </ListboxOptions>
                                                                </transition>
                                                            </div>
                                                        </Listbox>
                                                    </div>
                                                </th>

                                                <!-- Shear Ram Dist from Bottom Column with Unit Dropdown -->
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                                                    <div class="flex items-center space-x-2">
                                                        <span>Shear Ram Dist from Bottom</span>
                                                        <Listbox v-model="selected_shearRamDistFromBottomUnit">
                                                            <div class="relative">
                                                                <ListboxButton
                                                                    class="relative w-40 cursor-pointer rounded-md bg-white dark:bg-gray-700 py-1 px-2 text-left shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs">
                                                                    <span class="block truncate">{{
                                                                        selected_shearRamDistFromBottomUnit.name }}</span>
                                                                    <span
                                                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-1">
                                                                        <ChevronUpDownIcon class="h-3 w-3 text-gray-400"
                                                                            aria-hidden="true" />
                                                                    </span>
                                                                </ListboxButton>

                                                                <transition
                                                                    leave-active-class="transition duration-100 ease-in"
                                                                    leave-from-class="opacity-100"
                                                                    leave-to-class="opacity-0">
                                                                    <ListboxOptions
                                                                        class="absolute z-10 mt-1 max-h-32 w-40 overflow-auto rounded-md bg-white dark:bg-gray-700 py-1 text-xs shadow-lg ring-opacity-5 focus:outline-none">
                                                                        <ListboxOption v-slot="{ active, selected }"
                                                                            v-for="unit in shearRamDistFromBottomUnits"
                                                                            :key="unit.value" :value="unit" as="template">
                                                                            <li :class="[
                                                                                active ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-900 dark:text-gray-100',
                                                                                'relative cursor-pointer select-none py-1 px-2'
                                                                            ]">
                                                                                <span v-if="selected"
                                                                                    class="absolute inset-y-0 right-0 flex items-center pr-1 text-blue-600 dark:text-blue-400">
                                                                                    <CheckIcon class="h-3 w-3"
                                                                                        aria-hidden="true" />
                                                                                </span>
                                                                                <span
                                                                                    :class="[selected ? 'font-medium' : 'font-normal', 'block truncate']">
                                                                                    {{ unit.name }}
                                                                                </span>
                                                                            </li>
                                                                        </ListboxOption>
                                                                    </ListboxOptions>
                                                                </transition>
                                                            </div>
                                                        </Listbox>
                                                    </div>
                                                </th>

                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                                                    BHI or 3d Party
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 tracking-wider">
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>

                                        <!-- Loading Skeleton -->
                                        <tbody v-if="componentListLoading"
                                            class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                            <tr v-for="n in 3" :key="'loading-' + n">
                                                <td colspan="10" class="px-6 py-4">
                                                    <div
                                                        class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse w-full">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>

                                        <!-- Draggable Items with Unit Conversion -->
                                        <draggable v-else v-model="componentList" tag="tbody" item-key="component_id"
                                            @end="updatePositions"
                                            class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                            <template #item="{ element, index }">
                                                <tr>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                        {{ index + 1 }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <img :src="element.image" alt="Component image"
                                                            class="h-10 w-10 object-contain" />
                                                    </td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                        {{ element.description }}
                                                    </td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                        {{ element.serial_number }}
                                                    </td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                        {{ getConvertedComponent(element).displayHeight }}
                                                    </td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                        {{ getConvertedComponent(element).displayWeight }}
                                                    </td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                        {{ getConvertedComponent(element).displayPressureRating }}
                                                    </td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                        {{ getConvertedComponent(element).displayShearRamDistFromBottom }}
                                                    </td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                        {{ element.owner }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        <button @click="removeComponent(index, element)"
                                                            :disabled="element.isRemoving"
                                                            class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-red-600 hover:text-red-900 hover:bg-red-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                                                            <div v-if="element.isRemoving" class="spinner mr-2"></div>
                                                            <svg v-else class="w-4 h-4 mr-1" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            {{ element.isRemoving ? 'Removing...' : 'Remove' }}
                                                        </button>
                                                    </td>
                                                </tr>
                                            </template>

                                            <!-- Empty state -->
                                            <template #footer>
                                                <tr v-if="componentList.length === 0">
                                                    <td colspan="10"
                                                        class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                                        No components found
                                                    </td>
                                                </tr>
                                            </template>
                                        </draggable>
                                    </table>
                                </div>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
    <!-- Delete Confirmation Modal -->
    <TransitionRoot appear :show="isDeleteModalOpen" as="template">
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
                                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Confirm Delete
                            </DialogTitle>

                            <!-- Modal Content -->
                            <div class="mt-4 text-center space-y-4">
                                <p class="text-gray-700 dark:text-gray-200">
                                    Are you sure you want to delete the template
                                    <span class="font-semibold text-red-600">{{ selectedTemplate?.name }}</span>?
                                    This action cannot be undone.
                                </p>
                            </div>

                            <!-- Modal Actions -->
                            <div class="mt-6 flex justify-center space-x-3">
                                <button type="button"
                                    class="inline-flex justify-center cursor-pointer rounded-md border dark:text-white/75 border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:hover:bg-gray-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
                                    @click="closeModal">
                                    Cancel
                                </button>
                                <button type="button" :disabled="isDeleting" @click="handleDeleteTemplate"
                                    class="inline-flex justify-center cursor-pointer rounded-md border border-transparent px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2">
                                    <span v-if="!isDeleting">Delete</span>
                                    <span v-else class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Deleting...
                                    </span>
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
    <div class="rounded-xl bg-white dark:bg-slate-800/50 p-6">
        <div class="space-y-6">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <!-- Loading State -->
                    <template v-if="isLoadingData">
                        <div class="animate-pulse">
                            <!-- Title Skeleton -->
                            <div class="w-48 h-8 bg-gray-200 dark:bg-slate-600 rounded-lg mb-2"></div>
                            <!-- Subtitle Skeleton -->
                            <div class="w-72 h-5 bg-gray-200 dark:bg-slate-600 rounded-lg"></div>
                        </div>
                    </template>

                    <!-- Actual Content -->
                    <template v-else>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Wellstack Reporting</h1>
                        <p class="text-gray-600 dark:text-gray-400">Design and export Wellstack reports with selected items
                            and parameters. Then you can exported it to
                            pdf.</p>
                    </template>
                </div>
                <button @click="openModal('create')"
                    class="bg-blue-600 cursor-pointer hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Add Template</span>
                </button>
            </div>

            <!-- Search and Filter Section -->
            <div
                class="bg-white rounded-xl shadow-md p-4 sm:p-6 border border-gray-100 mb-6 dark:bg-slate-800/50 dark:border-slate-700/50">
                <!-- Mobile: Stack everything vertically -->
                <div class="space-y-4">

                    <!-- Search Bar - Full width on all screens -->
                    <div class="w-full">
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input v-model="search" type="text" placeholder="Search Templates..."
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                        </div>
                    </div>

                    <!-- Mobile: Show/Hide Filters Toggle -->
                    <div class="block sm:hidden">
                        <button @click="showMobileFilters = !showMobileFilters"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-200 rounded-lg border border-gray-200 dark:border-slate-600 hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                            </svg>
                            <span class="text-sm font-medium">
                                {{ showMobileFilters ? 'Hide Filters' : 'Show Filters' }}
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4 transition-transform"
                                :class="{ 'rotate-180': showMobileFilters }">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                    </div>

                    <!-- Filters Container -->
                    <div class="space-y-4 sm:space-y-0" :class="{ 'hidden sm:block': !showMobileFilters }">

                        <!-- Mobile: Grid for Refresh button -->
                        <div class="block sm:hidden">
                            <button @click="fetchAllTemplates" :disabled="loading"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                <span v-if="!loading" class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                    Refresh Data
                                </span>
                                <span v-else class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                    </svg>
                                    Loading...
                                </span>
                            </button>
                        </div>

                        <!-- Desktop: Horizontal layout -->
                        <div class="hidden sm:flex sm:items-center sm:justify-between sm:space-x-4">
                            <!-- Refresh Button -->
                            <button @click="fetchAllTemplates" :disabled="loading"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                <span v-if="!loading" class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                    Refresh Data
                                </span>
                                <span v-else class="flex items-center gap-1">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                    </svg>
                                    Loading...
                                </span>
                            </button>

                            <div class="flex items-center space-x-3">
                                <!-- Sort By Filter -->
                                <div class="w-40">
                                    <Listbox v-model="selectedSortByFilter">
                                        <div class="relative">
                                            <ListboxButton
                                                class="relative w-full cursor-default rounded-lg bg-white dark:bg-slate-800/50 py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-blue-500 focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-blue-300 sm:text-sm border border-gray-200 dark:border-slate-600">
                                                <span class="block truncate text-gray-900 dark:text-white">{{
                                                    selectedSortByFilter.name }}</span>
                                                <span
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                                                </span>
                                            </ListboxButton>

                                            <transition leave-active-class="transition duration-100 ease-in"
                                                leave-from-class="opacity-100" leave-to-class="opacity-0">
                                                <ListboxOptions
                                                    class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-slate-800 py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
                                                    <ListboxOption v-slot="{ active, selected }"
                                                        v-for="sortByItem in sortByItems" :key="sortByItem.name"
                                                        :value="sortByItem" as="template">
                                                        <li :class="[
                                                            active ? 'bg-blue-100 text-blue-900 dark:bg-gray-500 dark:text-white' : 'text-gray-900 dark:text-white',
                                                            'relative cursor-default select-none py-2 pl-10 pr-4',
                                                        ]">
                                                            <span
                                                                :class="[selected ? 'font-medium' : 'font-normal', 'block truncate']">{{
                                                                    sortByItem.name }}</span>
                                                            <span v-if="selected"
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-blue-600 dark:text-white">
                                                                <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                                            </span>
                                                        </li>
                                                    </ListboxOption>
                                                </ListboxOptions>
                                            </transition>
                                        </div>
                                    </Listbox>
                                </div>

                                <!-- Page Size Filter -->
                                <div class="w-20">
                                    <Listbox v-model="selectedPageSizeFilter">
                                        <div class="relative">
                                            <ListboxButton
                                                class="relative w-full cursor-default rounded-lg bg-white dark:bg-slate-800/50 py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-blue-500 focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-blue-300 sm:text-sm border border-gray-200 dark:border-slate-600">
                                                <span class="block truncate text-gray-900 dark:text-white">{{
                                                    selectedPageSizeFilter.name }}</span>
                                                <span
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                                                </span>
                                            </ListboxButton>

                                            <transition leave-active-class="transition duration-100 ease-in"
                                                leave-from-class="opacity-100" leave-to-class="opacity-0">
                                                <ListboxOptions
                                                    class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-slate-800 py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
                                                    <ListboxOption v-slot="{ active, selected }"
                                                        v-for="pageSizeItem in pageSizeItems" :key="pageSizeItem.name"
                                                        :value="pageSizeItem" as="template">
                                                        <li :class="[
                                                            active ? 'bg-blue-100 text-blue-900 dark:bg-gray-500 dark:text-white' : 'text-gray-900 dark:text-white',
                                                            'relative cursor-default select-none py-2 pl-10 pr-4',
                                                        ]">
                                                            <span
                                                                :class="[selected ? 'font-medium' : 'font-normal', 'block truncate']">{{
                                                                    pageSizeItem.name }}</span>
                                                            <span v-if="selected"
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-blue-600 dark:text-white">
                                                                <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                                            </span>
                                                        </li>
                                                    </ListboxOption>
                                                </ListboxOptions>
                                            </transition>
                                        </div>
                                    </Listbox>
                                </div>

                                <!-- Sort Direction Toggle -->
                                <SwitchGroup as="div" class="flex items-center space-x-2">
                                    <SwitchLabel as="span" class="text-sm text-gray-700 dark:text-white">Asc
                                    </SwitchLabel>
                                    <Switch v-model="isDesc" :class="isDesc ? 'bg-blue-600' : 'bg-gray-400'"
                                        class="relative inline-flex items-center h-6 w-11 shrink-0 cursor-pointer rounded-full border border-gray-200 dark:border-slate-600 transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75">
                                        <span class="sr-only">Toggle sort direction</span>
                                        <span aria-hidden="true" :class="isDesc ? 'translate-x-5' : 'translate-x-0'"
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out" />
                                    </Switch>
                                    <SwitchLabel as="span" class="text-sm text-gray-700 dark:text-white">
                                        Desc</SwitchLabel>
                                </SwitchGroup>
                            </div>
                        </div>

                        <!-- Mobile: Vertical Grid Layout for Filters -->
                        <div class="grid grid-cols-1 gap-4 sm:hidden">
                            <!-- Row 1: Status and Sort By -->
                            <div class="grid grid-cols-2 gap-3">
                                <!-- Status Filter -->
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                    <Listbox v-model="selectedStatusFilter">
                                        <div class="relative">
                                            <ListboxButton
                                                class="relative w-full cursor-default rounded-lg bg-white dark:bg-slate-800/50 py-2.5 pl-3 pr-8 text-left shadow-md focus:outline-none focus-visible:border-blue-500 focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-blue-300 text-sm border border-gray-200 dark:border-slate-600">
                                                <span class="block truncate text-gray-900 dark:text-white">{{
                                                    selectedStatusFilter.name }}</span>
                                                <span
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                                    <ChevronUpDownIcon class="h-4 w-4 text-gray-400" aria-hidden="true" />
                                                </span>
                                            </ListboxButton>

                                            <transition leave-active-class="transition duration-100 ease-in"
                                                leave-from-class="opacity-100" leave-to-class="opacity-0">
                                                <ListboxOptions
                                                    class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-slate-800 py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
                                                    <ListboxOption v-slot="{ active, selected }"
                                                        v-for="statusItem in statusItems" :key="statusItem.name"
                                                        :value="statusItem" as="template">
                                                        <li :class="[
                                                            active ? 'bg-blue-100 text-blue-900 dark:bg-gray-500 dark:text-white' : 'text-gray-900 dark:text-white',
                                                            'relative cursor-default select-none py-2 pl-10 pr-4',
                                                        ]">
                                                            <span
                                                                :class="[selected ? 'font-medium' : 'font-normal', 'block truncate']">{{
                                                                    statusItem.name }}</span>
                                                            <span v-if="selected"
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-blue-600 dark:text-white">
                                                                <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                                            </span>
                                                        </li>
                                                    </ListboxOption>
                                                </ListboxOptions>
                                            </transition>
                                        </div>
                                    </Listbox>
                                </div>

                                <!-- Sort By Filter -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Sort
                                        By</label>
                                    <Listbox v-model="selectedSortByFilter">
                                        <div class="relative">
                                            <ListboxButton
                                                class="relative w-full cursor-default rounded-lg bg-white dark:bg-slate-800/50 py-2.5 pl-3 pr-8 text-left shadow-md focus:outline-none focus-visible:border-blue-500 focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-blue-300 text-sm border border-gray-200 dark:border-slate-600">
                                                <span class="block truncate text-gray-900 dark:text-white">{{
                                                    selectedSortByFilter.name }}</span>
                                                <span
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                                    <ChevronUpDownIcon class="h-4 w-4 text-gray-400" aria-hidden="true" />
                                                </span>
                                            </ListboxButton>

                                            <transition leave-active-class="transition duration-100 ease-in"
                                                leave-from-class="opacity-100" leave-to-class="opacity-0">
                                                <ListboxOptions
                                                    class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-slate-800 py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
                                                    <ListboxOption v-slot="{ active, selected }"
                                                        v-for="sortByItem in sortByItems" :key="sortByItem.name"
                                                        :value="sortByItem" as="template">
                                                        <li :class="[
                                                            active ? 'bg-blue-100 text-blue-900 dark:bg-gray-500 dark:text-white' : 'text-gray-900 dark:text-white',
                                                            'relative cursor-default select-none py-2 pl-10 pr-4',
                                                        ]">
                                                            <span
                                                                :class="[selected ? 'font-medium' : 'font-normal', 'block truncate']">{{
                                                                    sortByItem.name }}</span>
                                                            <span v-if="selected"
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-blue-600 dark:text-white">
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

                            <!-- Row 2: Page Size and Sort Direction -->
                            <div class="grid grid-cols-2 gap-3">
                                <!-- Page Size Filter -->
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Items</label>
                                    <Listbox v-model="selectedPageSizeFilter">
                                        <div class="relative">
                                            <ListboxButton
                                                class="relative w-full cursor-default rounded-lg bg-white dark:bg-slate-800/50 py-2.5 pl-3 pr-8 text-left shadow-md focus:outline-none focus-visible:border-blue-500 focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-blue-300 text-sm border border-gray-200 dark:border-slate-600">
                                                <span class="block truncate text-gray-900 dark:text-white">{{
                                                    selectedPageSizeFilter.name }}</span>
                                                <span
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                                    <ChevronUpDownIcon class="h-4 w-4 text-gray-400" aria-hidden="true" />
                                                </span>
                                            </ListboxButton>

                                            <transition leave-active-class="transition duration-100 ease-in"
                                                leave-from-class="opacity-100" leave-to-class="opacity-0">
                                                <ListboxOptions
                                                    class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-slate-800 py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
                                                    <ListboxOption v-slot="{ active, selected }"
                                                        v-for="pageSizeItem in pageSizeItems" :key="pageSizeItem.name"
                                                        :value="pageSizeItem" as="template">
                                                        <li :class="[
                                                            active ? 'bg-blue-100 text-blue-900 dark:bg-gray-500 dark:text-white' : 'text-gray-900 dark:text-white',
                                                            'relative cursor-default select-none py-2 pl-10 pr-4',
                                                        ]">
                                                            <span
                                                                :class="[selected ? 'font-medium' : 'font-normal', 'block truncate']">{{
                                                                    pageSizeItem.name }}</span>
                                                            <span v-if="selected"
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-blue-600 dark:text-white">
                                                                <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                                            </span>
                                                        </li>
                                                    </ListboxOption>
                                                </ListboxOptions>
                                            </transition>
                                        </div>
                                    </Listbox>
                                </div>

                                <!-- Sort Direction Toggle -->
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Direction</label>
                                    <div
                                        class="flex items-center justify-center h-10 bg-gray-50 dark:bg-slate-700 rounded-lg border border-gray-200 dark:border-slate-600">
                                        <SwitchGroup as="div" class="flex items-center space-x-2">
                                            <SwitchLabel as="span" class="text-sm text-gray-700 dark:text-white">Asc
                                            </SwitchLabel>
                                            <Switch v-model="isDesc" :class="isDesc ? 'bg-blue-600' : 'bg-gray-400'"
                                                class="relative inline-flex items-center h-5 w-9 shrink-0 cursor-pointer rounded-full border border-gray-200 dark:border-slate-600 transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75">
                                                <span class="sr-only">Toggle sort direction</span>
                                                <span aria-hidden="true" :class="isDesc ? 'translate-x-4' : 'translate-x-0'"
                                                    class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out" />
                                            </Switch>
                                            <SwitchLabel as="span" class="text-sm text-gray-700 dark:text-white">Desc
                                            </SwitchLabel>
                                        </SwitchGroup>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- List Template Table -->
            <div class="bg-white dark:bg-slate-800/70 rounded-xl shadow-sm">
                <div class="p-6">
                    <h4 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">List Template</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Client</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Field</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Well Name & Number</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Min. Restriction</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        KOP</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Category</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        BHP</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        BHST</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        SO</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Supplier</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Date Drawn</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Drawn By</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Updated At</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Updated By</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Action</th>
                                </tr>
                            </thead>
                            <!-- Loading State -->
                            <tbody v-if="isLoadingData" class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="n in 5" :key="n" class="animate-pulse">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="h-4 bg-gray-200 dark:bg-slate-600 rounded w-8"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="h-4 bg-gray-200 dark:bg-slate-600 rounded w-32"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="h-4 bg-gray-200 dark:bg-slate-600 rounded w-40"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="h-4 bg-gray-200 dark:bg-slate-600 rounded w-28"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="h-4 bg-gray-200 dark:bg-slate-600 rounded w-24"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="h-4 bg-gray-200 dark:bg-slate-600 rounded w-24"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="h-4 bg-gray-200 dark:bg-slate-600 rounded w-36"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="h-4 bg-gray-200 dark:bg-slate-600 rounded w-36"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="h-4 bg-gray-200 dark:bg-slate-600 rounded w-28"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="h-4 bg-gray-200 dark:bg-slate-600 rounded w-28"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="h-4 bg-gray-200 dark:bg-slate-600 rounded w-28"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="h-4 bg-gray-200 dark:bg-slate-600 rounded w-28"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="h-4 bg-gray-200 dark:bg-slate-600 rounded w-28"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="h-4 bg-gray-200 dark:bg-slate-600 rounded w-28"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="h-4 bg-gray-200 dark:bg-slate-600 rounded w-28"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="h-4 bg-gray-200 dark:bg-slate-600 rounded w-28"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="h-4 bg-gray-200 dark:bg-slate-600 rounded w-28"></div>
                                    </td>
                                </tr>
                            </tbody>
                            <!-- Actual Content -->
                            <tbody v-else class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="(template, index) in listTemplates" :key="template.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ ++index }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ template.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ template.client }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ template.field }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ template.well_name_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ template.min_restriction }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ template.kop }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ template.category }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ template.bhp }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ template.bhst }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ template.so }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ template.supplier }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ formatDateWithoutTime(template.date_drawn) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ template.drawn_by }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ formatDate(template.updated_at) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ template.updated_by_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        <div class="flex justify-end gap-2">
                                            <button @click="openModal('update', template)"
                                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-md hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors duration-150">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                Edit
                                            </button>
                                            <button @click="openReportModal('create', template)"
                                                class="inline-flex items-center px-3 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-md hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors duration-150">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V7a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                Create Report
                                            </button>
                                            <button @click="confirmDeleteModal(template)"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-md hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors duration-150">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted, computed } from 'vue';
import { useToast } from 'vue-toastification';
import draggable from 'vuedraggable';

import {
    TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle,
    Listbox, ListboxButton, ListboxOptions, ListboxOption,
    Switch, SwitchGroup, SwitchLabel,
    Combobox, ComboboxInput, ComboboxButton, ComboboxOptions, ComboboxOption
} from '@headlessui/vue';

import {
    ChevronUpDownIcon, CheckIcon, PlusIcon, ClipboardDocumentIcon
} from '@heroicons/vue/20/solid';

/* --------------------------- DATA LIST & TABLE ----------------------------- */
const sortByItems = [
    { name: 'Name', value: 'name' },
    { name: 'Created Date', value: 'created_at' },
    { name: 'Updated Date', value: 'updated_at' },
];

const statusItems = [
    { name: 'Active', value: 'active' },
    { name: 'Inactive', value: 'inactive' },
    { name: 'All', value: 'all' },
];

const pageSizeItems = [
    { name: '10', value: 10 },
    { name: '25', value: 25 },
    { name: '50', value: 50 },
    { name: '100', value: 100 },
];

const listTemplates = ref([]);
const baseUrl = import.meta.env.VITE_API_URL;

const types = ref([]);
const items = ref([]);
const itemDimensions = ref([]);

const filteredTypes = ref([]);
const filteredItems = ref([]);

const selectedType = ref(null);
const selectedItem = ref(null);
const selectedItemDimension = ref(null);

const queryTypes = ref('');
const queryItems = ref('');
const queryItemDimensions = ref('');

const filteredItemDimensions = ref([]);
const componentListLoading = ref(false);
const AddComponentLoading = ref(false);
const updatePositionLoading = ref(false);
const isReportingModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const selectedTemplate = ref(null)
const isDeleting = ref(false);

const length_unit = ref('inch');
const weight_unit = ref('lbs');
const pressure_rating_unit = ref('psi');
const shear_ram_dist_from_bottom_unit = ref('ft');
const height_unit = ref('ft');
const height_pdf = ref(1500);
const loading = ref(false);
const isDesc = ref(true);
const isLoadingData = ref(false);
const componentList = ref([]);
const weightUnits = ref([
    { name: 'Selected Convertion', value: 'selected_convertion' },
    { name: 'Pound', value: 'lbs' },
    { name: 'Kilogram', value: 'kg' },
]);

const heightUnits = ref([
    { name: 'Selected Convertion', value: 'selected_convertion' },
    { name: 'Inch', value: 'inch' },
    { name: 'Foot', value: 'ft' },
    { name: 'Meter', value: 'm' },
]);

const pressureRatingUnits = ref([
    { name: 'Selected Convertion', value: 'selected_convertion' },
    { name: 'Psi', value: 'psi' },
    { name: 'Bar', value: 'bar' },
]);

const shearRamDistFromBottomUnits = ref([
    { name: 'Selected Convertion', value: 'selected_convertion' },
    { name: 'Inch', value: 'inch' },
    { name: 'Foot', value: 'ft' },
    { name: 'Meter', value: 'm' },
]);

const selected_weightUnit = ref(weightUnits.value[0]);
const selected_heightUnit = ref(heightUnits.value[0]);
const selected_pressureRatingUnit = ref(pressureRatingUnits.value[0]);
const selected_shearRamDistFromBottomUnit = ref(shearRamDistFromBottomUnits.value[0]);

/* ------------------------------ FILTER & SORT ------------------------------ */
const search = ref('');
const selectedStatusFilter = ref({ name: 'Active', value: 'active' });
const selectedSortByFilter = ref({ name: 'Created Date', value: 'created_at' });
const selectedPageSizeFilter = ref({ name: '10', value: 10 });

const isTemplateModalOpen = ref(false);
const titleModal = ref('Create New Template');
const titleModalButton = ref('Create');
const isCreateNewItem = ref(true);
const templateForm = ref({
    name: '',
    client: '',
    field: '',
    well_name_number: '',
    min_restriction: '',
    kop: '',
    category: '',
    bhp: '',
    bhst: '',
    so: '',
    supplier: '',
    date_drawn: '',
    drawn_by: '',
});

const resetForm = () => {
    templateForm.value = {
        name: '',
        client: '',
        field: '',
        well_name_number: '',
        min_restriction: '',
        kop: '',
        category: '',
        bhp: '',
        bhst: '',
        so: '',
        supplier: '',
        date_drawn: '',
        drawn_by: '',
    };
};

/* ------------------------------ API CALLS ---------------------------------- */
const fetchAllWellstackTypes = async () => {
    loading.value = true;
    try {
        const response = await axios.get(baseUrl + '/api/wellstack-types-search', {
            params: { search: queryTypes.value }
        });
        types.value = response.data;
        items.value = [];
        selectedItem.value = null;
        console.log(types.value);
    } catch (error) {
        console.error('Error fetching types:', error);
    } finally {
        loading.value = false;
    }
};

const fetchAllTemplates = async () => {
    loading.value = true;
    isLoadingData.value = true;
    try {
        const response = await axios.get(baseUrl + '/api/wellstack-reporting-histories', {
            params: {
                search: search.value,
                status: selectedStatusFilter.value?.value,
                sortBy: selectedSortByFilter.value?.value,
                pageSize: selectedPageSizeFilter.value?.value,
                direction: isDesc.value ? 'desc' : 'asc',
            }
        });
        listTemplates.value = response.data.data;
    } catch (error) {
        console.error('Error fetching data:', error);
    } finally {
        loading.value = false;
        isLoadingData.value = false;
    }
};

const fetchAllWellstackItems = async (typeId) => {
    loading.value = true;
    try {
        const response = await axios.get(baseUrl + '/api/wellstack-items-search', {
            params: { wellstack_type_id: typeId }
        });
        items.value = response.data;
        items.value.forEach(item => {
            item.image_url = item.image_url || 'default-image-url.jpg';
        });
    } catch (error) {
        console.error('Error fetching items:', error);
    } finally {
        loading.value = false;
    }
};

// Unit conversion functions
const parseValueAndUnit = (str) => {
    if (!str || str === 'N/A' || str === '') return { value: 0, unit: 'ft' };

    // Handle string format like "5.5 ft" or just number
    if (typeof str === 'string' && str.includes(' ')) {
        const parts = str.trim().split(' ');
        return {
            value: parseFloat(parts[0]) || 0,
            unit: parts[1] || 'ft'
        };
    }

    // Handle just number
    return {
        value: parseFloat(str) || 0,
        unit: 'ft' // default unit
    };
};

// Length conversion (inch, ft, m)
const convertLengthUnit = (value, fromUnit, toUnit) => {
    if (fromUnit === toUnit) return value;

    // Convert to meters as base unit
    let valueInMeters;
    switch (fromUnit.toLowerCase()) {
        case 'inch':
            valueInMeters = value * 0.0254;
            break;
        case 'ft':
            valueInMeters = value * 0.3048;
            break;
        case 'm':
            valueInMeters = value;
            break;
        default:
            valueInMeters = value;
    }

    // Convert from meters to target unit
    switch (toUnit.toLowerCase()) {
        case 'inch':
            return parseFloat((valueInMeters / 0.0254).toFixed(2));
        case 'ft':
            return parseFloat((valueInMeters / 0.3048).toFixed(2));
        case 'm':
            return parseFloat(valueInMeters.toFixed(2));
        default:
            return value;
    }
};

// Weight conversion (lbs, kg)
const convertWeightUnit = (value, fromUnit, toUnit) => {
    if (fromUnit === toUnit) return value;

    // Convert to kg as base unit
    let valueInKg;
    switch (fromUnit.toLowerCase()) {
        case 'lbs':
            valueInKg = value * 0.453592;
            break;
        case 'kg':
            valueInKg = value;
            break;
        default:
            valueInKg = value;
    }

    // Convert from kg to target unit
    switch (toUnit.toLowerCase()) {
        case 'lbs':
            return parseFloat((valueInKg / 0.453592).toFixed(2));
        case 'kg':
            return parseFloat(valueInKg.toFixed(2));
        default:
            return value;
    }
};

// Pressure conversion (psi, bar)
const convertPressureUnit = (value, fromUnit, toUnit) => {
    if (fromUnit === toUnit) return value;

    // Convert to bar as base unit
    let valueInBar;
    switch (fromUnit.toLowerCase()) {
        case 'psi':
            valueInBar = value * 0.0689476;
            break;
        case 'bar':
            valueInBar = value;
            break;
        default:
            valueInBar = value;
    }

    // Convert from bar to target unit
    switch (toUnit.toLowerCase()) {
        case 'psi':
            return parseFloat((valueInBar / 0.0689476).toFixed(2));
        case 'bar':
            return parseFloat(valueInBar.toFixed(2));
        default:
            return value;
    }
};

const formatValue = (value, unit) => {
    if (value === 0) return 'N/A';
    return `${value} ${unit}`;
};

const getConvertedComponent = (component) => {
    const heightData = parseValueAndUnit(component.height);
    const weightData = parseValueAndUnit(component.weight);
    const pressureData = parseValueAndUnit(component.pressure_rating);
    const shearRamData = parseValueAndUnit(component.shear_ram_dist_from_bottom);

    const convertedHeight = selected_heightUnit.value.value === 'selected_convertion'
        ? component.height + ' ' + (component.height_unit || heightData.unit)
        : formatValue(
            convertLengthUnit(heightData.value, component.height_unit || heightData.unit, selected_heightUnit.value.value),
            selected_heightUnit.value.value
        );

    const convertedWeight = selected_weightUnit.value.value === 'selected_convertion'
        ? component.weight + ' ' + (component.weight_unit || weightData.unit)
        : formatValue(
            convertWeightUnit(weightData.value, component.weight_unit || weightData.unit, selected_weightUnit.value.value),
            selected_weightUnit.value.value
        );

    const convertedPressure = selected_pressureRatingUnit.value.value === 'selected_convertion'
        ? component.pressure_rating + ' ' + (component.pressure_rating_unit || pressureData.unit)
        : formatValue(
            convertPressureUnit(pressureData.value, component.pressure_rating_unit || pressureData.unit, selected_pressureRatingUnit.value.value),
            selected_pressureRatingUnit.value.value
        );

    const convertedShearRam = selected_shearRamDistFromBottomUnit.value.value === 'selected_convertion'
        ? component.shear_ram_dist_from_bottom + ' ' + (component.shear_ram_dist_from_bottom_unit || shearRamData.unit)
        : formatValue(
            convertLengthUnit(shearRamData.value, component.shear_ram_dist_from_bottom_unit || shearRamData.unit, selected_shearRamDistFromBottomUnit.value.value),
            selected_shearRamDistFromBottomUnit.value.value
        );

    return {
        ...component,
        displayHeight: convertedHeight,
        displayWeight: convertedWeight,
        displayPressureRating: convertedPressure,
        displayShearRamDistFromBottom: convertedShearRam
    };
};

const fetchAllWellstackReportingDetails = async (templateId) => {
    loading.value = true;
    try {
        const response = await axios.get(`${baseUrl}/api/wellstack-reporting-history-details/${templateId}`);
        componentList.value = response.data.map((detail, index) => ({
            component_id: detail.id,
            image: baseUrl + detail.image_url || 'default-image-url.jpg',
            description: detail.item_name,
            serial_number: detail.serial_number || '',
            height: parseFloat(detail.height) || 0,
            height_unit: detail.height_unit || 'ft',
            weight: parseFloat(detail.weight) || 0,
            weight_unit: detail.weight_unit || 'lbs',
            pressure_rating: parseFloat(detail.pressure_rating) || 0,
            pressure_rating_unit: detail.pressure_rating_unit || 'psi',
            shear_ram_dist_from_bottom: parseFloat(detail.shear_ram_dist_from_bottom) || 0,
            shear_ram_dist_from_bottom_unit: detail.shear_ram_dist_from_bottom_unit || 'ft',
            owner: detail.owner || '',
            isRemoving: false
        }));
    } catch (error) {
        console.error('Error fetching wellstack reporting details:', error);
    } finally {
        loading.value = false;
    }
};

/* ----------------------------- FORMAT HELPERS ------------------------------ */
const formatDate = (utcDateString) => {
    const date = new Date(utcDateString);
    const options = {
        year: 'numeric', month: 'short', day: 'numeric',
        hour: '2-digit', minute: '2-digit', hour12: false,
        timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone,
    };
    return date.toLocaleString('en-US', options).replace(',', '').replace(',', ' at');
};

const formatDateWithoutTime = (utcDateString) => {
    const date = new Date(utcDateString);
    const options = { month: 'short', day: 'numeric', year: 'numeric' };
    return date
        .toLocaleDateString('en-US', options)
        .replace(',', '');  // hapus koma
};

/* --------------------------- COMPONENT ACTIONS ----------------------------- */
const handleAddComponent = async () => {
    AddComponentLoading.value = true;
    if (!selectedType.value || !selectedItem.value) {
        alert('Please select components first');
        AddComponentLoading.value = false;
        return;
    }

    const newComponent = {
        component_id: selectedItem.value.id,
        image: baseUrl + selectedItem.value.image_url || 'default-image-url.jpg',
        description: selectedItem.value.name,
        serial_number: selectedItem.value.serial_number || '',
        height: parseFloat(selectedItem.value.height) || 0,
        height_unit: selectedItem.value.height_unit || 'ft',
        weight: parseFloat(selectedItem.value.weight) || 0,
        weight_unit: selectedItem.value.weight_unit || 'lbs',
        pressure_rating: parseFloat(selectedItem.value.pressure_rating) || 0,
        pressure_rating_unit: selectedItem.value.pressure_rating_unit || 'psi',
        shear_ram_dist_from_bottom: parseFloat(selectedItem.value.shear_ram_dist_from_bottom) || 0,
        shear_ram_dist_from_bottom_unit: selectedItem.value.shear_ram_dist_from_bottom_unit || 'ft',
        owner: selectedItem.value.owner || '',
        position: componentList.value.length + 1,
        isRemoving: false
    };

    try {
        await axios.post(baseUrl + '/api/wellstack-reporting-history-details', {
            wellstack_reporting_history_id: templateForm.value.id,
            wellstack_type_id: selectedType.value.id,
            wellstack_item_id: selectedItem.value.id,
        });
        componentList.value.push(newComponent);
    } catch (error) {
        console.error('Error saving component:', error);
    }

    selectedType.value = null;
    selectedItem.value = null;
    selectedItemDimension.value = null;
    AddComponentLoading.value = false;
};

const removeComponent = async (index, component) => {
    // Set loading state
    component.isRemoving = true;

    let data = {
        ids: [component.component_id],
    };

    try {
        await axios.delete(`${baseUrl}/api/wellstack-reporting-history-details`, {
            data: data
        });

        // Add fade out animation before removing
        setTimeout(() => {
            componentList.value.splice(index, 1);
        }, 300);

    } catch (error) {
        console.error('Error removing component:', error);
        // Reset loading state on error
        component.isRemoving = false;
        useToast().error('Failed to remove component');
    }
};

const updatePositions = () => {
    // Update the original componentList with new positions from convertedComponentList
    componentList.value.forEach((component, index) => {
        component.position = index + 1;
    });
};

const handleUpdatePosition = async (event) => {
    updatePositionLoading.value = true;

    await axios.put(`${baseUrl}/api/wellstack-reporting-history-details/update-positions`, {
        components: componentList.value.map((component, index) => ({
            id: component.component_id,
            position: index + 1
        }))
    });
    useToast().success('Component positions updated successfully');
    updatePositionLoading.value = false;
};

const handleDeleteTemplate = async () => {
    if (!selectedTemplate.value) return;
    isDeleting.value = true;
    try {
        let ids = [selectedTemplate.value.id];
        await axios.delete(`${baseUrl}/api/wellstack-reporting-histories`, {
            data: { ids }
        });
        useToast().success('Template deleted successfully');
        fetchAllTemplates();
        closeModal();
    } catch (error) {
        console.error('Error deleting template:', error);
        useToast().error('Failed to delete template');
    } finally {
        isDeleteModalOpen.value = false;
        isDeleting.value = false;
    }
};

const handleExportPDF = () => {
    if (selected_weightUnit.value.value === 'selected_convertion' ||
        selected_heightUnit.value.value === 'selected_convertion' ||
        selected_pressureRatingUnit.value.value === 'selected_convertion' ||
        selected_shearRamDistFromBottomUnit.value.value === 'selected_convertion') {
        useToast().error('Please select a valid unit for conversion');
        return;
    }
    const url = baseUrl + '/backend/wellstack-reporting-histories/export-pdf/' + templateForm.value.id + '?height_pdf=' + height_pdf.value +
        '&height_unit=' + selected_heightUnit.value.value +
        '&weight_unit=' + selected_weightUnit.value.value +
        '&pressure_rating_unit=' + selected_pressureRatingUnit.value.value +
        '&shear_ram_dist_from_bottom_unit=' + selected_shearRamDistFromBottomUnit.value.value;
    window.open(url, '_blank');
}

/* ----------------------------- MODAL HANDLERS ------------------------------ */
function openModal(section = '', selectedItem = null) {
    isTemplateModalOpen.value = true;
    titleModal.value = section === 'create' ? 'Create New Template' : 'Edit Template';
    titleModalButton.value = section === 'create' ? 'Create' : 'Update';
    isCreateNewItem.value = section === 'create';

    if (selectedItem) {
        templateForm.value = { ...selectedItem };
        templateForm.value.date_drawn = templateForm.value.date_drawn
            ? new Date(templateForm.value.date_drawn).toISOString().split('T')[0]
            : '';
    } else {
        resetForm();
    }
}

async function openReportModal(section = '', selectedItem = null) {
    titleModal.value = section === 'create' ? 'Create New Report' : 'Edit Report';
    titleModalButton.value = section === 'create' ? 'Create' : 'Update';
    isCreateNewItem.value = section === 'create';
    isReportingModalOpen.value = true;
    componentList.value = [];

    if (selectedItem) {
        componentListLoading.value = true;
        templateForm.value = { ...selectedItem };
        await fetchAllWellstackTypes();
        await fetchAllWellstackReportingDetails(templateForm.value.id);
        componentListLoading.value = false;
    } else {
        resetForm();
    }
}

const confirmDeleteModal = (template) => {
    selectedTemplate.value = template
    isDeleteModalOpen.value = true
}

function closeModal() {
    isTemplateModalOpen.value = false;
    isReportingModalOpen.value = false;
    isDeleteModalOpen.value = false;
    selectedTemplate.value = null;
    resetForm();
}

const saveTemplate = async () => {
    loading.value = true;
    try {
        if (isCreateNewItem.value) {
            await axios.post(`${baseUrl}/api/wellstack-reporting-histories`, templateForm.value);
            useToast().success('Template created successfully!');
        } else {
            await axios.put(`${baseUrl}/api/wellstack-reporting-histories/${templateForm.value.id}`, templateForm.value);
            useToast().success('Template updated successfully!');
        }
        fetchAllTemplates();
        closeModal();
    } catch (error) {
        console.error('Error saving template:', error);
        useToast().error('Failed to save template.');
    } finally {
        loading.value = false;
    }
}

/* ------------------------------- WATCHERS ---------------------------------- */
watch(queryTypes, (newQuery) => {
    filteredTypes.value = !newQuery
        ? types.value
        : types.value.filter(cat => cat.name.toLowerCase().includes(newQuery.toLowerCase()));
}, { immediate: true });

watch(selectedType, (newType) => {
    selectedItem.value = null;
    selectedItemDimension.value = null;
    items.value = [];
    itemDimensions.value = [];
    if (newType) fetchAllWellstackItems(newType.id);
}, { immediate: true });

watch(queryItems, (newQuery) => {
    filteredItems.value = !newQuery
        ? items.value
        : items.value.filter(item => item.name.toLowerCase().includes(newQuery.toLowerCase()));
}, { immediate: true });


watch(queryItemDimensions, (newQuery) => {
    filteredItemDimensions.value = !newQuery
        ? itemDimensions.value
        : itemDimensions.value.filter(dim => dimensionLabel(dim).toLowerCase().includes(newQuery.toLowerCase()));
}, { immediate: true });

watch([search, selectedStatusFilter, selectedSortByFilter, selectedPageSizeFilter, isDesc], () => {
    fetchAllTemplates();
}, { deep: true });

onMounted(() => {
    fetchAllTemplates();
});

</script>
