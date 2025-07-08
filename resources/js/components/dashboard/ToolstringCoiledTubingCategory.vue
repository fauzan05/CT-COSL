<template>
    <head>
        <Title>Toolstring Coiled Tubing - {{ currentCategory?.name }}</Title>
    </head>
    <!-- modal create/update item -->
    <TransitionRoot appear :show="isItemModalOpen" as="template">
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
                            class="relative w-full max-w-6xl transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800/80 p-6 text-left align-middle shadow-xl transition-all">
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
                            <form @submit.prevent="saveItem" class="flex flex-col md:flex-row gap-6">
                                <!-- Upload Section - Full width on mobile, left column on desktop -->
                                <div class="w-full md:w-1/2 md:border-r md:border-gray-300 dark:border-gray-100 md:pr-6">
                                    <div class="h-[300px] md:h-[400px] border-2 border-dashed border-gray-300 dark:border-gray-100 rounded-lg flex flex-col items-center justify-center p-6"
                                        @drop.prevent="handleDrop" @dragover.prevent="dragover = true"
                                        @dragleave.prevent="dragover = false"
                                        :class="{ 'border-blue-500 bg-blue-50': dragover }">
                                        <div v-if="itemImage" class="w-full h-full relative">
                                            <!-- Gambar -->
                                            <img :src="itemImage" alt="Category"
                                                class="w-full h-full rounded-lg object-contain" />
                                            <!-- Overlay tombol "Change Image" -->
                                            <div class="absolute bottom-4 left-1/2 -translate-x-1/2">
                                                <label
                                                    class="cursor-pointer inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500 text-sm shadow">
                                                    Change Image
                                                    <input type="file" class="sr-only" @change="handleFileSelect"
                                                        accept="image/*">
                                                </label>
                                            </div>
                                        </div>
                                        <div v-else class="text-center">
                                            <div class="mb-4">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                                    fill="none" viewBox="0 0 48 48">
                                                    <path
                                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <div class="flex text-sm text-gray-600 justify-center">
                                                <label
                                                    class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500">
                                                    <span>Upload a file</span>
                                                    <input type="file" class="sr-only" @change="handleFileSelect"
                                                        accept="image/*">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-2">
                                                PNG, JPG, GIF up to 5MB
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Form Details - Full width on mobile, right column on desktop -->
                                <div class="w-full md:w-1/2 flex flex-col justify-between">
                                    <div>
                                        <!-- Name -->
                                        <div class="mb-4">
                                            <label for="name"
                                                class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                Name
                                            </label>
                                            <input type="text" id="name" v-model="itemForm.name"
                                                class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                required>
                                        </div>
                                        <!-- Description -->
                                        <div class="mb-4">
                                            <label for="description"
                                                class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                Description
                                            </label>
                                            <textarea id="description" v-model="itemForm.description" rows="4"
                                                class="w-full px-3 py-2 dark:text-white border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                required></textarea>
                                        </div>
                                        <!-- Dynamic Dimensions Sets with Add/Remove functionality -->
                                        <div class="mb-6">
                                            <div class="flex items-center justify-between mb-4">
                                                <h3 class="text-sm font-medium text-gray-700 dark:text-white">Dimensions
                                                </h3>
                                                <button @click="addDimensionSet" type="button"
                                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                                    <PlusIcon class="h-4 w-4 mr-1" />
                                                    Add Dimension Set
                                                </button>
                                            </div>

                                            <!-- Dynamic Dimension Sets List -->
                                            <div class="space-y-6">
                                                <div v-for="(dimensionSet, setIndex) in itemForm.dimensionSets"
                                                    :key="dimensionSet.id"
                                                    class="relative p-4 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                                                    <!-- Header with Set Number and Remove Button -->
                                                    <div class="flex items-center justify-between mb-4">
                                                        <h4 class="text-sm font-semibold text-gray-700 dark:text-white">
                                                            Dimension Set {{ setIndex + 1 }}
                                                        </h4>
                                                        <button v-if="itemForm.dimensionSets.length > 1"
                                                            @click="removeDimensionSet(setIndex)" type="button"
                                                            class="text-gray-400 hover:text-red-500 transition-colors duration-200"
                                                            :title="'Remove dimension set'">
                                                            <XMarkIcon class="h-5 w-5" />
                                                        </button>
                                                    </div>

                                                    <!-- Outer Diameter -->
                                                    <div class="mb-4">
                                                        <label :for="`outer_diameter_${setIndex}`"
                                                            class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                            Outer Diameter
                                                        </label>
                                                        <div class="flex space-x-2">
                                                            <input :id="`outer_diameter_${setIndex}`" type="text"
                                                                :value="dimensionSet.outer_diameter.value"
                                                                class="flex-1 px-3 py-2 dark:text-white border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                                @input="handleDecimalInput($event, setIndex, 'outer_diameter')"
                                                                placeholder="Enter outer diameter" required>
                                                            <Listbox v-model="dimensionSet.outer_diameter.unit" as="div"
                                                                class="relative">
                                                                <ListboxButton
                                                                    class="relative w-24 cursor-pointer rounded-md bg-white dark:bg-gray-700 py-2 pl-3 pr-10 text-left border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                                                    <span class="block truncate dark:text-white">{{
                                                                        dimensionSet.outer_diameter.unit }}</span>
                                                                    <span
                                                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400"
                                                                            aria-hidden="true" />
                                                                    </span>
                                                                </ListboxButton>
                                                                <transition
                                                                    leave-active-class="transition duration-100 ease-in"
                                                                    leave-from-class="opacity-100"
                                                                    leave-to-class="opacity-0">
                                                                    <ListboxOptions
                                                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-gray-700 py-1 text-base shadow-lg focus:outline-none sm:text-sm">
                                                                        <ListboxOption v-for="unit in units" :key="unit"
                                                                            :value="unit" v-slot="{ active, selected }">
                                                                            <li :class="[
                                                                                active ? 'bg-blue-600 text-white' : 'text-gray-900 dark:text-white',
                                                                                'relative cursor-pointer select-none py-2 pl-3 pr-9'
                                                                            ]">
                                                                                <span
                                                                                    :class="[selected ? 'font-semibold' : 'font-normal', 'block truncate']">
                                                                                    {{ unit }}
                                                                                </span>
                                                                                <span v-if="selected" :class="[
                                                                                    active ? 'text-white' : 'text-blue-600',
                                                                                    'absolute inset-y-0 right-0 flex items-center pr-4'
                                                                                ]">
                                                                                    <CheckIcon class="h-5 w-5"
                                                                                        aria-hidden="true" />
                                                                                </span>
                                                                            </li>
                                                                        </ListboxOption>
                                                                    </ListboxOptions>
                                                                </transition>
                                                            </Listbox>
                                                        </div>
                                                    </div>

                                                    <!-- Inner Diameter -->
                                                    <div class="mb-4">
                                                        <label :for="`inner_diameter_${setIndex}`"
                                                            class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                            Inner Diameter
                                                        </label>
                                                        <div class="flex space-x-2">
                                                            <input :id="`inner_diameter_${setIndex}`" type="text"
                                                                :value="dimensionSet.inner_diameter.value"
                                                                class="flex-1 px-3 py-2 dark:text-white border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                                @input="handleDecimalInput($event, setIndex, 'inner_diameter')"
                                                                placeholder="Enter inner diameter" required>
                                                            <Listbox v-model="dimensionSet.inner_diameter.unit" as="div"
                                                                class="relative">
                                                                <ListboxButton
                                                                    class="relative w-24 cursor-pointer rounded-md bg-white dark:bg-gray-700 py-2 pl-3 pr-10 text-left border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                                                    <span class="block truncate dark:text-white">{{
                                                                        dimensionSet.inner_diameter.unit }}</span>
                                                                    <span
                                                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400"
                                                                            aria-hidden="true" />
                                                                    </span>
                                                                </ListboxButton>
                                                                <transition
                                                                    leave-active-class="transition duration-100 ease-in"
                                                                    leave-from-class="opacity-100"
                                                                    leave-to-class="opacity-0">
                                                                    <ListboxOptions
                                                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-gray-700 py-1 text-base shadow-lg focus:outline-none sm:text-sm">
                                                                        <ListboxOption v-for="unit in units" :key="unit"
                                                                            :value="unit" v-slot="{ active, selected }">
                                                                            <li :class="[
                                                                                active ? 'bg-blue-600 text-white' : 'text-gray-900 dark:text-white',
                                                                                'relative cursor-pointer select-none py-2 pl-3 pr-9'
                                                                            ]">
                                                                                <span
                                                                                    :class="[selected ? 'font-semibold' : 'font-normal', 'block truncate']">
                                                                                    {{ unit }}
                                                                                </span>
                                                                                <span v-if="selected" :class="[
                                                                                    active ? 'text-white' : 'text-blue-600',
                                                                                    'absolute inset-y-0 right-0 flex items-center pr-4'
                                                                                ]">
                                                                                    <CheckIcon class="h-5 w-5"
                                                                                        aria-hidden="true" />
                                                                                </span>
                                                                            </li>
                                                                        </ListboxOption>
                                                                    </ListboxOptions>
                                                                </transition>
                                                            </Listbox>
                                                        </div>
                                                    </div>

                                                    <!-- Length -->
                                                    <div class="mb-4">
                                                        <label :for="`length_${setIndex}`"
                                                            class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                            Length
                                                        </label>
                                                        <div class="flex space-x-2">
                                                            <input :id="`length_${setIndex}`" type="text"
                                                                :value="dimensionSet.length.value"
                                                                class="flex-1 px-3 py-2 dark:text-white border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                                @input="handleDecimalInput($event, setIndex, 'length')"
                                                                placeholder="Enter length">
                                                            <Listbox v-model="dimensionSet.length.unit" as="div"
                                                                class="relative">
                                                                <ListboxButton
                                                                    class="relative w-24 cursor-pointer rounded-md bg-white dark:bg-gray-700 py-2 pl-3 pr-10 text-left border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                                                    <span class="block truncate dark:text-white">{{
                                                                        dimensionSet.length.unit }}</span>
                                                                    <span
                                                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400"
                                                                            aria-hidden="true" />
                                                                    </span>
                                                                </ListboxButton>
                                                                <transition
                                                                    leave-active-class="transition duration-100 ease-in"
                                                                    leave-from-class="opacity-100"
                                                                    leave-to-class="opacity-0">
                                                                    <ListboxOptions
                                                                        class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-gray-700 py-1 text-base shadow-lg focus:outline-none sm:text-sm">
                                                                        <ListboxOption v-for="unit in units" :key="unit"
                                                                            :value="unit" v-slot="{ active, selected }">
                                                                            <li :class="[
                                                                                active ? 'bg-blue-600 text-white' : 'text-gray-900 dark:text-white',
                                                                                'relative cursor-pointer select-none py-2 pl-3 pr-9'
                                                                            ]">
                                                                                <span
                                                                                    :class="[selected ? 'font-semibold' : 'font-normal', 'block truncate']">
                                                                                    {{ unit }}
                                                                                </span>
                                                                                <span v-if="selected" :class="[
                                                                                    active ? 'text-white' : 'text-blue-600',
                                                                                    'absolute inset-y-0 right-0 flex items-center pr-4'
                                                                                ]">
                                                                                    <CheckIcon class="h-5 w-5"
                                                                                        aria-hidden="true" />
                                                                                </span>
                                                                            </li>
                                                                        </ListboxOption>
                                                                    </ListboxOptions>
                                                                </transition>
                                                            </Listbox>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Show message when no dimension sets -->
                                            <div v-if="itemForm.dimensionSets.length === 0"
                                                class="text-center py-8 text-gray-500 dark:text-gray-400">
                                                <p>No dimension sets added yet. Click "Add Dimension Set" to get started.
                                                </p>
                                            </div>
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
    <!-- modal Delete Confirmation Modal -->
    <TransitionRoot appear :show="isDeleteItemModalOpen" as="template">
        <Dialog as="div" @close="closeModal" class="relative z-50">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
                leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/70 backdrop-blur-md" />
            </TransitionChild>
            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95">
                        <DialogPanel
                            class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 shadow-2xl transition-all border border-gray-200 dark:border-gray-700">

                            <!-- Header Section -->
                            <div class="px-8 pt-8 pb-6">
                                <!-- Close Button -->
                                <button @click="closeModal"
                                    class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors duration-200 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                                    aria-label="Close modal">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                <!-- Warning Icon -->
                                <div
                                    class="mx-auto flex items-center justify-center w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 mb-4">
                                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                </div>

                                <!-- Title -->
                                <DialogTitle as="h3" class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                    Delete {{ itemsToDelete.length > 1 ? 'Items' : 'Item' }}
                                </DialogTitle>

                                <!-- Subtitle -->
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                                    This action cannot be undone. The following {{ itemsToDelete.length > 1 ? 'items' :
                                        'item' }} will be permanently deleted.
                                </p>
                            </div>

                            <!-- Items List Section -->
                            <div class="px-8 pb-2">
                                <div
                                    class="max-h-60 overflow-y-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                        <div v-for="(item, index) in itemsToDelete" :key="index"
                                            class="flex items-center space-x-3 p-4 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-200">

                                            <!-- Item Icon/Image -->
                                            <div class="flex-shrink-0">
                                                <div v-if="item.image"
                                                    class="w-12 h-12 rounded-lg overflow-hidden bg-gray-200 dark:bg-gray-700">
                                                    <img :src="item.image_url" :alt="item.name"
                                                        class="w-full h-full object-contain">
                                                </div>
                                                <div v-else
                                                    class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- Item Details -->
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                    {{ item.name }}
                                                </h4>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                    {{ item.category || 'No category' }}
                                                </p>
                                                <div v-if="item.dimensions && item.dimensions.length > 0" class="mt-1">
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                                        {{ item.dimensions.length }} dimension{{ item.dimensions.length > 1
                                                            ? 's' : '' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Status Indicator -->
                                            <div class="flex-shrink-0">
                                                <div class="w-3 h-3 rounded-full bg-red-500 animate-pulse"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Summary Info -->
                                <div
                                    class="mt-4 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div class="text-sm text-red-800 dark:text-red-200">
                                            <span class="font-semibold">{{ itemsToDelete.length }}</span> item{{
                                                itemsToDelete.length > 1 ? 's' : '' }} will be permanently deleted
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons Section -->
                            <div
                                class="px-8 py-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex flex-col sm:flex-row-reverse gap-3">
                                    <!-- Delete Button -->
                                    <button @click="confirmDelete" :disabled="loading"
                                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 text-sm font-semibold rounded-xl text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]">
                                        <span v-if="!loading" class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete {{ itemsToDelete.length > 1 ? 'Items' : 'Item' }}
                                        </span>
                                        <span v-else class="flex items-center">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
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

                                    <!-- Cancel Button -->
                                    <button @click="closeModal" :disabled="loading"
                                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 text-sm font-semibold rounded-xl text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
    <div class="p-6 bg-gray-50 min-h-screen dark:bg-slate-900/50 dark:text-gray-100 rounded-xl">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ currentCategory?.name }}</h1>
                <p class="text-gray-600 dark:text-gray-400">Manage your items and organize your inventory</p>
            </div>
            <button @click="openModal(null)"
                class="bg-blue-600 cursor-pointer hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Items</span>
            </button>
        </div>
        <!-- Items Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            <!-- Loading State -->
            <template v-if="isLoadingData">
                <div v-for="n in 8" :key="n" class="animate-pulse">
                    <div
                        class="bg-white dark:bg-slate-800/70 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                        <!-- Image Skeleton -->
                        <div class="relative">
                            <div
                                class="w-full h-48 bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200 dark:from-slate-700 dark:via-slate-600 dark:to-slate-700 animate-shimmer bg-[length:400%_100%]">
                            </div>
                            <!-- Status Badge Skeleton -->
                            <div class="absolute top-3 right-3">
                                <div class="w-16 h-6 bg-gray-200 dark:bg-slate-600 rounded-full"></div>
                            </div>
                        </div>

                        <!-- Content Skeleton -->
                        <div class="p-5">
                            <!-- Title Skeleton -->
                            <div class="w-3/4 h-6 bg-gray-200 dark:bg-slate-600 rounded-lg mb-2"></div>

                            <!-- Description Skeleton -->
                            <div class="space-y-2 mb-4">
                                <div class="w-full h-4 bg-gray-200 dark:bg-slate-600 rounded"></div>
                                <div class="w-2/3 h-4 bg-gray-200 dark:bg-slate-600 rounded"></div>
                            </div>

                            <!-- Updated Info Skeleton -->
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-gray-200 dark:bg-slate-600 rounded-full"></div>
                                    <div class="w-24 h-3 bg-gray-200 dark:bg-slate-600 rounded"></div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-gray-200 dark:bg-slate-600 rounded-full"></div>
                                    <div class="w-20 h-3 bg-gray-200 dark:bg-slate-600 rounded"></div>
                                </div>
                            </div>

                            <!-- Buttons Skeleton -->
                            <div class="flex items-center space-x-2">
                                <div class="flex-1 h-10 bg-gray-200 dark:bg-slate-600 rounded-xl"></div>
                                <div class="w-10 h-10 bg-gray-200 dark:bg-slate-600 rounded-xl"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template v-else-if="items.data && items.data.length">
                <div v-for="item in items.data" :key="item.id"
                    class="group bg-white dark:bg-slate-800/70 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden hover:shadow-xl hover:shadow-blue-500/10 hover:-translate-y-1 transition-all duration-300">

                    <!-- Image Section -->
                    <div class="relative overflow-hidden">
                        <img :src="baseUrl + item.image_url || '/placeholder-item.jpg'" :alt="item.name"
                            class="w-full h-48 object-contain group-hover:scale-105 transition-transform duration-300">

                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            <span
                                :class="`px-3 py-1 rounded-full text-xs font-medium backdrop-blur-sm ${item.status === 'active' ? 'bg-emerald-100/80 text-emerald-700 border border-emerald-200' : 'bg-red-100/80 text-red-700 border border-red-200'}`">
                                {{ item.status }}
                            </span>
                        </div>

                        <!-- Gradient Overlay -->
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="p-5">
                        <!-- Title -->
                        <h3
                            class="text-lg font-semibold text-gray-800 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            {{ item.name }}
                        </h3>

                        <!-- Description -->
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2 leading-relaxed">
                            {{ item.description }}
                        </p>

                        <!-- Updated Info -->
                        <div
                            class="mb-4 text-xs text-gray-500 dark:text-gray-400 border-l-2 border-gray-200 dark:border-gray-600 pl-3">
                            <div class="flex items-center space-x-1 mb-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ item.updated_at }}</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>by {{ item.updated_by_name }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-2">
                            <button @click="openModal(item)"
                                class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800">
                                <span class="flex items-center justify-center space-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    <span>Edit</span>
                                </span>
                            </button>

                            <button @click="openDeleteModal(item)"
                                class="px-3 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 hover:text-red-700 rounded-xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 dark:bg-red-900/20 dark:hover:bg-red-900/30">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Empty State -->
            <template v-else-if="!isLoadingData">
                <div
                    class="col-span-full flex flex-col items-center justify-center py-20 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-slate-800 dark:to-slate-900 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-600 text-center">
                    <div class="w-16 h-16 bg-gray-200 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">
                        No Items Found
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm max-w-md leading-relaxed">
                        It seems there are no items to display here. Try adding new items or adjusting your filters to see
                        more content.
                    </p>
                </div>
            </template>
        </div>
        <!-- Pagination -->
        <Pagination v-if="items" :current-page="items.current_page || 1" :last-page="items.last_page || 1"
            :from="items.from" :to="items.to" :total="items.total" @prev="previousPage" @next="nextPage" />
    </div>
</template>
<script setup>
import { ref, computed, watch, onMounted, onUnmounted, nextTick } from 'vue';
import { useRoute } from 'vue-router';
import { useToast } from 'vue-toastification';
import axios from 'axios';

import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from '@headlessui/vue';

import { ChevronUpDownIcon, CheckIcon, PlusIcon, XMarkIcon } from '@heroicons/vue/20/solid';
import Pagination from '@/components/Pagination.vue';
import { useAppStore } from '@/stores/useAppStore';

// ========== INITIAL SETUP ==========
const toast = useToast();
const route = useRoute();
const baseUrl = document.querySelector('meta[name="base-url"]').content;

// ========== STATE ==========
const categoryId = ref(route.params.categoryId);
const currentCategory = ref(null);
const loading = ref(false);
const isLoadingData = ref(true);
const isItemModalOpen = ref(false);
const isDeleteItemModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const showMobileFilters = ref(false);
const showButtonDeleteSelectedItems = ref(false);

const titleModal = ref('');
const titleModalButton = ref('');
const isCreateNewItem = ref(true);

const items = ref([]);
const itemsToDelete = ref([]);
const selectedCategories = ref([]);
const search = ref('');
const isDesc = ref(true);
const currentPage = ref(1);
const totalPages = ref(1);
const dataPerPages = ref(0);

const itemImage = ref(null);
const uploadedItemImageFile = ref(null);
const dragover = ref(false);
const nextSetId = ref(2); // For adding new dimension sets

// ========== FILTER OPTIONS ==========
const sortByItems = [
    { name: 'Name', value: 'name' },
    { name: 'Created Date', value: 'created_at' },
    { name: 'Updated Date', value: 'updated_at' },
];

const statusItems = [
    { name: 'Active', value: 'true' },
    { name: 'Inactive', value: 'false' },
    { name: 'All', value: '' },
];

const pageSizeItems = [
    { name: '10', value: 10 },
    { name: '25', value: 25 },
    { name: '50', value: 50 },
    { name: '100', value: 100 },
];

const selectedStatusFilter = ref(statusItems[0]);
const selectedSortByFilter = ref(sortByItems[1]);
const selectedPageSizeFilter = ref(pageSizeItems[0]);

// ========== UNITS ==========
const units = ref(['inch', 'mm', 'cm']);

// ========== FORM DATA ==========
const itemForm = ref({
    id: 0,
    name: '',
    description: '',
    image: null,
    dimensionSets: [
        {
            id: 0,
            outer_diameter: { value: '', unit: 'inch' },
            inner_diameter: { value: '', unit: 'inch' },
            length: { value: '', unit: 'inch' },
        }
    ],
    dimension_sets_deleted_ids: [],
});

// ========== COMPUTED ==========
const direction = computed(() => (isDesc.value ? 'desc' : 'asc'));
const totalCurrentCategories = computed(() => items.value.total_current_datas);
const totalRealCategories = computed(() => items.value.total_real_datas);
const totalActiveCategories = computed(() => items.value.total_active_datas);
const totalInactiveCategories = computed(() => items.value.total_inactive_datas);
const totalProductsInCategories = ref(0);

// ========== FUNCTIONS: MODAL ==========
function openModal(selectedItem = null) {
    if (selectedItem) {
        itemForm.value.id = selectedItem.id;
        itemForm.value.name = selectedItem.name;
        itemForm.value.description = selectedItem.description;
        itemForm.value.dimensionSets = selectedItem.dimension_sets || [
            {
                id: 0,
                outer_diameter: { value: '', unit: 'inch' },
                inner_diameter: { value: '', unit: 'inch' },
                length: { value: '', unit: 'inch' },
                is_current: false,
            }
        ];
        console.log(itemForm.value.dimensionSets);
        itemForm.value.image = selectedItem.image || null;
        itemImage.value = selectedItem.image_url || null;
        titleModal.value = 'Edit Item';
        titleModalButton.value = 'Update Item';
        isCreateNewItem.value = false;
    } else {
        titleModal.value = 'Create New Item';
        titleModalButton.value = 'Create Item';
        resetForm();
    }
    isItemModalOpen.value = true;
}

function openDeleteModal(item) {
    isDeleteItemModalOpen.value = true;
    itemsToDelete.value = [item];
}

function closeModal() {
    isItemModalOpen.value = false;
    isDeleteItemModalOpen.value = false;
    resetForm();
}

function resetForm() {
    isCreateNewItem.value = true;
    itemForm.value = {
        id: 0,
        name: '',
        description: '',
        image: null,
        dimensionSets: [
            {
                id: 0,
                outer_diameter: { value: '', unit: 'inch' },
                inner_diameter: { value: '', unit: 'inch' },
                length: { value: '', unit: 'inch' },
                is_current: false
            }
        ],
        dimension_sets_deleted_ids: [],
    };
    itemImage.value = null;
    itemsToDelete.value = [];
}

// ========== FUNCTIONS: FILE HANDLING ==========
const validateFile = (file) => {
    const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!validTypes.includes(file.type)) {
        alert('Please upload an image file (PNG, JPG, or GIF)');
        return false;
    }
    if (file.size > 5 * 1048576) {
        alert('File size should be less than 5MB');
        return false;
    }
    return true;
};

const processFile = (file) => {
    if (file.type.startsWith('image/') && validateFile(file)) {
        const reader = new FileReader();
        reader.onload = (e) => {
            itemImage.value = e.target.result;
            uploadedItemImageFile.value = file;
        };
        reader.readAsDataURL(file);
    }
};

const handleFileSelect = (event) => {
    const file = event.target.files[0];
    if (file) processFile(file);
};

const handleDrop = (event) => {
    dragover.value = false;
    const file = event.dataTransfer.files[0];
    if (file) processFile(file);
};

// ========== FUNCTIONS: SELECTION ==========
const selectAllCategories = () => {
    selectedCategories.value = items.value.data.map((cat) => cat);
};

const clearSelectAllCategories = () => {
    selectedCategories.value = [];
    showButtonDeleteSelectedItems.value = false;
};

// ========== FUNCTIONS: PAGINATION ==========
const previousPage = () => {
    if (currentPage.value > 1) fetchAllItems(currentPage.value - 1);
};

const nextPage = () => {
    if (currentPage.value < totalPages.value) fetchAllItems(currentPage.value + 1);
};

// ========== FUNCTIONS: API ==========
const getCurrentCategory = async () => {
    try {
        const response = await axios.get(`${baseUrl}/api/toolstring-categories/${categoryId.value}`);
        currentCategory.value = response.data;
    } catch (error) {
        console.error('Error fetching current category:', error);
    }
};

const fetchAllItems = async (page = 1) => {
    try {
        isLoadingData.value = true;
        loading.value = true;

        const response = await axios.get(`${baseUrl}/api/toolstring-items`, {
            params: {
                category_id: categoryId.value,
                page,
                per_page: selectedPageSizeFilter.value.value,
                search: search.value,
                status: selectedStatusFilter.value.value,
                sort_by: selectedSortByFilter.value.value,
                direction: direction.value,
            },
        });

        items.value = response.data;
        items.value.data.forEach((item) => {
            item.created_at = formatDate(item.created_at);
            item.updated_at = formatDate(item.updated_at);
        });

        totalPages.value = response.data.last_page;
        currentPage.value = response.data.current_page;
    } catch (error) {
        console.error('Error fetching items:', error);
    } finally {
        loading.value = false;
        isLoadingData.value = false;
    }
};

// ========== FUNCTIONS: SAVE ==========
const saveItem = async () => {
    try {
        let message = '';
        loading.value = true;

        const formData = new FormData();
        formData.append('toolstring_category_id', categoryId.value);
        formData.append('name', itemForm.value.name);
        formData.append('description', itemForm.value.description);
        formData.append('dimension_sets', JSON.stringify(getDimensionSetsData()));

        if (uploadedItemImageFile.value) {
            const response = await fetch(itemImage.value);
            const blob = await response.blob();
            const file = new File([blob], uploadedItemImageFile.value.name, {
                type: uploadedItemImageFile.value.type,
            });
            formData.append('image', file);
        }

        if (itemForm.value.dimension_sets_deleted_ids.length > 0) {
            formData.append('dimension_sets_deleted_ids', JSON.stringify(itemForm.value.dimension_sets_deleted_ids));
        }

        // formData.forEach((value, key) => {
        //     console.log(`${key}: ${value}`);
        // });

        // let dimension = getDimensionSetsData()
        // console.log('Dimension Summary:', dimension);

        if (isCreateNewItem.value) {
            await axios.post(`${baseUrl}/api/toolstring-items`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            message = 'Item created successfully!';
        } else {
            formData.append('_method', 'put');
            await axios.post(`${baseUrl}/api/toolstring-items/${itemForm.value.id}`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            message = 'Item updated successfully!';
        }

        toast.success(message);
        fetchAllItems();
        closeModal();
    } catch (error) {
        console.error('Error creating item:', error);
        toast.error('Failed to create item. Please try again.');
    } finally {
        loading.value = false;
    }
};

// ========== FUNCTIONS: DELETE ==========
const confirmDelete = async () => {
    try {
        loading.value = true;
        const idsToDelete = itemsToDelete.value.map(item => item.id);
        await axios.delete(`${baseUrl}/api/toolstring-items`, {
            data: { ids: idsToDelete }
        });

        toast.success('Items deleted successfully!');
        itemsToDelete.value = [];
        closeModal();
        fetchAllItems();
    } catch (error) {
        console.error('Error deleting items:', error);
        toast.error('Failed to delete items. Please try again.');
    } finally {
        loading.value = false;
    }
};

// ========== FUNCTIONS: UTIL ==========
const formatDate = (utcDateString) => {
    const date = new Date(utcDateString);
    const options = {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
        timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone,
    };
    return date.toLocaleString('en-US', options).replace(',', '').replace(',', ' at');
};

function handleDecimalInput(event, setIndex, fieldName) {
    const value = event.target.value;
    // Allow only numbers and decimal point
    let sanitizedValue = value.replace(/[^0-9.]/g, '');

    // Prevent multiple decimal points
    const parts = sanitizedValue.split('.');
    if (parts.length > 2) {
        sanitizedValue = parts[0] + '.' + parts.slice(1).join('');
    }

    itemForm.value.dimensionSets[setIndex][fieldName].value = sanitizedValue;
    event.target.value = sanitizedValue;
}

// ========== FUNCTIONS: DIMENSIONS ==========
function addDimensionSet() {
    itemForm.value.dimensionSets.push({
        outer_diameter: {
            value: '',
            unit: 'inch'
        },
        inner_diameter: {
            value: '',
            unit: 'inch'
        },
        length: {
            value: '',
            unit: 'inch'
        },
        is_current: false
    });
}

function removeDimensionSet(setIndex) {
    if (itemForm.value.dimensionSets.length > 1) {
        const deletedId = itemForm.value.dimensionSets[setIndex].id;
        if (deletedId && deletedId > 0) {
            // Catat untuk dihapus di server
            itemForm.value.dimension_sets_deleted_ids.push(deletedId);
        }

        itemForm.value.dimensionSets.splice(setIndex, 1);
    }
}

function getDimensionSetsData() {
    return itemForm.value.dimensionSets.map((set, index) => ({
        id: set.id || 0,
        outer_diameter: {
            value: parseFloat(set.outer_diameter.value) || 0,
            unit: set.outer_diameter.unit
        },
        inner_diameter: {
            value: parseFloat(set.inner_diameter.value) || 0,
            unit: set.inner_diameter.unit
        },
        length: {
            value: parseFloat(set.length.value) || 0,
            unit: set.length.unit
        },
        is_current: set.is_current || false,
    }));
}

function validateDimensionSets() {
    return itemForm.value.dimensionSets.every(set => {
        const outerDiameterValid = set.outer_diameter.value &&
            set.outer_diameter.value.trim() !== '' &&
            !isNaN(parseFloat(set.outer_diameter.value));

        const innerDiameterValid = set.inner_diameter.value &&
            set.inner_diameter.value.trim() !== '' &&
            !isNaN(parseFloat(set.inner_diameter.value));

        // Length bisa optional, jadi tidak wajib diisi
        const lengthValid = !set.length.value ||
            (set.length.value.trim() !== '' && !isNaN(parseFloat(set.length.value)));

        return outerDiameterValid && innerDiameterValid && lengthValid;
    });
}

function getDimensionsSummary() {
    return itemForm.value.dimensionSets.map((set, index) => {
        return `Set ${--index}: OD=${set.outer_diameter.value}${set.outer_diameter.unit}, ID=${set.inner_diameter.value}${set.inner_diameter.unit}, L=${set.length.value}${set.length.unit}`;
    });
}

// ========== WATCHERS ==========
watch([selectedStatusFilter, selectedSortByFilter, selectedPageSizeFilter, search, isDesc], () => { });
watch(selectedCategories, (newVal) => {
    showButtonDeleteSelectedItems.value = newVal.length > 0;
});

watch(
    () => route.params.categoryId,
    (newVal) => {
        categoryId.value = newVal;
        getCurrentCategory();
        fetchAllItems();
    },
    { immediate: true }
);

// ========== LIFECYCLE ==========
onMounted(() => {
    getCurrentCategory();
    fetchAllItems();
});

onUnmounted(() => { });
</script>


<style>@keyframes shimmer {
    0% {
        background-position: -200% 0;
    }

    100% {
        background-position: 200% 0;
    }
}

.animate-shimmer {
    animation: shimmer 2s infinite linear;
}</style>