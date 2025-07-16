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
                            <form @submit.prevent="saveTemplate" class="flex flex-col md:flex-row gap-6">
                                <!-- Form Details - Full width on mobile, right column on desktop -->
                                <div class="w-full flex flex-col justify-between">
                                    <div>
                                        <!-- Name -->
                                        <div class="mb-4">
                                            <label for="name"
                                                class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                Name
                                            </label>
                                            <input type="text" id="name" v-model="templateToolstringForm.name"
                                                class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                required>
                                        </div>
                                        <!-- Title -->
                                        <div class="mb-4">
                                            <label for="name"
                                                class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                Title
                                            </label>
                                            <input type="text" id="name" v-model="templateToolstringForm.title"
                                                class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                required>
                                        </div>
                                        <!-- Client -->
                                        <div class="mb-4">
                                            <label for="name"
                                                class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                Client
                                            </label>
                                            <input type="text" id="name" v-model="templateToolstringForm.client"
                                                class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                required>
                                        </div>
                                        <!-- Well -->
                                        <div class="mb-4">
                                            <label for="name"
                                                class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                Well
                                            </label>
                                            <input type="text" id="name" v-model="templateToolstringForm.well"
                                                class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                required>
                                        </div>
                                        <!-- Title -->
                                        <div class="mb-4">
                                            <label for="date"
                                                class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                Well Date
                                            </label>
                                            <input type="date" id="date" v-model="templateToolstringForm.date"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-600 dark:text-white"
                                                required>
                                        </div>
                                    </div>
                                    <div>
                                        <!-- Action Buttons -->
                                        <div class="mt-6 flex justify-center space-x-3">
                                            <button type="button"
                                                class="inline-flex justify-center cursor-pointer rounded-md border dark:text-white/75 border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:hover:bg-gray-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
                                                @click="closeModal">
                                                Cancel
                                            </button>
                                            <button type="submit" :disabled="loading"
                                                class="inline-flex justify-center cursor-pointer rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                                                <span v-if="!loading">{{ titleModalButton }}</span>
                                                <span v-else class="flex items-center">
                                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                            stroke="currentColor" stroke-width="4">
                                                        </circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>
                                                    Processing...
                                                </span>
                                            </button>
                                        </div>
                                    </div>
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
                                        <div class="flex gap-5">
                                            <!-- Name -->
                                            <div class="mb-4">
                                                <label for="name"
                                                    class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                    Name
                                                </label>
                                                <input type="text" id="name" v-model="templateToolstringForm.name"
                                                    class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                    disabled>
                                            </div>
                                            <!-- Title -->
                                            <div class="mb-4">
                                                <label for="name"
                                                    class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                    Title
                                                </label>
                                                <input type="text" id="name" v-model="templateToolstringForm.title"
                                                    class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                    disabled>
                                            </div>
                                            <!-- Client -->
                                            <div class="mb-4">
                                                <label for="name"
                                                    class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                    Client
                                                </label>
                                                <input type="text" id="name" v-model="templateToolstringForm.client"
                                                    class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                    disabled>
                                            </div>
                                            <!-- Well -->
                                            <div class="mb-4">
                                                <label for="name"
                                                    class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                    Well
                                                </label>
                                                <input type="text" id="name" v-model="templateToolstringForm.well"
                                                    class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                    disabled>
                                            </div>
                                            <!-- Title -->
                                            <div class="mb-4">
                                                <label for="date"
                                                    class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                    Well Date
                                                </label>
                                                <input type="date" id="date" v-model="templateToolstringForm.date"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-600 dark:text-white"
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
                                                <ClipboardDocumentIcon class="w-5 h-5" />
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
                                        <div class="w-full">
                                            <!-- Component Dimension -->
                                            <div class="w-full flex flex-col">
                                                <label
                                                    class="text-sm font-medium text-gray-700 dark:text-white mb-1">Component
                                                    Dimension</label>
                                                <Combobox v-model="selectedItemDimension">
                                                    <div class="relative mt-1 w-full">
                                                        <div
                                                            class="relative w-full cursor-default overflow-hidden rounded-lg bg-white dark:bg-slate-800/50 text-left shadow-md border border-gray-300">
                                                            <ComboboxInput
                                                                class="w-full h-11 border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 dark:text-white bg-transparent focus:outline-none"
                                                                :displayValue="dimensionLabel"
                                                                @change="queryItemDimensions = $event.target.value"
                                                                placeholder="Search Dimension Tools..." />
                                                            <ComboboxButton
                                                                class="absolute inset-y-0 right-0 flex items-center pr-2">
                                                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400"
                                                                    aria-hidden="true" />
                                                            </ComboboxButton>
                                                        </div>
                                                        <transition leave-active-class="transition duration-100 ease-in"
                                                            leave-from-class="opacity-100" leave-to-class="opacity-0">
                                                            <ComboboxOptions
                                                                class="absolute mt-1 z-30 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-slate-800 py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">

                                                                <div v-if="loading"
                                                                    class="relative cursor-default select-none px-4 py-2 text-gray-700 dark:text-gray-300">
                                                                    Loading...
                                                                </div>
                                                                <div v-else-if="itemDimensions.length === 0"
                                                                    class="relative cursor-default select-none px-4 py-2 text-gray-700 dark:text-gray-300">
                                                                    Nothing found.
                                                                </div>

                                                                <ComboboxOption v-for="dimension in itemDimensions"
                                                                    :key="dimension.id" :value="dimension"
                                                                    v-slot="{ selected, active }">
                                                                    <li :class="[
                                                                        'relative cursor-default select-none py-2 pl-10 pr-4',
                                                                        active ? 'bg-blue-100 text-blue-900 dark:bg-gray-500 dark:text-white' : 'text-gray-900 dark:text-white'
                                                                    ]">
                                                                        <span :class="[
                                                                            'block truncate',
                                                                            selected ? 'font-medium' : 'font-normal'
                                                                        ]">
                                                                            OD: {{ dimension.outer_diameter.value }} {{
                                                                                dimension.outer_diameter.unit }} - ID: {{
        dimension.inner_diameter.value }} {{
        dimension.inner_diameter.unit }} - Length: {{
        dimension.length.value }} {{
        dimension.length.unit
    }}
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Table Component -->
                            <div class="bg-white dark:bg-slate-800 my-5 rounded-xl shadow-md overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50  dark:bg-gray-800">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                No</th>
                                            <th class="px-6 py-3 dark:text-gray-300">Image</th>
                                            <th class="px-6 py-3 dark:text-gray-300">Description</th>
                                            <th class="px-6 py-3 dark:text-gray-300">OD</th>
                                            <th class="px-6 py-3 dark:text-gray-300">ID</th>
                                            <th class="px-6 py-3 dark:text-gray-300">Top Connection</th>
                                            <th class="px-6 py-3 dark:text-gray-300">Bottom Connection</th>
                                            <th class="px-6 py-3 dark:text-gray-300">Length</th>
                                            <th class="px-6 py-3 dark:text-gray-300">Actions</th>
                                        </tr>
                                    </thead>

                                    <!-- Loading Skeleton -->
                                    <tbody v-if="componentListLoading"
                                        class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                        <tr v-for="n in 3" :key="'loading-' + n">
                                            <td colspan="9" class="px-6 py-4">
                                                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse w-full">
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>

                                    <!-- Draggable Items -->
                                    <draggable v-else v-model="componentList" tag="tbody" item-key="component_id"
                                        @end="updatePositions"
                                        class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                        <template #item="{ element, index }">
                                            <tr>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                    {{ index + 1 }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <img :src="element.image" alt="Component image"
                                                        class="h-10 w-10 object-contain" />
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                    {{ element.description }}</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                    {{ element.od }}</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                    {{ element.id }}</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                    {{ element.top_connection }}</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                    {{ element.bottom_connection }}</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                    {{ element.length }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <button @click="removeComponent(index, element)"
                                                        :disabled="element.isRemoving"
                                                        class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-red-600 hover:text-red-900 hover:bg-red-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                                                        <div v-if="element.isRemoving" class="spinner mr-2"></div>
                                                        <svg v-else class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
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
                                                <td colspan="7"
                                                    class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                                    No components found
                                                </td>
                                            </tr>
                                        </template>
                                    </draggable>
                                </table>
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
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Toolstring Coiled Tubing Reporting</h1>
                        <p class="text-gray-600 dark:text-gray-400">Design and export Toolstring Coiled
                            Tubing reports with selected items and parameters. Then you can exported it to
                            pdf.</p>
                    </template>
                </div>
                <button @click="openModal('create', 'toolstring_coiled_tubing')"
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
                                    Refresh
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
                                                class="relative w-full cursor-default rounded-lg bg-white dark:bg-slate-800/50 py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-blue-300 sm:text-sm border border-gray-200 dark:border-slate-600">
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
                                                class="relative w-full cursor-default rounded-lg bg-white dark:bg-slate-800/50 py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-blue-300 sm:text-sm border border-gray-200 dark:border-slate-600">
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
                                                class="relative w-full cursor-default rounded-lg bg-white dark:bg-slate-800/50 py-2.5 pl-3 pr-8 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-blue-300 text-sm border border-gray-200 dark:border-slate-600">
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
                                                class="relative w-full cursor-default rounded-lg bg-white dark:bg-slate-800/50 py-2.5 pl-3 pr-8 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-blue-300 text-sm border border-gray-200 dark:border-slate-600">
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
                                                class="relative w-full cursor-default rounded-lg bg-white dark:bg-slate-800/50 py-2.5 pl-3 pr-8 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-blue-300 text-sm border border-gray-200 dark:border-slate-600">
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
                                        Title</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Client</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Well</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Date</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Updated At</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Created By</th>
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
                                        {{ template.title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ template.client }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ template.well }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ template.date }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ formatDate(template.updated_at) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ template.updated_by_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        <div class="flex justify-end gap-2">
                                            <button @click="openModal('update', 'toolstring_coiled_tubing', template)"
                                                class="inline-flex items-center px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-md hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors duration-150">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </button>
                                            <button @click="openReportModal('create', 'toolstring_coiled_tubing', template)"
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
/* --------------------------------- IMPORTS --------------------------------- */
import { ref, watch, onMounted } from 'vue';
import { useRoute } from 'vue-router';
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

/* ------------------------------ ROUTE & STATE ------------------------------ */
const route = useRoute();
const reportingSlug = ref(route.params.slug);

watch(() => route.params.slug, (newVal) => {
    reportingSlug.value = newVal;
}, { immediate: true });


/* ------------------------------ UI STATE FLAGS ----------------------------- */
const isLoadingData = ref(false);
const isTemplateModalOpen = ref(false);
const isReportingModalOpen = ref(false);
const loading = ref(false);
const showMobileFilters = ref(false);
const isDesc = ref(true);
const baseUrl = import.meta.env.VITE_API_URL;

/* ------------------------------ FILTER & SORT ------------------------------ */
const search = ref('');
const selectedStatusFilter = ref({ name: 'Active', value: 'active' });
const selectedSortByFilter = ref({ name: 'Created Date', value: 'created_at' });
const selectedPageSizeFilter = ref({ name: '10', value: 10 });

/* ------------------------------ FILTER & SORT ITEMS ------------------------- */
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


/* -------------------------- MODAL / FORM STATE ----------------------------- */
const titleModal = ref('');
const titleModalButton = ref('');
const isCreateNewItem = ref(true);

const templateToolstringForm = ref({
    name: '',
    title: '',
    client: '',
    well: '',
    date: ''
});

const resetForm = () => {
    templateToolstringForm.value = {
        name: '',
        title: '',
        client: '',
        well: '',
        date: ''
    };
};


/* --------------------------- DATA LIST & TABLE ----------------------------- */
const listTemplates = ref([]);

/* --------------------------- TOOLSTRING PICKERS ---------------------------- */
const types = ref([]);
const items = ref([]);
const itemDimensions = ref([]);

const filteredTypes = ref([]);
const filteredItems = ref([]);
const filteredItemDimensions = ref([]);

const selectedType = ref(null);
const selectedItem = ref(null);
const selectedItemDimension = ref(null);

const queryTypes = ref('');
const queryItems = ref('');
const queryItemDimensions = ref('');

const componentListLoading = ref(false);
const AddComponentLoading = ref(false);
const updatePositionLoading = ref(false);

const outer_diameter_unit = ref('inch');
const inner_diameter_unit = ref('inch');
const length_unit = ref('inch');
const height_pdf = ref(1500);

const isDeleteModalOpen = ref(false);
const selectedTemplate = ref(null)
const isDeleting = ref(false);
const dimensionLabel = (item) => {
    if (!item) return '';
    return `OD: ${item.outer_diameter.value} ${item.outer_diameter.unit} - ID: ${item.inner_diameter.value} ${item.inner_diameter.unit} - Length: ${item.length.value} ${item.length.unit}`;
};


/* ------------------------------ COMPONENT LIST ----------------------------- */
const componentList = ref([]);


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


/* ------------------------------ API CALLS ---------------------------------- */
const fetchAllTemplates = async () => {
    loading.value = true;
    isLoadingData.value = true;
    try {
        const response = await axios.get(baseUrl + '/api/toolstring-reporting-histories', {
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

const fetchAllToolstringTypes = async () => {
    loading.value = true;
    try {
        const response = await axios.get(baseUrl + '/api/toolstring-types-search', {
            params: { search: queryTypes.value }
        });
        types.value = response.data;
        items.value = [];
        itemDimensions.value = [];
        selectedItem.value = null;
        selectedItemDimension.value = null;
    } catch (error) {
        console.error('Error fetching types:', error);
    } finally {
        loading.value = false;
    }
};

const fetchAllToolstringItems = async (typeId) => {
    loading.value = true;
    try {
        const response = await axios.get(baseUrl + '/api/toolstring-items-search', {
            params: { toolstring_type_id: typeId }
        });
        items.value = response.data;
        items.value.forEach(item => {
            item.image_url = baseUrl + item.image_url || 'default-image-url.jpg';
        });
        selectedItemDimension.value = null;
    } catch (error) {
        console.error('Error fetching items:', error);
    } finally {
        loading.value = false;
    }
};

const fetchAllComponentDimensions = async (itemId) => {
    loading.value = true;
    try {
        const response = await axios.get(`${baseUrl}/api/toolstring-item-dimensions/${itemId}`);
        itemDimensions.value = response.data;
    } catch (error) {
        console.error('Error fetching item dimensions:', error);
    } finally {
        loading.value = false;
    }
};

const fetchAllToolstringReportingDetails = async (templateId) => {
    loading.value = true;
    try {
        const response = await axios.get(`${baseUrl}/api/toolstring-reporting-history-details/${templateId}`);
        componentList.value = response.data.map((detail, index) => ({
            component_id: detail.id,
            image: baseUrl + detail.image_url || 'default-image-url.jpg',
            description: detail.description,
            od: `${detail.dimension?.outer_diameter.value} ${detail.dimension?.outer_diameter.unit}` || 'N/A',
            id: `${detail.dimension?.inner_diameter.value} ${detail.dimension?.inner_diameter.unit}` || 'N/A',
            top_connection: detail.thread_size?.top_connection || 'N/A',
            bottom_connection: detail.thread_size?.bottom_connection || 'N/A',
            length: `${detail.dimension?.length.value} ${detail.dimension?.length.unit}` || 'N/A',
            position: index + 1
        }));

        componentList.value.forEach((component, index) => {
            console.log(component)
        });
    } catch (error) {
        console.error('Error fetching toolstring reporting details:', error);
    } finally {
        loading.value = false;
    }
};


/* -------------------------- MODAL HANDLERS --------------------------------- */
function openModal(section = '', type = '', selectedItem = null) {
    isTemplateModalOpen.value = (type === 'toolstring_coiled_tubing');
    titleModal.value = section === 'create' ? 'Create New Template' : 'Edit Template';
    titleModalButton.value = section === 'create' ? 'Create' : 'Update';
    isCreateNewItem.value = section === 'create';

    if (selectedItem) {
        templateToolstringForm.value = { ...selectedItem };
    } else {
        resetForm();
    }
}

async function openReportModal(section = '', type = '', selectedItem = null) {
    isReportingModalOpen.value = (type === 'toolstring_coiled_tubing');
    titleModal.value = section === 'create' ? 'Create New Report' : 'Edit Report';
    titleModalButton.value = section === 'create' ? 'Create' : 'Update';
    isCreateNewItem.value = section === 'create';

    if (selectedItem) {
        componentListLoading.value = true;
        templateToolstringForm.value = { ...selectedItem };
        await fetchAllToolstringTypes();
        await fetchAllToolstringReportingDetails(templateToolstringForm.value.id);
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

/* ---------------------------- SAVE HANDLER --------------------------------- */
const saveTemplate = async () => {
    try {
        loading.value = true;
        if (isCreateNewItem.value) {
            await axios.post(baseUrl + '/api/toolstring-reporting-histories', templateToolstringForm.value);
            useToast().success('Template created successfully');
        } else {
            await axios.put(`${baseUrl}/api/toolstring-reporting-histories/${templateToolstringForm.value.id}`, templateToolstringForm.value);
            useToast().success('Template updated successfully');
        }
        fetchAllTemplates();
        closeModal();
    } catch (error) {
        console.error('Error saving template:', error);
    } finally {
        loading.value = false;
    }
};


/* --------------------------- COMPONENT ACTIONS ----------------------------- */
const handleAddComponent = async () => {
    AddComponentLoading.value = true;
    if (!selectedType.value || !selectedItem.value || !selectedItemDimension.value) {
        alert('Please select all components first');
        AddComponentLoading.value = false;
        return;
    }

    const newComponent = {
        component_id: selectedItem.value.id,
        image: baseUrl + selectedItem.value.image_url || 'default-image-url.jpg',
        description: selectedItem.value.description,
        od: `${selectedItemDimension.value.outer_diameter.value} ${selectedItemDimension.value.outer_diameter.unit}`,
        id: `${selectedItemDimension.value.inner_diameter.value} ${selectedItemDimension.value.inner_diameter.unit}`,
        length: `${selectedItemDimension.value.length.value} ${selectedItemDimension.value.length.unit}`,
        position: componentList.value.length + 1
    };

    try {
        await axios.post(baseUrl + '/api/toolstring-reporting-history-details', {
            toolstring_reporting_history_id: templateToolstringForm.value.id,
            toolstring_type_id: selectedType.value.id,
            toolstring_item_id: selectedItem.value.id,
            toolstring_item_dimension_id: selectedItemDimension.value.id,
        });
    } catch (error) {
        console.error('Error saving component:', error);
    }

    componentList.value.push(newComponent);

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
        await axios.delete(`${baseUrl}/api/toolstring-reporting-history-details`, {
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

const handleUpdatePosition = async (event) => {
    updatePositionLoading.value = true;
    componentList.value.forEach((component, index) => {
        console.log(component)
    });

    await axios.put(`${baseUrl}/api/toolstring-reporting-history-details/update-positions`, {
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
        await axios.delete(`${baseUrl}/api/toolstring-reporting-histories`, {
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

/* ------------------------------- EXPORT PDF COMPONENT -------------------------- */
const handleExportPDF = () => {
    const url = baseUrl + '/backend/toolstring-reporting-histories/export-pdf/' + templateToolstringForm.value.id + '?od_unit=' + outer_diameter_unit.value + '&id_unit=' + inner_diameter_unit.value + '&length_unit=' + length_unit.value + '&height_pdf=' + height_pdf.value;
    window.open(url, '_blank');
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
    if (newType) fetchAllToolstringItems(newType.id);
}, { immediate: true });

watch(queryItems, (newQuery) => {
    filteredItems.value = !newQuery
        ? items.value
        : items.value.filter(item => item.name.toLowerCase().includes(newQuery.toLowerCase()));
}, { immediate: true });

watch(selectedItem, (newItem) => {
    selectedItemDimension.value = null;
    itemDimensions.value = [];
    if (newItem) fetchAllComponentDimensions(newItem.id);
}, { immediate: true });

watch(queryItemDimensions, (newQuery) => {
    filteredItemDimensions.value = !newQuery
        ? itemDimensions.value
        : itemDimensions.value.filter(dim => dimensionLabel(dim).toLowerCase().includes(newQuery.toLowerCase()));
}, { immediate: true });

watch([search, selectedStatusFilter, selectedSortByFilter, selectedPageSizeFilter, isDesc], () => {
    fetchAllTemplates();
}, { deep: true });


/* ------------------------------- LIFECYCLE ---------------------------------- */
onMounted(() => {
    fetchAllTemplates();
    fetchAllToolstringTypes();
});
</script>

<style>
@keyframes shimmer {
    0% {
        background-position: -200% 0;
    }

    100% {
        background-position: 200% 0;
    }
}

.animate-shimmer {
    animation: shimmer 2s infinite linear;
}
</style>