<template>
    <head>
        <Title>Dashboard</Title>
    </head>
    <div class="min-h-screen bg-gradient-to-br rounded-md from-slate-900 via-blue-900 to-slate-800 relative">
        <!-- Subtle Pattern Background -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0"
                style="background-image: radial-gradient(circle at 25px 25px, rgba(255,255,255,.2) 2%, transparent 0%), radial-gradient(circle at 75px 75px, rgba(255,255,255,.1) 1%, transparent 0%); background-size: 100px 100px;">
            </div>
        </div>
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl animate-pulse"
                style="animation-delay: 2s;"></div>
        </div>

        <!-- Main Content -->
        <div class="relative p-6">
            <!-- Welcome Section -->
            <div class="mb-8 bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20 shadow-xl">
                <h1 class="text-3xl font-bold text-white mb-2">
                    Welcome back to CT COSL!
                </h1>
                <p class="text-blue-100 text-lg mb-4">Oil & Gas Operations Dashboard</p>

                <!-- Document Introduction -->
                <div class="bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-lg p-4 border border-white/10">
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-blue-500/30 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold mb-1">Technical Documentation Available</h3>
                            <p class="text-blue-100 text-sm mb-2">Access our comprehensive Coiled Tubing Surface Equipment
                                manual below. This document contains essential operational procedures, safety guidelines,
                                and technical specifications.</p>
                            <div class="flex flex-wrap gap-2 text-xs">
                                <span class="px-2 py-1 bg-white/10 rounded-full text-blue-200">Equipment Manual</span>
                                <span class="px-2 py-1 bg-white/10 rounded-full text-blue-200">Safety Procedures</span>
                                <span class="px-2 py-1 bg-white/10 rounded-full text-blue-200">Technical Specs</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PDF Viewer Container -->
            <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 shadow-xl overflow-hidden">
                <!-- PDF Controls -->
                <div class="bg-black/20 backdrop-blur-sm px-6 py-4 border-b border-white/10">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <!-- Navigation Controls -->
                        <div class="flex items-center gap-3">
                            <button @click="page = page > 1 ? page - 1 : page" :disabled="page <= 1"
                                class="flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 disabled:bg-white/5 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg border border-white/20 transition-all duration-200 hover:scale-105 active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                Previous
                            </button>

                            <div
                                class="px-4 py-2 bg-gradient-to-r from-blue-500/20 to-purple-500/20 text-white rounded-lg border border-white/20 font-medium">
                                {{ page }} / {{ pages }}
                            </div>

                            <button @click="page = page < pages ? page + 1 : page" :disabled="page >= pages"
                                class="flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 disabled:bg-white/5 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg border border-white/20 transition-all duration-200 hover:scale-105 active:scale-95">
                                Next
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>

                        <!-- Zoom Controls -->
                        <div class="flex items-center gap-3">
                            <button @click="zoomOut"
                                class="p-2 bg-white/10 hover:bg-white/20 text-white rounded-lg border border-white/20 transition-all duration-200 hover:scale-105 active:scale-95"
                                title="Zoom Out">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7" />
                                </svg>
                            </button>

                            <div
                                class="px-3 py-2 bg-white/10 text-white text-sm rounded-lg border border-white/20 min-w-[60px] text-center">
                                {{ Math.round(zoomLevel * 100) }}%
                            </div>

                            <button @click="zoomIn"
                                class="p-2 bg-white/10 hover:bg-white/20 text-white rounded-lg border border-white/20 transition-all duration-200 hover:scale-105 active:scale-95"
                                title="Zoom In">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                </svg>
                            </button>

                            <button @click="resetZoom"
                                class="px-3 py-2 bg-white/10 hover:bg-white/20 text-white text-sm rounded-lg border border-white/20 transition-all duration-200 hover:scale-105 active:scale-95"
                                title="Reset Zoom">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>

                <!-- PDF Content Area -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 min-h-[600px] flex items-center justify-center">
                    <div class="pdf-container transition-transform duration-300 ease-out"
                        :style="{ transform: `scale(${zoomLevel})`, transformOrigin: 'center top' }">
                        <div class="shadow-2xl rounded-lg overflow-hidden bg-white">
                            <VuePDF :pdf="pdf" :page="page" class="max-w-full h-auto" />
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div v-if="!pdf && isSupported"
                    class="bg-gradient-to-br from-gray-50 to-gray-100 p-12 min-h-[600px] flex flex-col items-center justify-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
                    <p class="text-gray-600 text-lg font-medium">Loading PDF...</p>
                    <p class="text-gray-500 text-sm mt-2">Please wait while we prepare the document</p>
                </div>

                <!-- Safari/Browser Compatibility Error -->
                <div v-if="!isSupported"
                    class="bg-gradient-to-br from-red-50 to-orange-50 p-12 min-h-[600px] flex flex-col items-center justify-center">
                    <div class="bg-red-100 p-4 rounded-full mb-6">
                        <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-red-800 mb-4">Browser Compatibility Issue</h3>
                    <p class="text-red-700 text-center max-w-md mb-6">{{ errorMessage }}</p>

                    <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 border border-red-200 max-w-lg">
                        <h4 class="font-semibold text-red-800 mb-3">🌐 Recommended Browsers:</h4>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="flex items-center gap-2 text-red-700">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                Google Chrome
                            </div>
                            <div class="flex items-center gap-2 text-red-700">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                Mozilla Firefox
                            </div>
                            <div class="flex items-center gap-2 text-red-700">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                Microsoft Edge
                            </div>
                            <div class="flex items-center gap-2 text-red-700">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                                Safari (Limited)
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-red-200">
                            <p class="text-xs text-red-600">
                                <strong>Alternative:</strong> You can download the PDF file directly using the link below.
                            </p>
                            <a :href="pdfUrl" target="_blank"
                                class="inline-flex items-center gap-2 mt-3 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Info & Instructions -->
            <div class="mt-6 space-y-4">
                <!-- Document Info -->
                <div class="bg-white/5 backdrop-blur-sm rounded-xl p-4 border border-white/10">
                    <div class="flex items-center justify-between text-sm text-blue-100 mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="font-medium">Coiled Tubing Surface Equipment Manual</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span>{{ pages }} pages total</span>
                            <span>•</span>
                            <span>Zoom: {{ Math.round(zoomLevel * 100) }}%</span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-white/10 rounded-full h-2 mb-2">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full transition-all duration-300"
                            :style="{ width: `${(page / pages) * 100}%` }"></div>
                    </div>
                    <p class="text-xs text-blue-200">Reading progress: {{ Math.round((page / pages) * 100) }}%</p>
                </div>

                <!-- Navigation Instructions -->
                <div
                    class="bg-gradient-to-r from-amber-500/10 to-orange-500/10 backdrop-blur-sm rounded-xl p-4 border border-amber-300/20">
                    <div class="flex items-start gap-3">
                        <div class="p-1.5 bg-amber-500/20 rounded-lg">
                            <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-amber-100 font-medium text-sm mb-2">How to Navigate</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs text-amber-200">
                                <div class="flex items-center gap-2">
                                    <kbd class="px-2 py-1 bg-black/20 rounded text-xs">←→</kbd>
                                    <span>Navigate pages</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <kbd class="px-2 py-1 bg-black/20 rounded text-xs">+ -</kbd>
                                    <span>Zoom in/out</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <kbd class="px-2 py-1 bg-black/20 rounded text-xs">0</kbd>
                                    <span>Reset zoom</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-4 h-4 bg-blue-500/30 rounded"></span>
                                    <span>Use toolbar controls</span>
                                </div>
                            </div>

                            <!-- Safari Warning -->
                            <div v-if="isSafari" class="mt-3 p-2 bg-yellow-500/20 border border-yellow-500/30 rounded-lg">
                                <p class="text-yellow-200 text-xs">
                                    ⚠️ <strong>Safari User:</strong> If you experience issues, please try Chrome or Firefox
                                    for optimal PDF viewing.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Document Sections (if you want to add chapter/section info) -->
                <div class="bg-white/5 backdrop-blur-sm rounded-xl p-4 border border-white/10">
                    <h4 class="text-white font-medium text-sm mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Document Sections
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-xs">
                        <div class="px-3 py-2 bg-white/5 rounded-lg border border-white/10">
                            <div class="text-blue-300 font-medium">Equipment Overview</div>
                            <div class="text-blue-200 opacity-75">Pages 1-15</div>
                        </div>
                        <div class="px-3 py-2 bg-white/5 rounded-lg border border-white/10">
                            <div class="text-blue-300 font-medium">Safety Procedures</div>
                            <div class="text-blue-200 opacity-75">Pages 16-30</div>
                        </div>
                        <div class="px-3 py-2 bg-white/5 rounded-lg border border-white/10">
                            <div class="text-blue-300 font-medium">Technical Specs</div>
                            <div class="text-blue-200 opacity-75">Pages 31+</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useCurrentUserStore } from '@/stores/CurrentUser'
import { VuePDF, usePDF } from '@tato30/vue-pdf'
import pdfUrl from '@/../../resources/assets/Coiled-Tubing-Surface-Equipment.pdf'

// Safari compatibility check
const isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent)
const isSupported = ref(true)
const errorMessage = ref('')

let pdfData = null
let pagesData = null

// Initialize PDF with error handling for Safari
try {
    const pdfResult = usePDF(pdfUrl)
    pdfData = pdfResult.pdf
    pagesData = pdfResult.pages
} catch (error) {
    console.error('PDF initialization error:', error)
    isSupported.value = false
    errorMessage.value = 'PDF viewer is not supported in this browser. Please use Chrome, Firefox, or Edge for the best experience.'
}

const pdf = pdfData
const pages = pagesData
const page = ref(1)
const zoomLevel = ref(1)
const currentUserStore = useCurrentUserStore()

// Zoom functions
const zoomIn = () => {
    if (zoomLevel.value < 3) {
        zoomLevel.value = Math.min(3, zoomLevel.value + 0.25)
    }
}

const zoomOut = () => {
    if (zoomLevel.value > 0.5) {
        zoomLevel.value = Math.max(0.5, zoomLevel.value - 0.25)
    }
}

const resetZoom = () => {
    zoomLevel.value = 1
}

// Keyboard navigation
const handleKeydown = (event) => {
    switch (event.key) {
        case 'ArrowLeft':
            if (page.value > 1) page.value--
            break
        case 'ArrowRight':
            if (page.value < pages.value) page.value++
            break
        case '+':
        case '=':
            event.preventDefault()
            zoomIn()
            break
        case '-':
            event.preventDefault()
            zoomOut()
            break
        case '0':
            event.preventDefault()
            resetZoom()
            break
    }
}

onMounted(async () => {
    if (!currentUserStore.user) {
        await currentUserStore.fetchUser()
    }

    // Add keyboard event listener
    document.addEventListener('keydown', handleKeydown)
})

// Cleanup event listener
onUnmounted(() => {
    document.removeEventListener('keydown', handleKeydown)
})
</script>
  
<style scoped>
.pdf-container {
    max-width: 100%;
    overflow: visible;
}

/* Custom scrollbar for PDF area */
.pdf-content::-webkit-scrollbar {
    width: 8px;
}

.pdf-content::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
}

.pdf-content::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 4px;
}

.pdf-content::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}

/* Smooth transitions for all interactive elements */
button {
    transition: all 0.2s ease;
}

button:hover:not(:disabled) {
    transform: translateY(-1px);
}

button:active:not(:disabled) {
    transform: translateY(0);
}
</style>