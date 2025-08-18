<template>
    <div>
        <!-- Tombol Search Mobile -->
        <button @click="openSearchModal"
            class="md:hidden p-2.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800/50 dark:text-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 text-gray-600 dark:text-white group-hover:text-gray-800 dark:group-hover:text-gray-200 transition-colors"
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
            </svg>
        </button>

        <!-- Search Bar Desktop -->
        <button @click="openSearchModal" class="md:flex hidden items-center text-left space-x-3 px-4 h-10 
    bg-white dark:bg-slate-800/50 
    ring-1 ring-slate-900/10 dark:ring-slate-700/50 
    hover:ring-slate-300 dark:hover:ring-slate-500 
    focus:outline-none focus:ring-2 focus:ring-blue-500 
    shadow-sm dark:shadow-slate-800/30 
    rounded-lg text-slate-400 dark:text-slate-300 
    backdrop-blur-sm transition-all duration-200">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="flex-none text-slate-300 dark:text-slate-400" aria-hidden="true">
                <path d="m19 19-3.5-3.5" />
                <circle cx="11" cy="11" r="6" />
            </svg>
            <span class="flex-auto dark:text-slate-400">Quick search...</span>
            <kbd class="font-sans font-semibold dark:text-slate-500">
                <template v-if="isMac">
                    <abbr title="Command" class="no-underline text-slate-300 dark:text-slate-500">⌘</abbr> K
                </template>
                <template v-else>
                    <abbr title="Control" class="no-underline text-slate-300 dark:text-slate-500">Ctrl</abbr> K
                </template>
            </kbd>
        </button>

        <!-- Modal Backdrop -->
        <Teleport to="body">
            <transition enter-active-class="ease-out duration-300" enter-from-class="opacity-0" enter-to-class="opacity-100"
                leave-active-class="ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="isOpen" class="fixed inset-0 bg-gray-900/70 dark:bg-slate-900/80 backdrop-blur-sm z-[999]"
                    @click="closeSearchModal" />
            </transition>

            <!-- Search Modal -->
            <transition enter-active-class="ease-out duration-300" enter-from-class="opacity-0 scale-95"
                enter-to-class="opacity-100 scale-100" leave-active-class="ease-in duration-200"
                leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
                <div v-if="isOpen" class="fixed inset-x-0 top-0 z-[1000] p-4 sm:p-6 md:p-20 transition-all">
                    <div ref="modalContent" class="mx-auto max-w-2xl transform divide-y divide-gray-100 dark:divide-gray-700/50 
                               overflow-hidden rounded-xl 
                               bg-white dark:bg-slate-800/90 
                               shadow-2xl dark:shadow-slate-900/20 
                               ring-1 ring-black/5 dark:ring-white/10 
                               backdrop-blur-xl transition-all">
                        <!-- Search Input -->
                        <div class="relative">
                            <svg class="pointer-events-none absolute left-4 top-3.5 h-5 w-5 text-gray-400 dark:text-gray-500"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                                    clip-rule="evenodd" />
                            </svg>
                            <input ref="searchInput" v-model="searchQuery" type="text" class="h-12 w-full border-0 bg-transparent pl-11 pr-4 
                                       text-gray-900 dark:text-gray-100 
                                       placeholder:text-gray-400 dark:placeholder:text-gray-500 
                                       focus:ring-2 focus:ring-blue-500 focus:outline-none 
                                       sm:text-sm" placeholder="Search pages, tools, and more..."
                                @keyup.esc="closeSearchModal" @keydown="handleSearchKeydown" />
                            <div class="absolute right-4 top-3">
                                <kbd class="inline-flex items-center rounded border 
                                           border-gray-200 dark:border-gray-700 
                                           px-1.5 text-sm text-gray-400 dark:text-gray-500">esc</kbd>
                            </div>
                            <!-- Loading indicator -->
                            <div v-if="isSearching" class="absolute right-12 top-4">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500"></div>
                            </div>
                        </div>

                        <!-- Search Results -->
                        <div v-if="searchQuery && (searchResults.length > 0 || groupedResults.length > 0)"
                            class="flex flex-col">

                            <!-- Main Pages/Menu Results -->
                            <div v-if="searchResults.length > 0" class="divide-y divide-gray-100 dark:divide-gray-700/50">
                                <div class="px-6 py-4">
                                    <h2 class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                                        Pages ({{ searchResults.length }})
                                    </h2>
                                </div>
                                <div class="max-h-48 overflow-y-auto">
                                    <div v-for="(item, index) in searchResults" :key="`main-${index}`" class="flex items-center px-6 py-3 transition 
                                                hover:bg-gray-50 dark:hover:bg-slate-700/50 
                                                cursor-pointer"
                                        :class="{ 'bg-blue-50 dark:bg-blue-900/20': selectedIndex === index }"
                                        @click="selectSearchResult(item)" @mouseenter="selectedIndex = index">
                                        <div class="flex-shrink-0 mr-3">
                                            <div
                                                class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center">
                                                <i class="text-blue-600 dark:text-blue-400 fa-solid text-sm"
                                                    :class="item.icon"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100"
                                                v-html="highlightMatch(item.title)"></p>
                                            <p v-if="item.description" class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                                                v-html="highlightMatch(item.description)"></p>
                                        </div>
                                        <div class="flex-shrink-0 ml-3">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sub-menu/Types Results -->
                            <div v-if="groupedResults.length > 0" class="divide-y divide-gray-100 dark:divide-gray-700/50">
                                <div class="px-6 py-4">
                                    <h2 class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                                        Tools & Types ({{ getTotalSubResults() }})
                                    </h2>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    <div v-for="(group, groupIndex) in groupedResults" :key="`group-${groupIndex}`">
                                        <!-- Parent Category Header -->
                                        <div
                                            class="px-6 py-2 bg-gray-50 dark:bg-slate-800/50 border-b border-gray-100 dark:border-gray-700/30">
                                            <div class="flex items-center space-x-2">
                                                <i class="fa-solid text-xs text-gray-500 dark:text-gray-400"
                                                    :class="group.parentIcon"></i>
                                                <span class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                                    {{ group.parentTitle }}
                                                </span>
                                                <span class="text-xs text-gray-400 dark:text-gray-500">
                                                    ({{ group.items.length }})
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Sub-items -->
                                        <div v-for="(item, itemIndex) in group.items"
                                            :key="`sub-${groupIndex}-${itemIndex}`"
                                            class="flex items-center px-8 py-2.5 transition 
                                                    hover:bg-gray-50 dark:hover:bg-slate-700/50 
                                                    cursor-pointer border-l-2 border-gray-200 dark:border-gray-600 ml-6"
                                            :class="{ 'bg-blue-50 dark:bg-blue-900/20 border-blue-300 dark:border-blue-600': selectedIndex === (searchResults.length + getItemGlobalIndex(groupIndex, itemIndex)) }"
                                            @click="selectSearchResult(item)"
                                            @mouseenter="selectedIndex = searchResults.length + getItemGlobalIndex(groupIndex, itemIndex)">
                                            <div class="flex-shrink-0 mr-3">
                                                <div
                                                    class="w-6 h-6 rounded bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                                    <i class="text-gray-600 dark:text-gray-300 fa-solid text-xs"
                                                        :class="item.icon || 'fa-cog'"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-900 dark:text-gray-100"
                                                    v-html="highlightMatch(item.name || item.title)"></p>
                                                <p v-if="item.description"
                                                    class="text-xs text-gray-400 dark:text-gray-500 mt-0.5"
                                                    v-html="highlightMatch(item.description)"></p>
                                            </div>
                                            <div class="flex-shrink-0 ml-3">
                                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- No Results -->
                        <div v-else-if="searchQuery && !isSearching && searchResults.length === 0 && groupedResults.length === 0"
                            class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-4 text-sm font-medium text-gray-900 dark:text-gray-100">No results found</h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Try searching for something else or check your spelling.
                            </p>
                        </div>

                        <!-- Recent Searches (shown when no search query) -->
                        <div v-else-if="!searchQuery"
                            class="flex flex-col divide-y divide-gray-100 dark:divide-gray-700/50">
                            <div class="px-6 py-4 flex items-center justify-between">
                                <h2 class="text-xs font-semibold text-gray-500 dark:text-gray-400">Recent</h2>
                                <button v-if="recentSearches.length > 0" @click="clearRecentSearches"
                                    class="text-xs text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                    Clear all
                                </button>
                            </div>
                            <div v-if="recentSearches.length > 0" class="max-h-96 overflow-y-auto">
                                <div v-for="(item, index) in recentSearches" :key="index" class="flex items-center px-6 py-4 transition 
                                            hover:bg-gray-50 dark:hover:bg-slate-700/50 
                                            cursor-pointer" @click="selectRecentSearch(item)">
                                    <div class="flex-shrink-0 mr-3">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ item.title }}</p>
                                        <p v-if="item.category" class="text-sm text-gray-500 dark:text-gray-400">{{
                                            item.category }}</p>
                                    </div>
                                    <button @click.stop="removeRecentSearch(index)" class="ml-4 p-1 text-gray-400 hover:text-gray-500 
                                                   dark:text-gray-500 dark:hover:text-gray-400 
                                                   transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div v-else class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mt-4 text-sm font-medium text-gray-900 dark:text-gray-100">No recent searches
                                </h3>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Your recent searches will appear here.
                                </p>
                            </div>
                        </div>

                        <!-- Search Tips Footer -->
                        <div class="px-6 py-3 bg-gray-50 dark:bg-slate-700/30">
                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                <div class="flex items-center space-x-4">
                                    <span class="flex items-center">
                                        <kbd
                                            class="mr-1 inline-flex items-center rounded border border-gray-200 dark:border-gray-600 px-1">↵</kbd>
                                        to select
                                    </span>
                                    <span class="flex items-center">
                                        <kbd
                                            class="mr-1 inline-flex items-center rounded border border-gray-200 dark:border-gray-600 px-1">↑↓</kbd>
                                        to navigate
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </Teleport>
    </div>
</template>
  
<script setup>
import { ref, onMounted, onUnmounted, nextTick, computed, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useAppStore } from '@/stores/useAppStore'

const router = useRouter()
const appStore = useAppStore()
const isOpen = ref(false)
const searchQuery = ref('')
const searchInput = ref(null)
const modalContent = ref(null)
const isSearching = ref(false)
const searchResults = ref([])
const groupedResults = ref([])
const selectedIndex = ref(-1)
let searchTimeout = null

// Main menu data
const searchData = ref([
    {
        title: 'Dashboard',
        icon: 'fa-home',
        description: 'View and read the documentation',
        path: '/dashboard',
        slug: 'dashboard',
        hasSubMenu: false,
    },
    {
        title: 'Thread',
        icon: 'fa-stream',
        description: 'Manage thread data items',
        path: '/thread',
        slug: 'thread',
        hasSubMenu: false,
    },
    {
        title: 'Toolstring Coiled Tubing',
        icon: 'fa-screwdriver-wrench',
        description: 'Manage toolstring coiled tubing items',
        path: '/toolstring-coiled-tubing',
        slug: 'toolstring-coiled-tubing',
        hasSubMenu: true,
        subMenuKey: 'toolstringTypes'
    },
    {
        title: 'Wellstack',
        icon: 'fa-oil-well',
        description: 'Manage well data items',
        path: '/wellstack',
        slug: 'wellstack',
        hasSubMenu: true,
        subMenuKey: 'wellstackTypes'
    },
    {
        title: 'Job Tracker',
        icon: 'fa-chart-line',
        description: 'Track and manage job progress',
        path: '/job-tracker',
        slug: 'job-tracker',
        hasSubMenu: false,
    },
    {
        title: 'Nitrogen',
        icon: 'fa-flask',
        description: 'Manage nitrogen documents',
        path: '/nitrogen',
        slug: 'nitrogen',
        hasSubMenu: false,
    },
    {
        title: 'Coiled Tubing',
        icon: 'fa-circle',
        description: 'Manage coiled tubing documents',
        path: '/coiled-tubing',
        slug: 'coiled-tubing',
        hasSubMenu: false,
    },
    {
        title: 'Reporting',
        icon: 'fa-file-lines',
        description: 'Generate and view reports',
        path: '/reporting',
        slug: 'reporting',
        hasSubMenu: false,
    },
    {
        title: 'Users',
        icon: 'fa-user',
        description: 'Manage user accounts and permissions',
        path: '/users',
        slug: 'users',
        hasSubMenu: false,
    },
])

const recentSearches = ref([])

// Watch untuk pencarian real-time
watch(searchQuery, (newQuery) => {
    if (searchTimeout) {
        clearTimeout(searchTimeout)
    }

    if (newQuery.trim() === '') {
        searchResults.value = []
        groupedResults.value = []
        selectedIndex.value = -1
        return
    }

    isSearching.value = true

    searchTimeout = setTimeout(() => {
        performSearch(newQuery)
        isSearching.value = false
    }, 300)
})

const performSearch = (query) => {
    const lowercaseQuery = query.toLowerCase()

    // Search main pages
    searchResults.value = searchData.value.filter(item => {
        return (
            item.hasSubMenu === false && (
            item.title.toLowerCase().includes(lowercaseQuery) ||
            (item.description && item.description.toLowerCase().includes(lowercaseQuery)))
        )
    }).slice(0, 8)

    // Search sub-menu items
    groupedResults.value = []

    // Search dalam toolstringTypes
    if (appStore.toolstringTypes && appStore.toolstringTypes.length > 0) {
        const toolstringMatches = appStore.toolstringTypes.filter(type =>
            type.name?.toLowerCase().includes(lowercaseQuery) ||
            type.description?.toLowerCase().includes(lowercaseQuery)
        )

        if (toolstringMatches.length > 0) {
            groupedResults.value.push({
                parentTitle: 'Toolstring Coiled Tubing',
                parentIcon: 'fa-screwdriver-wrench',
                parentPath: '/toolstring-coiled-tubing',
                items: toolstringMatches.map(type => ({
                    ...type,
                    path: `/toolstring-coiled-tubing/${type.slug}/${type.id}`,
                    icon: 'fa-wrench',
                    category: 'Toolstring Type'
                }))
            })
        }
    }

    // Search dalam wellstackTypes
    if (appStore.wellstackTypes && appStore.wellstackTypes.length > 0) {
        const wellstackMatches = appStore.wellstackTypes.filter(type =>
            type.name?.toLowerCase().includes(lowercaseQuery) ||
            type.description?.toLowerCase().includes(lowercaseQuery)
        )

        if (wellstackMatches.length > 0) {
            groupedResults.value.push({
                parentTitle: 'Wellstack',
                parentIcon: 'fa-oil-well',
                parentPath: '/wellstack',
                items: wellstackMatches.map(type => ({
                    ...type,
                    path: `/wellstack/${type.slug}/${type.id}`,
                    icon: 'fa-layer-group',
                    category: 'Wellstack Type'
                }))
            })
        }
    }

    selectedIndex.value = (searchResults.value.length > 0 || groupedResults.value.length > 0) ? 0 : -1
}

const highlightMatch = (text) => {
    if (!searchQuery.value || !text) return text
    const regex = new RegExp(`(${searchQuery.value})`, 'gi')
    return text.replace(regex, '<mark class="bg-yellow-200 dark:bg-yellow-800 rounded px-1">$1</mark>')
}

const getTotalSubResults = () => {
    return groupedResults.value.reduce((total, group) => total + group.items.length, 0)
}

const getItemGlobalIndex = (groupIndex, itemIndex) => {
    let globalIndex = 0
    for (let i = 0; i < groupIndex; i++) {
        globalIndex += groupedResults.value[i].items.length
    }
    return globalIndex + itemIndex
}

const getAllSearchableItems = () => {
    const allItems = [...searchResults.value]
    groupedResults.value.forEach(group => {
        allItems.push(...group.items)
    })
    return allItems
}

const openSearchModal = () => {
    isOpen.value = true
    selectedIndex.value = -1
    nextTick(() => {
        searchInput.value?.focus()
    })
}

const closeSearchModal = () => {
    isOpen.value = false
    searchQuery.value = ''
    searchResults.value = []
    groupedResults.value = []
    selectedIndex.value = -1
    if (searchTimeout) {
        clearTimeout(searchTimeout)
    }
}

const selectSearchResult = async (item) => {
    addToRecentSearches(item)

    try {
        if (item.path) {
            await router.push(item.path)
        } else if (item.slug) {
            await router.push(`/docs/${item.slug}`)
        } else {
            console.warn('No path or slug found for:', item)
        }

        closeSearchModal()
    } catch (error) {
        console.error('Navigation error:', error)
        closeSearchModal()
    }
}

const selectRecentSearch = async (item) => {
    try {
        if (item.path) {
            await router.push(item.path)
            closeSearchModal()
        } else {
            searchQuery.value = item.title
            performSearch(item.title)
        }
    } catch (error) {
        console.error('Navigation error:', error)
        searchQuery.value = item.title
        performSearch(item.title)
    }
}

const addToRecentSearches = (item) => {
    const existingIndex = recentSearches.value.findIndex(recent => recent.title === (item.title || item.name))
    if (existingIndex > -1) {
        recentSearches.value.splice(existingIndex, 1)
    }

    recentSearches.value.unshift({
        title: item.title || item.name,
        category: item.category || item.description,
        path: item.path,
        timestamp: new Date().toISOString()
    })

    if (recentSearches.value.length > 5) {
        recentSearches.value.pop()
    }

    saveRecentSearches()
}

const saveRecentSearches = () => {
    try {
        localStorage.setItem('searchRecentItems', JSON.stringify(recentSearches.value))
    } catch (error) {
        console.warn('Could not save recent searches to localStorage:', error)
    }
}

const removeRecentSearch = (index) => {
    recentSearches.value.splice(index, 1)
    saveRecentSearches()
}

const clearRecentSearches = () => {
    recentSearches.value = []
    try {
        localStorage.removeItem('searchRecentItems')
    } catch (error) {
        console.warn('Could not clear recent searches from localStorage:', error)
    }
}

const handleSearchKeydown = (event) => {
    const totalItems = searchResults.value.length + getTotalSubResults()
    if (totalItems === 0) return

    switch (event.key) {
        case 'ArrowDown':
            event.preventDefault()
            selectedIndex.value = Math.min(selectedIndex.value + 1, totalItems - 1)
            break
        case 'ArrowUp':
            event.preventDefault()
            selectedIndex.value = Math.max(selectedIndex.value - 1, 0)
            break
        case 'Enter':
            event.preventDefault()
            if (selectedIndex.value >= 0 && selectedIndex.value < totalItems) {
                const allItems = getAllSearchableItems()
                selectSearchResult(allItems[selectedIndex.value])
            }
            break
    }
}

const handleKeydown = (e) => {
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
        e.preventDefault()
        openSearchModal()
    }
    if (e.key === 'Escape' && isOpen.value) {
        closeSearchModal()
    }
}

const handleClickOutside = (event) => {
    if (isOpen.value && modalContent.value && !modalContent.value.contains(event.target)) {
        closeSearchModal()
    }
}

const isMac = computed(() => {
    return navigator.platform.toUpperCase().indexOf('MAC') >= 0
})

const loadRecentSearches = () => {
    try {
        const saved = localStorage.getItem('searchRecentItems')
        if (saved) {
            const parsed = JSON.parse(saved)
            if (Array.isArray(parsed) && parsed.length > 0) {
                const thirtyDaysAgo = new Date()
                thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30)

                const validItems = parsed.filter(item => {
                    if (!item.timestamp) return true
                    return new Date(item.timestamp) > thirtyDaysAgo
                })

                recentSearches.value = validItems.slice(0, 5)
            }
        } else {
            recentSearches.value = []
        }
    } catch (error) {
        console.warn('Could not load recent searches from localStorage:', error)
        recentSearches.value = []
    }
}

onMounted(() => {
    document.addEventListener('mousedown', handleClickOutside)
    document.addEventListener('keydown', handleKeydown)
    loadRecentSearches()
})

onUnmounted(() => {
    document.removeEventListener('mousedown', handleClickOutside)
    document.removeEventListener('keydown', handleKeydown)
    if (searchTimeout) {
        clearTimeout(searchTimeout)
    }
})
</script>