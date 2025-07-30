<template>
    <head>
        <Title>Operations Dashboard - Development Stage</Title>
    </head>
    <div class="min-h-screen bg-gradient-to-br rounded-md from-slate-900 via-blue-900 to-slate-800 relative">
        <!-- Subtle Pattern Background -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25px 25px, rgba(255,255,255,.2) 2%, transparent 0%), radial-gradient(circle at 75px 75px, rgba(255,255,255,.1) 1%, transparent 0%); background-size: 100px 100px;"></div>
        </div>
        
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative p-6">
            <!-- Development Stage Banner -->
            <div class="mb-6 bg-gradient-to-r from-orange-500/95 to-red-500/95 backdrop-blur-md rounded-xl border border-orange-300/50 shadow-2xl">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-white/20 rounded-full">
                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.78 0-2.678-2.153-1.415-3.414l5-5A2 2 0 009 9.586V5L8 4z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Development in Progress</h2>
                                <p class="text-orange-100">This dashboard is currently under active development</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-white">60%</div>
                            <div class="text-orange-200 text-sm">Complete</div>
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="mt-4">
                        <div class="flex justify-between text-sm text-orange-100 mb-2">
                            <span>Development Progress</span>
                            <span>60% Complete</span>
                        </div>
                        <div class="w-full bg-white/20 rounded-full h-3">
                            <div class="bg-gradient-to-r from-yellow-400 to-orange-300 h-3 rounded-full transition-all duration-1000 ease-out" 
                                 style="width: 40%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-orange-200 mt-2">
                            <span>Started</span>
                            <span class="text-yellow-300">Current Phase: Nitrogen Uploaded Document Master</span>
                            <span>Target Completion</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Welcome Section -->
            <div class="mb-8 bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20 shadow-xl">
                <h1 class="text-3xl font-bold text-white mb-2">
                    Welcome back, {{ currentUserStore?.user?.fullname || 'Developer' }}!
                </h1>
                <p class="text-blue-100 text-lg">Oil & Gas Operations Dashboard - Preview Version</p>
                <div class="mt-3 inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-500/30 text-blue-100 border border-blue-300/30">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    Beta Version - Features may change
                </div>
            </div>

    
            <!-- Development Status Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Completed Features -->
                <div class="bg-gradient-to-br from-green-500/20 to-emerald-600/20 backdrop-blur-md rounded-xl p-6 border border-green-400/40 shadow-xl hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-green-500/40 rounded-xl mr-3 shadow-lg">
                            <svg class="h-6 w-6 text-green-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-green-100">Completed Features</h3>
                    </div>
                    <ul class="space-y-3 text-sm text-green-200">
                        <li class="flex items-center">
                            <span class="mr-2 text-green-400">✅</span> 
                            <span>Basic Dashboard Layout</span>
                        </li>
                        <li class="flex items-center">
                            <span class="mr-2 text-green-400">✅</span> 
                            <span>Threads Master</span>
                        </li>
                        <li class="flex items-center">
                            <span class="mr-2 text-green-400">✅</span> 
                            <span>Responsive Grid System</span>
                        </li>
                        <li class="flex items-center">
                            <span class="mr-2 text-green-400">✅</span> 
                            <span>Toolstring Coiled Tubing Master + Reporting</span>
                        </li>
                        <li class="flex items-center">
                            <span class="mr-2 text-green-400">✅</span> 
                            <span>Well Stack Master + Reporting</span>
                        </li>
                        <li class="flex items-center">
                            <span class="mr-2 text-green-400">✅</span> 
                            <span>Job Tracker</span>
                        </li>
                    </ul>
                </div>

                <!-- In Progress -->
                <div class="bg-gradient-to-br from-orange-500/20 to-yellow-600/20 backdrop-blur-md rounded-xl p-6 border border-orange-400/40 shadow-xl hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-orange-500/40 rounded-xl mr-3 shadow-lg">
                            <svg class="h-6 w-6 text-orange-200 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-orange-100">In Progress</h3>
                    </div>
                    <ul class="space-y-3 text-sm text-orange-200">
                        <li class="flex items-center">
                            <span class="mr-2 text-orange-400 animate-pulse">🔄</span> 
                            <span>Nitrogen</span>
                        </li>
                        <li class="flex items-center">
                            <span class="mr-2 text-orange-400 animate-pulse">🔄</span> 
                            <span>Coiled Tubing</span>
                        </li>
                        <li class="flex items-center">
                            <span class="mr-2 text-orange-400 animate-pulse">🔄</span> 
                            <span>User Management (delete user)</span>
                        </li>
                        <li class="flex items-center">
                            <span class="mr-2 text-orange-400 animate-pulse">🔄</span> 
                            <span>Settings Dashboard & Settings User Profile</span>
                        </li>
                    </ul>
                </div>

                <!-- Coming Soon -->
                <div class="bg-gradient-to-br from-blue-500/20 to-indigo-600/20 backdrop-blur-md rounded-xl p-6 border border-blue-400/40 shadow-xl hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-blue-500/40 rounded-xl mr-3 shadow-lg">
                            <svg class="h-6 w-6 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-blue-100">Coming Soon</h3>
                    </div>
                    <ul class="space-y-3 text-sm text-blue-200">
                        <li class="flex items-center">
                            <span class="mr-2 text-blue-400">📋</span> 
                            <span>Detailed Reports & Advanced Reports</span>
                        </li>
                        <li class="flex items-center">
                            <span class="mr-2 text-blue-400">🎯</span> 
                            <span>Advanced Analytics</span>
                        </li>
                        <li class="flex items-center">
                            <span class="mr-2 text-blue-400">📱</span> 
                            <span>IoT Integeration Real Time Data</span>
                        </li>
                        <li class="flex items-center">
                            <span class="mr-2 text-blue-400">🔒</span> 
                            <span>Enhanced Security</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Footer Notice -->
            <div class="bg-slate-800/60 backdrop-blur-md rounded-xl p-6 border border-slate-600/50 text-center shadow-xl">
                <div class="flex items-center justify-center mb-3">
                    <div class="p-2 bg-yellow-500/20 rounded-lg mr-3">
                        <svg class="h-6 w-6 text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-yellow-200">Development Notice</h3>
                </div>
                <p class="text-slate-300">
                    This dashboard is in active development. Features and data shown are for demonstration purposes only and may not reflect actual operations.
                </p>
                <div class="mt-4 inline-flex items-center px-4 py-2 rounded-full text-sm bg-slate-700/50 text-slate-300 border border-slate-600/50">
                    <svg class="w-4 h-4 mr-2 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    Version 0.6.0 Beta - Last Updated: July 2025
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useCurrentUserStore } from '@/stores/CurrentUser'

const currentUserStore = useCurrentUserStore()

// Development stage data
const developmentProgress = ref({
    percentage: 40,
    phase: 'Core Features Development',
    completedFeatures: [
        'Basic Dashboard Layout',
        'KPI Cards Design', 
        'Responsive Grid System',
        'Dark Mode Support'
    ],
    inProgressFeatures: [
        'Real-time Data Integration',
        'Interactive Charts',
        'Alert System',
        'User Authentication'
    ],
    upcomingFeatures: [
        'Detailed Reports',
        'Advanced Analytics',
        'Mobile App',
        'Enhanced Security'
    ]
})

// Sample Data for Oil & Gas Operations (Mock data for development)
const operationalData = ref({
    dailyProduction: {
        current: 85234,
        change: 3.2,
        unit: 'BBL',
        isSimulated: true
    },
    wells: {
        active: 142,
        total: 156,
        operationalRate: 91,
        isSimulated: true
    },
    equipment: {
        uptime: 98.5,
        alerts: 3,
        isSimulated: true
    },
    safety: {
        daysWithoutIncident: 365,
        status: 'Perfect',
        isSimulated: true
    }
})

onMounted(async () => {
    if (!currentUserStore.user) {
        await currentUserStore.fetchUser()
    }
    
    // Simulate development progress animation
    setTimeout(() => {
        console.log('Dashboard loaded in development mode')
    }, 1000)
})
</script>