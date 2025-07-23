<template>
    <head>
        <Title>JobTracker</Title>
    </head>
    <!-- Main Content -->
    <div class="p-6 bg-gray-50 min-h-screen dark:bg-slate-900/50 dark:text-gray-100 rounded-xl">
        <!-- Header Section with improved styling -->
        <div v-if="!isCreate" class="mb-8 bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Job Tracker Management</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Efficiently monitor, organize, and manage your job
                        tracking tools and components.</p>
                </div>
                <button @click="$router.push('/job-tracker/create')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg flex items-center space-x-2 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Add New Job Tracker</span>
                </button>
            </div>
        </div>

        <div class="job-tracker-wrapper">
            <Transition enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="opacity-0 translate-x-full" enter-to-class="opacity-100 translate-x-0"
                leave-active-class="transition-all duration-300 ease-in" leave-from-class="opacity-100 translate-x-0"
                leave-to-class="opacity-0 -translate-x-full" mode="out-in">
                <router-view :key="$route.fullPath" />
            </Transition>
        </div>

        <!-- Search and Filter Section -->
        <div v-if="!isCreate"
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
                        <input v-model="search" type="text" placeholder="Search job tracker..."
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
                        <button @click="fetchJobTrackers(pagination.current_page)" :disabled="isLoading"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <span v-if="!isLoading" class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                Refresh Data
                            </span>
                            <span v-else class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
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
                        <button @click="fetchJobTrackers(pagination.current_page)" :disabled="isLoading"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <span v-if="!isLoading" class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                Refresh Data
                            </span>
                            <span v-else class="flex items-center gap-1">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
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

                            <!-- Sort Direction Toggle -->
                            <SwitchGroup as="div" class="flex items-center space-x-2">
                                <SwitchLabel as="span" class="text-sm text-gray-700 dark:text-white">Asc</SwitchLabel>
                                <Switch v-model="isDesc" :class="isDesc ? 'bg-blue-600' : 'bg-gray-400'"
                                    class="relative inline-flex items-center h-6 w-11 shrink-0 cursor-pointer rounded-full border border-gray-200 dark:border-slate-600 transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75">
                                    <span class="sr-only">Toggle sort direction</span>
                                    <span aria-hidden="true" :class="isDesc ? 'translate-x-5' : 'translate-x-0'"
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out" />
                                </Switch>
                                <SwitchLabel as="span" class="text-sm text-gray-700 dark:text-white">Desc</SwitchLabel>
                            </SwitchGroup>
                        </div>
                    </div>

                    <!-- Mobile: Vertical Grid Layout for Filters -->
                    <div class="grid grid-cols-1 gap-4 sm:hidden">
                        <!-- Row 1: Status and Sort By -->
                        <div class="grid grid-cols-2 gap-3">
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

        <!-- Table Section with improved styling -->
        <div v-if="!isCreate" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Job Tracker List</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"></p>
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
                            <tr v-for="(jobTracker, index) in listJobTrackers" :key="jobTracker.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ (pagination.current_page - 1) * perPage + index + 1 }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ jobTracker.well_name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ jobTracker.company_man }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ formatDate(jobTracker.updated_at) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ jobTracker.updated_by_name }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <!-- Edit -->
                                    <button @click="openJobTrackerModal(jobTracker)"
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-md hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors duration-150">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>

                                    <!-- Delete -->
                                    <button @click="confirmDeleteModal(jobTracker)"
                                        class="inline-flex items-center ms-2 px-2.5 py-1.5 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 rounded-lg transition-all duration-200 group">
                                        <svg class="w-4 h-4 mr-1.5 transition-transform group-hover:scale-110" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span class="text-sm font-medium">Delete</span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Improved Pagination with Per-Page Selector -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mt-6 space-y-3 md:space-y-0 px-4">
                    <!-- Per Page Selector -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Show</span>
                        <Listbox v-model="perPage" @update:modelValue="changePerPage">
                            <div class="relative">
                                <ListboxButton
                                    class="relative w-20 cursor-default rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 py-1.5 pl-3 pr-8 text-left text-sm text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    {{ perPage }}
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
            </div>
        </div>
    </div>
</template>
  
<script setup>
/* ------------------------------- IMPORTS ------------------------------- */
import { ref, onMounted, watch, computed } from 'vue';
import { useToast } from 'vue-toastification';
import { useRoute } from 'vue-router';
import { useCurrentUserStore } from '@/stores/CurrentUser';
import {
    TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle,
    Listbox, ListboxButton, ListboxOptions, ListboxOption,
    Switch, SwitchGroup, SwitchLabel,
} from '@headlessui/vue';
import {
    ChevronUpDownIcon, ChevronLeftIcon, ChevronRightIcon,
    ChevronDoubleLeftIcon, ChevronDoubleRightIcon, CheckIcon
} from '@heroicons/vue/20/solid';

/* ----------------------------- STATE & STORES ----------------------------- */
const baseUrl = import.meta.env.VITE_API_URL;
const route = useRoute();
const isCreate = computed(() => route.name === 'create-job-tracker');

const listJobTrackers = ref([]);
const pagination = ref({ current_page: 1, last_page: 1 });

const jobTrackerForm = ref({ type: '' });
const jobTrackerFormSize = ref({ top_connection: '', bottom_connection: '' });
const listJobTrackerSizes = ref([]);

const isLoading = ref(false);
const isJobTrackerModalOpen = ref(false);
const titleModal = ref('Add JobTracker');
const titleModalButton = ref('Save JobTracker');
const addSizeLoading = ref(false);
const editingSizeIndex = ref(null);
const editingRowIndex = ref(null);
const loading = ref(false);
const loadingAllSizes = ref(false);
const isDeleteModalOpen = ref(false);
const isDeleting = ref(false);
const showMobileFilters = ref(false);

const sortByItems = ref([
    { name: 'Updated At', value: 'updated_at' },
    { name: 'Updated By', value: 'updated_by_name' },
    { name: 'Type', value: 'type' },
]);

const selectedSortByFilter = ref(sortByItems.value[0]);
const isDesc = ref(true);

const selectedJobTracker = ref(null);
const currentUserStore = useCurrentUserStore();

const perPageOptions = [10, 25, 100];
const perPage = ref(perPageOptions[0]);
const search = ref('');

/* ------------------------------- UTILITIES ------------------------------- */
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

/* ------------------------------ MODAL HANDLERS ------------------------------ */
function closeModal() {
    isJobTrackerModalOpen.value = false;
    isDeleteModalOpen.value = false;
    resetForm();
}

function resetForm() {
    jobTrackerForm.value = { type: '' };
    jobTrackerFormSize.value = { top_connection: '', bottom_connection: '' };
    listJobTrackerSizes.value = [];
    editingSizeIndex.value = null;
    editingRowIndex.value = null;
}

const openJobTrackerModal = async (jobTracker) => {
    if (jobTracker) {
        isJobTrackerModalOpen.value = true;
        titleModal.value = 'Edit JobTracker';
        titleModalButton.value = 'Update JobTracker';
        selectedJobTracker.value = jobTracker;
        jobTrackerForm.value.type = jobTracker.type;
        fetchJobTrackerSizes(jobTracker.id);
    } else {
        isJobTrackerModalOpen.value = true;
        titleModal.value = 'Add JobTracker';
    }
};

const confirmDeleteModal = (jobTracker) => {
    selectedJobTracker.value = jobTracker;
    isDeleteModalOpen.value = true;
};

/* ----------------------------- SIZE HANDLERS ----------------------------- */
function editJobTrackerSize(_, index) {
    editingRowIndex.value = index;
}

function saveJobTrackerSize(index) {
    editingRowIndex.value = null;
}

function addSize() {
    if (!jobTrackerFormSize.value.top_connection || !jobTrackerFormSize.value.bottom_connection) return;

    addSizeLoading.value = true;

    setTimeout(() => {
        const now = new Date().toISOString();
        const userName = currentUserStore.user ? currentUserStore.user.fullname : 'Current User';

        if (editingSizeIndex.value !== null) {
            // Update existing size
            listJobTrackerSizes.value[editingSizeIndex.value] = {
                ...listJobTrackerSizes.value[editingSizeIndex.value],
                top_connection: jobTrackerFormSize.value.top_connection,
                bottom_connection: jobTrackerFormSize.value.bottom_connection,
                updated_at: now,
                updated_by_name: userName,
            };
        } else {
            // Add new size
            listJobTrackerSizes.value.push({
                id: 0,
                top_connection: jobTrackerFormSize.value.top_connection,
                bottom_connection: jobTrackerFormSize.value.bottom_connection,
                updated_at: now,
                updated_by_name: userName,
            });
        }

        // Clear input
        jobTrackerFormSize.value = { top_connection: '', bottom_connection: '' };
        editingSizeIndex.value = null;
        addSizeLoading.value = false;
    }, 500);
}

function deleteJobTrackerSize(index) {
    listJobTrackerSizes.value.splice(index, 1);
}

/* ----------------------------- API HANDLERS ----------------------------- */
async function fetchJobTrackers(page = 1) {
    try {
        isLoading.value = true;
        const response = await axios.get(`${baseUrl}/api/job-trackers?page=${page}&per_page=${perPage.value}&search=${search.value}&sort_by=${selectedSortByFilter.value.value}&is_desc=${isDesc.value}`);
        listJobTrackers.value = response.data.data;
        pagination.value = {
            current_page: response.data.current_page,
            last_page: response.data.last_page,
        };
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
    }
}

async function fetchJobTrackerSizes(jobTrackerId) {
    try {
        loadingAllSizes.value = true;
        const response = await axios.get(`${baseUrl}/api/jobTrackers/${jobTrackerId}/sizes`);
        listJobTrackerSizes.value = response.data.data;
    } catch (error) {
        console.error(error);
    } finally {
        loadingAllSizes.value = false;
    }
}

const saveJobTracker = async () => {
    loading.value = true;
    const toast = useToast();

    try {
        const data = {
            type: jobTrackerForm.value.type,
            sizes: listJobTrackerSizes.value,
        };

        if (selectedJobTracker.value) {
            // Update existing jobTracker
            data.id = selectedJobTracker.value.id;
            const response = await axios.put(`${baseUrl}/api/jobTrackers/${selectedJobTracker.value.id}`, data);
            if (response.status === 200) {
                toast.success('JobTracker updated successfully!');
            }
        } else {
            // Add new jobTracker
            const response = await axios.post(`${baseUrl}/api/jobTrackers`, data);
            if (response.status === 201) {
                toast.success('JobTracker saved successfully!');
            }
        }

        resetForm();
        fetchJobTrackers(pagination.value.current_page);
        closeModal();
    } catch (error) {
        console.error(error);
        toast.error('Failed to save jobTracker.');
    } finally {
        loading.value = false;
    }
};

function handleDeleteJobTracker() {
    isDeleting.value = true;
    const toast = useToast();

    let ids = [selectedJobTracker.value.id];
    axios.delete(`${baseUrl}/api/jobTrackers`, {
        data: { ids }
    })
        .then(response => {
            if (response.status === 200) {
                toast.success('JobTracker deleted successfully!');
                fetchJobTrackers(pagination.value.current_page);
                closeModal();
            }
        })
        .catch(error => {
            console.error(error);
            toast.error('Failed to delete jobTracker.');
        })
        .finally(() => {
            isDeleting.value = false;
        });
}

function goToPage(page) {
    if (page < 1 || page > pagination.value.last_page) return;
    fetchJobTrackers(page);
}

function changePerPage(newPerPage) {
    perPage.value = newPerPage;
    pagination.value.current_page = 1;
    fetchJobTrackers(1);
}

const displayedPages = computed(() => {
    if (!pagination.value?.current_page || !pagination.value?.last_page) {
        return [];
    }
    const current = pagination.value.current_page;
    const last = pagination.value.last_page;
    const delta = 2;
    const range = [];

    for (let i = 1; i <= last; i++) {
        if (i === 1 || i === last || (i >= current - delta && i <= current + delta)) {
            range.push(i);
        } else if (range[range.length - 1] !== '...') {
            range.push('...');
        }
    }

    return range;
});

/* ------------------------------ FILTERS ------------------------------ */
watch([selectedSortByFilter, perPage, search, isDesc], () => {
    fetchJobTrackers(pagination.value.current_page || 1);
});

/* ------------------------------ ON MOUNT ------------------------------ */
onMounted(async () => {
    if (!currentUserStore.user) {
        await currentUserStore.fetchUser();
    }
    fetchJobTrackers();
});
</script>