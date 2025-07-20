<template>
    <head>
        <Title>Operations Dashboard</Title>
    </head>
    <div class="p-6 bg-gray-50 dark:bg-slate-900/50 dark:text-gray-100 rounded-xl transition-colors duration-200">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-blue-50">Welcome back, {{ currentUserStore?.user?.fullname }}!</h1>
            <p class="text-gray-600 dark:text-gray-400">Oil & Gas Operations Overview</p>
        </div>

        <!-- Key Performance Indicators -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Daily Production -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 dark:bg-slate-800/50 dark:shadow-slate-900/50 dark:border-slate-700/50 backdrop-blur-xl transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-blue-50">Daily Production</p>
                        <h3 class="text-2xl font-bold text-gray-700 dark:text-white">85,234 BBL</h3>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-green-600 text-sm flex items-center">
                    <span class="text-green-500 mr-1">↑</span> 3.2% from yesterday
                </p>
            </div>

            <!-- Active Wells -->
            <div class="bg-white dark:bg-slate-800/50 rounded-xl shadow-md dark:shadow-slate-900/50 p-6 border border-gray-50 dark:border-slate-700/50 backdrop-blur-xl transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-blue-50">Active Wells</p>
                        <h3 class="text-2xl font-bold text-gray-700 dark:text-white">142/156</h3>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-green-600 text-sm flex items-center">
                    91% Operational Rate
                </p>
            </div>

            <!-- Equipment Status -->
            <div class="bg-white dark:bg-slate-800/50 rounded-xl shadow-md dark:shadow-slate-900/50 p-6 border border-gray-100 dark:border-slate-700/50 backdrop-blur-xl transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-blue-50">Equipment Status</p>
                        <h3 class="text-2xl font-bold text-gray-700 dark:text-white">98.5%</h3>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-yellow-600 text-sm flex items-center">
                    3 Maintenance Alerts
                </p>
            </div>

            <!-- Safety Metrics -->
            <div class="bg-white dark:bg-slate-800/50 rounded-xl shadow-md dark:shadow-slate-900/50 p-6 border border-gray-100 dark:border-slate-700/50 backdrop-blur-xl transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-blue-50">Days Without Incident</p>
                        <h3 class="text-2xl font-bold text-gray-700 dark:text-white">365</h3>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full">
                        <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-purple-600 text-sm flex items-center">
                    Perfect Safety Record
                </p>
            </div>
        </div>

        <!-- Additional Dashboard Content -->
        <!-- You can add charts, tables, or other components here -->
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useCurrentUserStore } from '@/stores/CurrentUser'

const currentUserStore = useCurrentUserStore()

// Sample Data for Oil & Gas Operations
const operationalData = ref({
    dailyProduction: {
        current: 85234,
        change: 3.2,
        unit: 'BBL'
    },
    wells: {
        active: 142,
        total: 156,
        operationalRate: 91
    },
    equipment: {
        uptime: 98.5,
        alerts: 3
    },
    safety: {
        daysWithoutIncident: 365,
        status: 'Perfect'
    }
})

onMounted(async () => {
    if (!currentUserStore.user) {
        await currentUserStore.fetchUser()
    }
    // Add any additional initialization logic here
})
</script>