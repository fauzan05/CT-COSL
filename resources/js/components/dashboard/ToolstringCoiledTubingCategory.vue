<template>
    <head>
        <Title>Toolstring Coiled Tubing - {{ currentCategory?.name }}</Title>
    </head>
    <!-- modal create new item -->
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
                            <!-- Tombol silang di sudut kanan atas -->
                            <button @click="closeModal"
                                class="absolute top-4 right-6 text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-white transition-colors text-2xl leading-none"
                                aria-label="Close modal">
                                &times;
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
                                        <!-- Dimensions with Units -->
                                        <div class="mb-6">
                                            <h3 class="text-sm font-medium text-gray-700 mb-4 dark:text-white">Dimensions
                                            </h3>

                                            <!-- Outer Diameter -->
                                            <div class="mb-4 flex items-center space-x-4">
                                                <div class="flex-1">
                                                    <label for="outer_diameter"
                                                        class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                        Outer Diameter
                                                    </label>
                                                    <div class="flex space-x-2">
                                                        <input type="number" id="outer_diameter"
                                                            v-model="itemForm.outer_diameter.value"
                                                            class="w-full px-3 py-2 dark:text-white border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                            required>
                                                        <Listbox v-model="itemForm.outer_diameter.unit" as="div"
                                                            class="relative">
                                                            <ListboxButton
                                                                class="relative w-24 cursor-pointer rounded-md bg-white dark:bg-gray-800 py-2 pl-3 pr-10 text-left border border-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                                                <span class="block truncate dark:text-white">{{
                                                                    itemForm.outer_diameter.unit }}</span>
                                                                <span
                                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400"
                                                                        aria-hidden="true" />
                                                                </span>
                                                            </ListboxButton>
                                                            <transition leave-active-class="transition duration-100 ease-in"
                                                                leave-from-class="opacity-100" leave-to-class="opacity-0">
                                                                <ListboxOptions
                                                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-gray-800 py-1 text-base shadow-lg focus:outline-none sm:text-sm">
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

                                            <!-- Inner Diameter -->
                                            <div class="mb-4 flex items-center space-x-4">
                                                <div class="flex-1">
                                                    <label for="inner_diameter"
                                                        class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                        Inner Diameter
                                                    </label>
                                                    <div class="flex space-x-2">
                                                        <input type="number" id="inner_diameter"
                                                            v-model="itemForm.inner_diameter.value"
                                                            class="w-full px-3 py-2 dark:text-white border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                            required>
                                                        <Listbox v-model="itemForm.inner_diameter.unit" as="div"
                                                            class="relative">
                                                            <ListboxButton
                                                                class="relative w-24 cursor-pointer rounded-md bg-white dark:bg-gray-800 py-2 pl-3 pr-10 text-left border border-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                                                <span class="block truncate dark:text-white">{{
                                                                    itemForm.inner_diameter.unit }}</span>
                                                                <span
                                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400"
                                                                        aria-hidden="true" />
                                                                </span>
                                                            </ListboxButton>
                                                            <transition leave-active-class="transition duration-100 ease-in"
                                                                leave-from-class="opacity-100" leave-to-class="opacity-0">
                                                                <ListboxOptions
                                                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-gray-800 py-1 text-base shadow-lgfocus:outline-none sm:text-sm">
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

                                            <!-- Length -->
                                            <div class="mb-4 flex items-center space-x-4">
                                                <div class="flex-1">
                                                    <label for="length"
                                                        class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                        Length
                                                    </label>
                                                    <div class="flex space-x-2">
                                                        <input type="number" id="length" v-model="itemForm.length.value"
                                                            class="w-full px-3 py-2 dark:text-white border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                            required>
                                                        <Listbox v-model="itemForm.length.unit" as="div" class="relative">
                                                            <ListboxButton
                                                                class="relative w-24 cursor-pointer rounded-md bg-white dark:bg-gray-800 py-2 pl-3 pr-10 text-left border border-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                                                <span class="block truncate dark:text-white">{{
                                                                    itemForm.length.unit }}</span>
                                                                <span
                                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400"
                                                                        aria-hidden="true" />
                                                                </span>
                                                            </ListboxButton>
                                                            <transition leave-active-class="transition duration-100 ease-in"
                                                                leave-from-class="opacity-100" leave-to-class="opacity-0">
                                                                <ListboxOptions
                                                                    class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-gray-800 py-1 text-base shadow-lg focus:outline-none sm:text-sm">
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

                            <button @click="deleteCategory(item)"
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

import { ChevronUpDownIcon, CheckIcon } from '@heroicons/vue/20/solid';
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
const isDeleteModalOpen = ref(false);
const showMobileFilters = ref(false);
const showButtonDeleteSelectedItems = ref(false);

const titleModal = ref('');
const titleModalButton = ref('');
const isCreateNewItem = ref(true);

const items = ref([]);
const selectedCategories = ref([]);
const search = ref('');
const isDesc = ref(true);
const currentPage = ref(1);
const totalPages = ref(1);
const dataPerPages = ref(0);

const itemImage = ref(null);
const uploadedItemImageFile = ref(null);
const dragover = ref(false);

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
    outer_diameter: { value: '', unit: 'inch' },
    inner_diameter: { value: '', unit: 'inch' },
    length: { value: '', unit: 'inch' },
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
        itemForm.value.outer_diameter.value = selectedItem.outer_diameter || 0;
        itemForm.value.outer_diameter.unit = selectedItem.outer_diameter_unit || 'inch';
        itemForm.value.inner_diameter.value = selectedItem.inner_diameter || 0;
        itemForm.value.inner_diameter.unit = selectedItem.inner_diameter_unit || 'inch';
        itemForm.value.length.value = selectedItem.length || 0;
        itemForm.value.length.unit = selectedItem.length_unit || 'inch';
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

function closeModal() {
    isItemModalOpen.value = false;
}

function resetForm() {
    isCreateNewItem.value = true;
    itemForm.value = {
        id: 0,
        name: '',
        description: '',
        image: null,
        outer_diameter: { value: '', unit: 'inch' },
        inner_diameter: { value: '', unit: 'inch' },
        length: { value: '', unit: 'inch' },
    };
    itemImage.value = null;
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
        formData.append('outer_diameter', itemForm.value.outer_diameter.value);
        formData.append('outer_diameter_unit', itemForm.value.outer_diameter.unit);
        formData.append('inner_diameter', itemForm.value.inner_diameter.value);
        formData.append('inner_diameter_unit', itemForm.value.inner_diameter.unit);
        formData.append('length', itemForm.value.length.value);
        formData.append('length_unit', itemForm.value.length.unit);

        if (uploadedItemImageFile.value) {
            const response = await fetch(itemImage.value);
            const blob = await response.blob();
            const file = new File([blob], uploadedItemImageFile.value.name, {
                type: uploadedItemImageFile.value.type,
            });
            formData.append('image', file);
        }

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