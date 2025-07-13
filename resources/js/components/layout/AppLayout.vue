<template>
    <div>
        <div v-if="loading"
            class="fixed inset-0 z-50 flex items-center justify-center bg-white/80 dark:bg-slate-800 backdrop-blur-sm">
            <svg class="animate-spin h-12 w-12 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                </path>
            </svg>
        </div>
        <TypeForm />
        <div v-if="!loading" class="h-screen flex overflow-hidden bg-white dark:bg-slate-800 dark:text-white">
            <!-- Sidebar backdrop -->
            <Transition enter-active-class="transition-opacity ease-out duration-300" enter-from-class="opacity-0"
                enter-to-class="opacity-100" leave-active-class="transition-opacity ease-in duration-200"
                leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="isMobileSidebarOpen" @click="closeMobileSidebar"
                    class="fixed inset-0 z-30 bg-gray-900/50 backdrop-blur-sm md:hidden"></div>
            </Transition>
            <!-- Sidebar -->
            <aside class="h-screen flex flex-col" :class="[
                'fixed inset-y-0 left-0 z-40 transition-all duration-300 ease-in-out transform',
                'dark:bg-slate-800 dark:text-white shadow-2xl bg-white',
                isMobileSidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0',
                isSidebarCollapsed ? 'w-20' : 'w-70'
            ]">
                <!-- Sidebar Header -->
                <div
                    class="relative h-30 flex items-center justify-between px-3 border-b border-gray-200 dark:border-white/10">
                    <button @click="toggleSidebar"
                        class="absolute top-1/2 right-[-20px] hidden md:flex z-50 transform -translate-y-1/2 w-10 h-10 items-center justify-center rounded-full bg-white dark:bg-slate-600 text-blue-600 dark:text-blue-500 dark:text- shadow-md hover:bg-blue-100 dark:hover:bg-slate-400 transition-all duration-200">
                        <svg :class="[isSidebarCollapsed ? 'rotate-180' : '']" class="w-4 h-4 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <div class="flex items-center space-x-3" :class="[isSidebarCollapsed ? '' : 'p-3']">
                        <div class="relative">
                            <div class="absolute inset-0 bg-blue-500/20 blur-xl rounded-lg"></div>
                            <img class="relative object-contain" :class="[isSidebarCollapsed ? 'w-[100px]' : 'w-12']"
                                :src="imgSrc" alt="COSL logo">
                        </div>
                        <Transition enter-active-class="transition-all duration-300 ease-out"
                            enter-from-class="opacity-0 -translate-x-4" enter-to-class="opacity-100 translate-x-0"
                            leave-active-class="transition-all duration-200 ease-in"
                            leave-from-class="opacity-100 translate-x-0" leave-to-class="opacity-0 -translate-x-4">
                            <div v-show="!isSidebarCollapsed" class="flex flex-col">
                                <span class="text-lg font-semibold text-gray-800 dark:text-white">COSL Coiled Tubing</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Management System</span>
                            </div>
                        </Transition>
                    </div>
                </div>
                <!-- Sidebar Content -->
                <div class="flex flex-col flex-1  overflow-y-auto mt-3 h-screen">
                    <!-- Navigation -->
                    <nav class="space-y-2 flex-1 overflow-y-auto pb-20">
                        <div v-for="(item, index) in sidebarItems" :key="index">
                            <RouterLink v-if="item.name !== 'Toolstring Coiled Tubing' && item.name !== 'Wellstack'"
                                :to="item.path" class="flex items-center justify-between w-full px-4 py-2.5 rounded-xl font-medium group
    hover:bg-blue-500 hover:text-white
    dark:hover:bg-white/10 dark:hover:text-blue-500
    transition-colors duration-200" :class="isActive(item.path)
        ? 'bg-blue-500 text-white dark:bg-white/10 dark:text-blue-500'
        : 'text-gray-700 dark:text-white'">
                                <div class="flex items-center">
                                    <i class="mx-3 fa-solid transition-colors duration-200" :class="[
                                        item.icon,
                                        isActive(item.path)
                                            ? 'text-white dark:text-blue-500'
                                            : 'group-hover:text-white dark:group-hover:text-blue-500'
                                    ]"></i>
                                    <span v-show="!isSidebarCollapsed">{{ item.name }}</span>
                                </div>
                            </RouterLink>
                            <button v-else @click="toggleDropdown(index)"
                                @contextmenu.prevent="(item.name === 'Toolstring Coiled Tubing' || item.name === 'Wellstack') && openContextMenu($event, true, false, false, null, item)"
                                :to="item.path" class="flex items-center justify-between w-full px-4 py-2.5 rounded-xl font-medium group
    hover:bg-blue-500 hover:text-white
    dark:hover:bg-white/10 dark:hover:text-blue-500
    transition-colors duration-200" :class="isActive(item.path)
        ? 'bg-blue-500 text-white dark:bg-white/10 dark:text-blue-500'
        : 'text-gray-700 dark:text-white'">
                                <div class="flex items-center">
                                    <i class="mx-3 fa-solid transition-colors duration-200" :class="[
                                        item.icon,
                                        isActive(item.path)
                                            ? 'text-white dark:text-blue-500'
                                            : 'group-hover:text-white dark:group-hover:text-blue-500'
                                    ]"></i>
                                    <span v-show="!isSidebarCollapsed">{{ item.name }}</span>
                                </div>
                                <i v-if="item.children && !isSidebarCollapsed"
                                    class="fas fa-chevron-right transition-transform duration-200"
                                    :class="{ 'rotate-90': dropdownOpen[index] }"></i>
                            </button>
                            <transition name="fade">
                                <div v-if="item.children && dropdownOpen[index] && !isSidebarCollapsed"
                                    class="ml-8 mt-1 space-y-1">
                                    <RouterLink v-for="(child, cIdx) in item.children" :key="cIdx" :to="child.path"
                                        @click="isMobileSidebarOpen = false"
                                        @contextmenu.prevent="openContextMenu($event, false, true, true, child)" class="flex items-center px-3 py-2 rounded-lg text-sm
              hover:bg-blue-500 hover:text-white
              dark:hover:bg-white/10 dark:hover:text-blue-500
              transition-colors duration-200" :class="isActive(child.path)
                  ? 'bg-blue-500 text-white dark:bg-white/10 dark:text-blue-500'
                  : 'text-gray-600 dark:text-gray-300'">
                                        <i class="mr-2 fa-solid" :class="child.icon"></i>
                                        <span>{{ child.name }}</span>
                                    </RouterLink>
                                </div>
                            </transition>
                        </div>
                        <!-- Custom Context Menu -->
                        <div v-if="contextMenu.visible" :style="{ top: `${contextMenu.y}px`, left: `${contextMenu.x}px` }"
                            class="fixed z-50 min-w-[200px] animate-fadeIn">
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <!-- Header -->
                                <div
                                    class="px-4 py-2 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-700">
                                    <h4 class="text-gray-700 dark:text-gray-200">Quick Actions</h4>
                                </div>
                                <!-- Menu Items -->
                                <div class="p-2">
                                    <!-- Add type Button -->
                                    <button v-if="showAddType" @click="handleAddType"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-left text-sm transition-colors duration-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700">
                                        <span
                                            class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30">
                                            <i class="fas fa-plus text-blue-600 dark:text-blue-400"></i>
                                        </span>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-200">Add
                                                type</span>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Create a new type
                                            </p>
                                        </div>
                                    </button>
                                    <!-- Rename Button -->
                                    <button v-if="showRenameType" @click="handleRenameType"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-left text-sm transition-colors duration-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700">
                                        <span
                                            class="flex items-center justify-center w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30">
                                            <i class="fas fa-edit text-green-600 dark:text-green-400"></i>
                                        </span>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-200">Edit</span>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Rename type
                                            </p>
                                        </div>
                                    </button>
                                    <!-- Delete Button -->
                                    <button v-if="showDeleteType" @click="handleDeleteType"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-left text-sm transition-colors duration-200 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30">
                                        <span
                                            class="flex items-center justify-center w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/30">
                                            <i class="fas fa-trash text-red-600 dark:text-red-400"></i>
                                        </span>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-200">Delete</span>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Remove permanently</p>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </aside>
            <!-- Main Content -->
            <div
                :class="['flex-1 flex flex-col overflow-hidden transition-all duration-300', isSidebarCollapsed ? 'md:ml-20' : 'md:ml-70']">
                <!-- Header & Page Content (unchanged) -->
                <header
                    class="relative h-[80px] bg-white/80 dark:bg-slate-800/50 dark:text-white shadow-sm flex items-center justify-between px-6 z-10">
                    <div class="flex items-center space-x-4">
                        <button @click="toggleMobileSidebar"
                            class="md:hidden p-2 rounded-md hover:bg-gray-100 dark:hover:bg-slate-500 transition">
                            <svg class="w-6 h-6 text-gray-600 dark:text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <!-- <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Dashboard</h1> -->
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <div class="relative h-[80px] flex items-center group">
                            <SearchButton />
                        </div>
                        <div class="relative h-[80px] flex items-center dropdown-notification-wrapper"
                            @mouseenter="openNotificationDropdown" @mouseleave="isNotificationDropdownOpen = false">
                            <!-- Tombol -->
                            <button @click.stop="toggleNotificationDropdown"
                                class="relative group p-2.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-500 transition-all duration-200">
                                <svg class="w-6 h-6 text-gray-600 dark:text-white hover:text-gray-800 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.4-1.4a2 2 0 01-.6-1.42V11a6 6 0 00-4-5.66V5a2 2 0 10-4 0v.34A6 6 0 006 11v3.18c0 .53-.2 1.05-.6 1.42L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                                </span>
                            </button>
                            <!-- Dropdown -->
                            <div v-show="isNotificationDropdownOpen"
                                class="absolute -right-25 md:-right-4 top-full w-80 bg-white z-50 dark:bg-slate-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 transition-all duration-200">
                                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Notifications</h3>
                                    <button class="text-xs text-blue-600 hover:text-blue-700 font-medium">Mark all as
                                        read</button>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <div
                                        class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors cursor-pointer border-b border-gray-50 dark:border-gray-700">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-800 dark:text-white">Your order has been
                                                    confirmed
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1 dark:text-gray-500">2 minutes ago</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors cursor-pointer">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0"><img
                                                    src="https://api.dicebear.com/7.x/avataaars/svg?seed=Jane"
                                                    class="w-10 h-10 rounded-full" alt="User"></div>
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-800 dark:text-white"><span
                                                        class="font-medium">Jane
                                                        Cooper</span>
                                                    mentioned you in a comment</p>
                                                <p class="text-xs text-gray-500 mt-1 dark:text-gray-500">1 hour ago</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-4 py-3 bg-gray-50 dark:bg-slate-700 text-center rounded-b-xl">
                                    <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View all
                                        notifications</a>
                                </div>
                            </div>
                        </div>
                        <div class="relative h-[80px] flex items-center">
                            <ToggleDarkMode />
                        </div>
                        <div class="relative h-[80px] ms-3 flex items-center dropdown-notification-wrapper"
                            @mouseenter="openProfileDropdown" @mouseleave="isProfileDropdownOpen = false">
                            <button @click.stop="toggleProfileDropdown" class="relative">
                                <div class="relative">
                                    <svg width="100" height="100"
                                        class="w-8 h-8 rounded-full ring-2 ring-gray-200 group-hover:ring-orange-300 transition-all duration-200"
                                        viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="50" cy="50" r="48" fill="#F3F4F6" stroke="#E5E7EB" stroke-width="4" />
                                        <circle cx="50" cy="38" r="14" fill="#D1D5DB" />
                                        <path
                                            d="M24 78C24 65.2975 35.2975 56 48 56H52C64.7025 56 76 65.2975 76 78V80H24V78Z"
                                            fill="#D1D5DB" />
                                    </svg>
                                    <div
                                        class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-green-500 rounded-full border-2 border-white">
                                    </div>
                                </div>
                            </button>
                            <div v-show="isProfileDropdownOpen"
                                class="absolute -right-4 top-full w-50 md:w-50 bg-white dark:bg-slate-800 dark:border-gray-700 rounded-2xl shadow-xl border border-gray-100 z-50 transition-all duration-200">
                                <div class="px-4 py-4 border-b border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <svg width="100" height="100" class="h-8 w-8 rounded-full object-cover"
                                            viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="50" cy="50" r="48" fill="#F3F4F6" stroke="#E5E7EB"
                                                stroke-width="4" />
                                            <circle cx="50" cy="38" r="14" fill="#D1D5DB" />
                                            <path
                                                d="M24 78C24 65.2975 35.2975 56 48 56H52C64.7025 56 76 65.2975 76 78V80H24V78Z"
                                                fill="#D1D5DB" />
                                        </svg>
                                        <div class="max-w-30 md:max-w-35">
                                            <p class="text-sm font-semibold text-gray-800 dark:text-white truncate">
                                                {{ currentUserStore?.user?.fullname }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ currentUserStore?.user?.email }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-2">
                                    <a href="#"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-white dark:hover:bg-gray-600 dark:hover:text-white hover:bg-gray-50 hover:text-blue-600 transition-colors"><svg
                                            class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>My Profile</a>
                                    <a href="#"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-white dark:hover:bg-gray-600 dark:hover:text-white hover:bg-gray-50 hover:text-blue-600 transition-colors"><svg
                                            class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>Settings</a>
                                    <a href="#"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-white dark:hover:bg-gray-600 dark:hover:text-white hover:bg-gray-50 hover:text-blue-600 transition-colors"><svg
                                            class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8.228 11.685h.774a2 2 0 001.94-1.515L12 5.5h0l1.058 4.67a2 2 0 001.94 1.515h.774m-7.544 0H5a2 2 0 00-2 2v5a2 2 0 002 2h14a2 2 0 002-2v-5a2 2 0 00-2-2h-3.228M10 17v-6m4 6v-6" />
                                        </svg>Billing</a>
                                </div>
                                <div class="border-t border-gray-100"></div>
                                <div class="py-2">
                                    <button @click="logout"
                                        class="flex items-center w-full px-4 py-2.5 text-sm text-red-600 dark:hover:bg-red-200 hover:bg-red-50 transition-colors"><svg
                                            class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>Sign out</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <main class="flex-1 overflow-y-auto overflow-x-hidden">
                    <div class="p-4 sm:p-6">
                        <slot />
                    </div>
                </main>
                <footer class="bg-white dark:bg-slate-800 dark:text-white border-t dark:border-gray-500 border-gray-200">
                    <div class="mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2 text-gray-500 text-sm">
                                <span>Admin Panel Version 1.0.0</span>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</template>
<script setup>
/* ==================== IMPORTS ==================== */
import { ref, onMounted, onUnmounted, watch, computed } from 'vue';
import { useRoute } from 'vue-router';
import { useToast } from 'vue-toastification';

import SearchButton from '../modals/SearchButton.vue';
import ToggleDarkMode from '../buttons/DarkModeToggle.vue';
import TypeForm from '../modals/TypeForm.vue';

import { useCurrentUserStore } from '@/stores/CurrentUser';
import { useAppStore } from '@/stores/useAppStore';


/* ==================== STORES & LIBS ==================== */
const appStore = useAppStore();
const currentUserStore = useCurrentUserStore();
const toast = useToast();
const route = useRoute();


/* ==================== CONSTANTS & STATIC ==================== */
const baseUrl = document.querySelector('meta[name="base-url"]').content;
const imgSrc = `${baseUrl}/assets/images/company/company-logo.png`;


/* ==================== STATE ==================== */
const loading = ref(true);
const isLoggingOut = ref(false);

const isSidebarCollapsed = ref(false);
const isMobileSidebarOpen = ref(false);

const isNotificationDropdownOpen = ref(false);
const isProfileDropdownOpen = ref(false);
const showNotification = ref(false);

const dropdownOpen = ref({});

const contextMenu = ref({
    visible: false,
    x: 0,
    y: 0,
});

const showAddType = ref(false);
const showRenameType = ref(false);
const showDeleteType = ref(false);


/* ==================== COMPUTED ==================== */
const isActive = (path) => route.path.startsWith(path);

const sidebarItems = computed(() => [
    { name: 'Dashboard', path: '/dashboard', icon: 'fa-home' },
    { name: 'Thread', path: '/thread', icon: 'fa-stream' },
    {
        name: 'Toolstring Coiled Tubing',
        path: '/toolstring-coiled-tubing',
        icon: 'fa-screwdriver-wrench',
        children: appStore.toolstringTypes.map(type => ({
            id: type.id,
            name: type.name,
            path: `/toolstring-coiled-tubing/${type.slug}/${type.id}`,
            icon: 'fa-folder',
        })),
    },
    {
        name: 'Wellstack',
        path: '/wellstack',
        icon: 'fa-oil-well',
        children: appStore.wellstackTypes.map(type => ({
            id: type.id,
            name: type.name,
            path: `/wellstack/${type.slug}/${type.id}`,
            icon: 'fa-folder',
        })),
    },
    { name: 'Nitrogen', path: '/nitrogen', icon: 'fa-flask' },
    { name: 'Coiled Tubing', path: '/coiled-tubing', icon: 'fa-circle' },
    { name: 'Reporting', path: '/reporting/toolstring-coiled-tubing', icon: 'fa-file-lines' },
    { name: 'Users', path: '/users', icon: 'fa-user' },
    { name: 'Settings', path: '/settings', icon: 'fa-gear' },
]);


/* ==================== WATCHERS ==================== */
watch(showNotification, (val) => {
    if (val) {
        setTimeout(() => {
            showNotification.value = false;
        }, 3000);
    }
});


/* ==================== LIFECYCLE HOOKS ==================== */
onMounted(async () => {
    if (!currentUserStore.user) {
        await currentUserStore.fetchUser();
    }
    await appStore.getToolstringTypes();
    await appStore.getWellstackTypes();
    window.addEventListener('click', closeNotificationDropdown);
    window.addEventListener('click', closeProfileDropdown);

    loading.value = false;
});

onUnmounted(() => {
    window.removeEventListener('click', closeNotificationDropdown);
    window.removeEventListener('click', closeProfileDropdown);
});


/* ==================== DROPDOWN HANDLERS ==================== */
function openNotificationDropdown() {
    isNotificationDropdownOpen.value = true;
}

function openProfileDropdown() {
    isProfileDropdownOpen.value = true;
}

function closeNotificationDropdown(e) {
    if (!e.target.closest('.dropdown-notification-wrapper')) {
        isNotificationDropdownOpen.value = false;
    }
}

function closeProfileDropdown(e) {
    if (!e.target.closest('.dropdown-profile-wrapper')) {
        isProfileDropdownOpen.value = false;
    }
}

function toggleNotificationDropdown() {
    isNotificationDropdownOpen.value = !isNotificationDropdownOpen.value;
    isProfileDropdownOpen.value = false;
}

function toggleProfileDropdown() {
    isProfileDropdownOpen.value = !isProfileDropdownOpen.value;
    isNotificationDropdownOpen.value = false;
}


/* ==================== SIDEBAR HANDLERS ==================== */
function toggleSidebar() {
    isSidebarCollapsed.value = !isSidebarCollapsed.value;
}

function toggleMobileSidebar() {
    isMobileSidebarOpen.value = true;
    isSidebarCollapsed.value = false;
    isProfileDropdownOpen.value = false;
    isNotificationDropdownOpen.value = false;
}

function closeMobileSidebar() {
    isMobileSidebarOpen.value = false;
}


/* ==================== TYPE MENU HANDLERS ==================== */
function toggleDropdown(index) {
    dropdownOpen.value[index] = !dropdownOpen.value[index];
    appStore.getToolstringTypes();
}

function openContextMenu(event, addType = false, renameType = false, deleteType = false, selectedType = null, selectedDropdownMenu = null) {
    contextMenu.value = {
        visible: true,
        x: event.clientX,
        y: event.clientY,
    };

    document.addEventListener('click', closeContextMenu);

    showAddType.value = addType;
    showRenameType.value = renameType;
    showDeleteType.value = deleteType;

    appStore.selectedTypeData = selectedType ?? null;
    appStore.selectedDropdownMenu = selectedDropdownMenu ?? null;
}

function closeContextMenu() {
    contextMenu.value.visible = false;
    document.removeEventListener('click', closeContextMenu);
    appStore.selectedTypeData = null;
}

function handleAddType() {
    appStore.isTypeModalOpen = true;
    appStore.typeFormAction = 'create';
    closeContextMenu();
}

function handleRenameType() {
    appStore.isTypeModalOpen = true;
    appStore.typeFormAction = 'update';
    closeContextMenu();
}

function handleDeleteType() {
    appStore.isTypeModalOpen = true;
    appStore.typeFormAction = 'delete';
    closeContextMenu();
}


/* ==================== LOGOUT ==================== */
async function logout() {
    try {
        if (isLoggingOut.value) return;
        isLoggingOut.value = true;

        await axios.post(`${baseUrl}/api/logout`);
        currentUserStore.user = null;
        appStore.toolstringTypes = [];
        appStore.selectedTypeData = null;

        toast.success('Logged out successfully!');
        window.location.href = `${baseUrl}/login`;
    } catch (err) {
        console.error('Logout error:', err);
        toast.error('Failed to log out. Please try again.');
    } finally {
        isLoggingOut.value = false;
    }
}
</script>
<style>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-5px);
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }

    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.2s ease-out;
}
</style>