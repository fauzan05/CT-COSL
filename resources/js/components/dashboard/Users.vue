<template>
    <head>
        <Title>User</Title>
    </head>
    <!-- modal create/update user -->
    <TransitionRoot appear :show="isUserModalOpen" as="template">
        <Dialog as="div" @close="closeModal" class="relative z-50">
            <!-- Background overlay -->
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
                            <!-- Header Section -->
                            <div class="flex justify-between items-center mb-6">
                                <DialogTitle as="h3" class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Create New User
                                </DialogTitle>
                                <button @click="closeModal"
                                    class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors duration-200">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <form @submit.prevent="createUser" class="space-y-6">
                                <!-- User Information Section -->
                                <div class="space-y-4">
                                    <!-- Full Name -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                            Full Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" v-model="userForm.fullname" placeholder="Enter full name"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            required>
                                    </div>

                                    <!-- Username -->
                                    <div v-if="userForm.id == 0">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                            Username <span class="text-red-500">*</span>
                                        </label>
                                        <div class="flex space-x-2">
                                            <input type="text" v-model="userForm.username" placeholder="Enter username"
                                                class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                :required="userForm.id == 0">
                                            <button type="button" @click="generateUsername()"
                                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 flex items-center"
                                                :disabled="generatingUsername">
                                                <svg v-if="!generatingUsername" class="w-4 h-4 mr-2" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                <svg v-else class="animate-spin w-4 h-4 mr-2" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                        stroke-width="4" />
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                                </svg>
                                                {{ generatingUsername ? 'Generating...' : 'Generate' }}
                                            </button>
                                        </div>

                                        <!-- Username Recommendations -->
                                        <div v-if="usernameRecommendations.length > 0"
                                            class="mt-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                                Username Recommendations:
                                            </h4>
                                            <div class="flex flex-wrap gap-2">
                                                <button v-for="(recommendation, index) in usernameRecommendations"
                                                    :key="index" type="button"
                                                    @click="selectUsername(recommendation.username)"
                                                    class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-md text-sm transition-colors duration-200 group">
                                                    <span class="mr-2">{{ recommendation.username }}</span>
                                                    <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                Click on any recommendation to use it
                                            </div>
                                        </div>

                                        <!-- Username Availability Status -->
                                        <div v-if="usernameStatus" class="mt-2 flex items-center text-sm">
                                            <svg v-if="usernameStatus === 'available'" class="w-4 h-4 mr-1 text-green-500"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            <svg v-else-if="usernameStatus === 'taken'" class="w-4 h-4 mr-1 text-red-500"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            <svg v-else class="animate-spin w-4 h-4 mr-1 text-gray-500" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4" />
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                            </svg>
                                            <span :class="{
                                                'text-green-600 dark:text-green-400': usernameStatus === 'available',
                                                'text-red-600 dark:text-red-400': usernameStatus === 'taken',
                                                'text-gray-500 dark:text-gray-400': usernameStatus === 'checking'
                                            }">
                                                {{ usernameStatusMessage }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" v-model="userForm.email" placeholder="Enter email address"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            required>
                                        <!-- Username Availability Status -->
                                        <div v-if="emailStatus" class="mt-2 flex items-center text-sm">
                                            <svg v-if="emailStatus === 'available'" class="w-4 h-4 mr-1 text-green-500"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            <svg v-else-if="emailStatus === 'taken'" class="w-4 h-4 mr-1 text-red-500"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            <svg v-else class="animate-spin w-4 h-4 mr-1 text-gray-500" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4" />
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                            </svg>
                                            <span :class="{
                                                'text-green-600 dark:text-green-400': emailStatus === 'available',
                                                'text-red-600 dark:text-red-400': emailStatus === 'taken',
                                                'text-gray-500 dark:text-gray-400': emailStatus === 'checking'
                                            }">
                                                {{ emailStatusMessage }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Update Password Access -->
                                    <div v-if="selectedUser" class="flex items-center mt-7">
                                        <Switch v-model="userForm.is_update_password" :class="[
                                            userForm.is_update_password ? 'bg-blue-600 dark:bg-blue-500' : 'bg-gray-200 dark:bg-gray-700',
                                            'relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2'
                                        ]">
                                            <span class="sr-only">Update Password Access</span>
                                            <span :class="[
                                                userForm.is_update_password ? 'translate-x-6' : 'translate-x-1',
                                                'inline-block h-4 w-4 transform rounded-full bg-white transition-transform'
                                            ]" />
                                        </Switch>
                                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-200">
                                            Update Password
                                        </span>
                                    </div>

                                    <!-- Password -->
                                    <PasswordInput v-if="!selectedUser || userForm.is_update_password"
                                        v-model="userForm.password"
                                        @passwordValidityChange="handlePasswordValidityChange" />

                                    <!-- Download Access -->
                                    <div class="flex items-center mt-7">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" v-model="userForm.download_access" id="download_access"
                                                class="sr-only peer">
                                            <div
                                                class="relative w-5 h-5 border border-gray-300 rounded transition-all dark:border-gray-600 peer-checked:border-blue-600 peer-checked:bg-blue-600">
                                                <!-- Checkmark icon -->
                                                <svg class="absolute w-3.5 h-3.5 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 stroke-white"
                                                    :class="userForm.download_access ? 'opacity-100' : 'opacity-0'"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-200">Download
                                                Access</span>
                                        </label>
                                    </div>

                                    <!-- Modification Job Tracker Master Access -->
                                    <div class="flex items-center mt-7">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" v-model="userForm.modification_job_tracker_master_access"
                                                id="download_access" class="sr-only peer">
                                            <div
                                                class="relative w-5 h-5 border border-gray-300 rounded transition-all dark:border-gray-600 peer-checked:border-blue-600 peer-checked:bg-blue-600">
                                                <!-- Checkmark icon -->
                                                <svg class="absolute w-3.5 h-3.5 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 stroke-white"
                                                    :class="userForm.modification_job_tracker_master_access ? 'opacity-100' : 'opacity-0'"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span
                                                class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-200">Modification
                                                Job Tracker Master Access</span>
                                        </label>
                                    </div>
                                    <!-- Info to user if created an user, it will be send an authentication via email -->
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                        <p>
                                            Note: If you click the "Create User" button, the user will receive an email with
                                            their credentials and a link to login.
                                        </p>
                                    </div>
                                </div>

                                <!-- Footer Actions -->
                                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <button type="button"
                                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200"
                                        @click="closeModal">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
                                        :disabled="createUserloading">
                                        <span v-if="!createUserloading">Create User</span>
                                        <span v-else class="flex items-center">
                                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4" />
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                            </svg>
                                            Creating...
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
                                    Are you sure you want to delete the user
                                    <span class="font-semibold text-red-600">{{ selectedUser?.type }}</span>?
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
                                <button type="button" :disabled="isDeleting" @click="handleDeleteUser"
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
    <!-- Main Content -->
    <div class="p-6 bg-gray-50 min-h-screen dark:bg-slate-900/50 dark:text-gray-100 rounded-xl">
        <!-- Header Section with improved styling -->
        <div class="mb-8 bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">User Management</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your user and set previlleges</p>
                </div>
                <button v-if="currentUserStore.user.is_admin" @click="openUserModal(null)"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg flex items-center space-x-2 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Add New User</span>
                </button>
            </div>
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
                        <input v-model="search" type="text" placeholder="Search items..."
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
                        <button @click="fetchUsers(pagination.current_page)" :disabled="isLoading"
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
                        <button @click="fetchUsers(pagination.current_page)" :disabled="isLoading"
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
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white">User List</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage and monitor your user</p>
            </div>

            <!-- Table Content -->
            <div class="p-6">
                <!-- Skeleton Loading -->
                <div v-if="isLoading" class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th v-for="header in tableHeaders" :key="header"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ header }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="i in 5" :key="i" class="animate-pulse">
                                <td v-for="(width, index) in ['w-8', 'w-15', 'w-25', 'w-25', 'w-25', 'w-32', 'w-16', 'w-24', 'w-28', 'w-12']"
                                    :key="index" class="px-6 py-4">
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
                                <th v-for="header in tableHeaders" :key="header"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ header }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="(user, index) in listUsers" :key="user.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ (pagination.current_page - 1) * perPage + index + 1 }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white" v-html="user.profile_photo
                                    ? `<img src='${user.profile_photo}' alt='Profile Photo' class='w-10 h-10 rounded-full'>`
                                    : svg_profile_blank">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ user.fullname }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ user.username }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ user.email }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    <Switch v-model="user.download_access" :disabled="!currentUserStore.user.is_admin"
                                        @update:modelValue="handleDownloadToggle(user, $event)" :class="[
                                            user.download_access == 1 ? 'bg-blue-600 dark:bg-blue-500' : 'bg-gray-200 dark:bg-gray-700',
                                            'relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2'
                                        ]">
                                        <span class="sr-only">Enable download</span>
                                        <span :class="[
                                            user.download_access == 1 ? 'translate-x-6' : 'translate-x-1',
                                            'inline-block h-4 w-4 transform rounded-full bg-white transition-transform'
                                        ]" />
                                    </Switch>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    <Switch v-model="user.modification_job_tracker_master_access" :disabled="!currentUserStore.user.is_admin"
                                        @update:modelValue="handleModificationJobTrackerMasterToggle(user, $event)" :class="[
                                            user.modification_job_tracker_master_access == 1 ? 'bg-blue-600 dark:bg-blue-500' : 'bg-gray-200 dark:bg-gray-700',
                                            'relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2'
                                        ]">
                                        <span class="sr-only">Enable modification job tracker master</span>
                                        <span :class="[
                                            user.modification_job_tracker_master_access == 1 ? 'translate-x-6' : 'translate-x-1',
                                            'inline-block h-4 w-4 transform rounded-full bg-white transition-transform'
                                        ]" />
                                    </Switch>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ formatDate(user.updated_at) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ user.updated_by_name }}
                                </td>
                                <td v-if="currentUserStore.user.is_admin" class="px-6 py-4 text-sm flex gap-2">
                                    <!-- Edit -->
                                    <button @click="openUserModal(user)"
                                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-md hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors duration-150">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        Edit
                                    </button>

                                    <!-- Delete -->
                                    <button @click="confirmDeleteModal(user)"
                                        class="inline-flex items-center px-2.5 py-1.5 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 rounded-lg transition-all duration-200 group">
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
import { ref, onMounted, watch, computed, reactive } from 'vue';
import { useToast } from 'vue-toastification';
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
import PasswordInput from '@/components/inputs/PasswordInput.vue';

/* ----------------------------- STATE & STORES ----------------------------- */
const listUsers = ref([]);
const pagination = ref({ current_page: 1, last_page: 1 });
const toast = useToast();
const baseUrl = import.meta.env.VITE_API_URL;

const userForm = reactive({
    id: 0,
    fullname: '',
    username: '',
    email: '',
    password: '',
    download_access: false,
    is_update_password: false,
    modification_job_tracker_master_access: false,
});

const isLoading = ref(false);
const isUserModalOpen = ref(false);
const titleModal = ref('Add User');
const titleModalButton = ref('Save User');
const isDeleteModalOpen = ref(false);
const isDeleting = ref(false);
const showMobileFilters = ref(false);
const showPassword = ref(false);
const generatingUsername = ref(false);
const usernameRecommendations = ref([]);
const usernameStatus = ref(null); // 'available', 'taken', 'checking'
const usernameStatusMessage = ref('');
const emailStatus = ref(null); // 'available', 'taken', 'checking'
const emailStatusMessage = ref('');
let usernameCheckTimeout = null;
let emailCheckTimeout = null;
const isPasswordValid = ref(false)
const tableHeaders = computed(() => {
    const base = ['No', 'Profile Photo', 'Full Name', 'Username', 'Email', 'Download Access', 'Modification Job Tracker Master Access', 'Updated At', 'Updated By']
    if (currentUserStore.user.is_admin) {
        base.push('Action')
    }
    return base
})
const sortByItems = ref([
    { name: 'Full Name', value: 'fullname' },
    { name: 'Username', value: 'username' },
    { name: 'Email', value: 'email' },
    { name: 'Updated At', value: 'updated_at' },
    { name: 'Updated By', value: 'updated_by_name' },
]);
const selectedSortByFilter = ref(sortByItems.value[0]);
const isDesc = ref(true);

const selectedUser = ref(null);
const currentUserStore = useCurrentUserStore();
const createUserloading = ref(false);
const perPageOptions = [10, 25, 100];
const perPage = ref(10);
const search = ref('');
const svg_profile_blank = `<svg width="100" height="100"
                                        class="w-8 h-8 rounded-full ring-2 ring-gray-200 group-hover:ring-orange-300 transition-all duration-200"
                                        viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="50" cy="50" r="48" fill="#F3F4F6" stroke="#E5E7EB" stroke-width="4" />
                                        <circle cx="50" cy="38" r="14" fill="#D1D5DB" />
                                        <path
                                            d="M24 78C24 65.2975 35.2975 56 48 56H52C64.7025 56 76 65.2975 76 78V80H24V78Z"
                                            fill="#D1D5DB" />
                                    </svg>`

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

const handlePasswordValidityChange = (validity) => {
    isPasswordValid.value = validity
}

/* ------------------------------ MODAL HANDLERS ------------------------------ */
function closeModal() {
    isUserModalOpen.value = false;
    isDeleteModalOpen.value = false;
    resetForm();
}

const openUserModal = async (user) => {
    if (user) {
        isUserModalOpen.value = true;
        titleModal.value = 'Edit User';
        titleModalButton.value = 'Update User';
        selectedUser.value = user;
        userForm.id = user.id;
        userForm.fullname = user.fullname;
        userForm.email = user.email;
        userForm.download_access = user.download_access === 1;
        userForm.password = ''; // Reset password field
        userForm.is_update_password = false;
    } else {
        isUserModalOpen.value = true;
        titleModal.value = 'Add User';
        selectedUser.value = null;
        userForm.is_update_password = true
    }
};

const confirmDeleteModal = (user) => {
    selectedUser.value = user;
    isDeleteModalOpen.value = true;
};


/* ----------------------------- API HANDLERS ----------------------------- */
async function fetchUsers(page = 1) {
    try {
        isLoading.value = true;
        const response = await axios.get(`${baseUrl}/api/users?page=${page}&per_page=${perPage.value}&search=${search.value}&sort_by=${selectedSortByFilter.value.value}&is_desc=${isDesc.value}`);
        listUsers.value = response.data.data;
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

function handleDeleteUser() {
    isDeleting.value = true;
    const toast = useToast();

    let ids = [selectedUser.value.id];
    axios.delete(`${baseUrl}/api/users`, {
        data: { ids }
    })
        .then(response => {
            if (response.status === 200) {
                toast.success('User deleted successfully!');
                fetchUsers(pagination.value.current_page);
                closeModal();
            }
        })
        .catch(error => {
            console.error(error);
            toast.error('Failed to delete user.');
        })
        .finally(() => {
            isDeleting.value = false;
        });
}

function goToPage(page) {
    if (page < 1 || page > pagination.value.last_page) return;
    fetchUsers(page);
}

function changePerPage(newPerPage) {
    perPage.value = newPerPage;
    pagination.value.current_page = 1;
    fetchUsers(1);
}

async function generateUsername() {
    if (userForm.fullname.trim() == '') {
        toast.error('Please enter full name first');
        return;
    }

    generatingUsername.value = true;
    usernameRecommendations.value = [];

    try {
        const candidates = generateUsernameCandidates(userForm.fullname);
        const availableUsernames = [];

        for (const candidate of candidates) {
            const isAvailable = await checkUsernameAvailability(candidate, false);
            if (isAvailable && availableUsernames.length < 3) {
                availableUsernames.push({ username: candidate, available: true });
            }
        }

        if (availableUsernames.length < 3) {
            const baseUsername = generateBaseUsername(userForm.fullname);
            let counter = 1;

            while (availableUsernames.length < 3 && counter <= 99) {
                const candidate = `${baseUsername}${counter}`;
                const isAvailable = await checkUsernameAvailability(candidate, false);
                if (isAvailable) {
                    availableUsernames.push({ username: candidate, available: true });
                }
                counter++;
            }
        }

        usernameRecommendations.value = availableUsernames;

        if (availableUsernames.length === 0) {
            toast.warning('No available username found. Please try a different name.');
        }

    } catch (error) {
        console.error('Error generating username:', error);
        toast.error('Failed to generate username recommendations');
    } finally {
        generatingUsername.value = false;
    }
}

function generateUsernameCandidates(fullname) {
    const name = fullname.toLowerCase().trim();
    const nameParts = name.split(' ').filter(part => part.length > 0);
    const candidates = [];

    if (nameParts.length >= 2) {
        const firstName = nameParts[0];
        const lastName = nameParts[nameParts.length - 1];

        candidates.push(`${firstName}.${lastName}`);
        candidates.push(`${firstName}_${lastName}`);
        candidates.push(`${firstName}${lastName}`);

        if (firstName.length >= 3 && lastName.length >= 3) {
            candidates.push(`${firstName.substring(0, 3)}${lastName.substring(0, 3)}`);
        }

        candidates.push(`${firstName}${lastName.charAt(0)}`);
        candidates.push(`${firstName.charAt(0)}${lastName}`);
    } else if (nameParts.length === 1) {
        const singleName = nameParts[0];
        candidates.push(singleName);
        if (singleName.length > 4) {
            candidates.push(singleName.substring(0, 6));
        }
    }

    return candidates
        .map(candidate => candidate.replace(/[^a-zA-Z0-9._]/g, '').toLowerCase())
        .filter(candidate => candidate.length >= 3 && candidate.length <= 20);
}

function generateBaseUsername(fullname) {
    const name = fullname.toLowerCase().trim();
    const nameParts = name.split(' ').filter(part => part.length > 0);

    if (nameParts.length >= 2) {
        return `${nameParts[0]}${nameParts[nameParts.length - 1]}`;
    }

    return nameParts[0] || 'user';
}

async function checkUsernameAvailability(username, showStatus = true) {
    if (!username || username.length < 3) return false;

    if (showStatus) {
        usernameStatus.value = 'checking';
        usernameStatusMessage.value = 'Checking availability...';
    }

    try {
        const response = await axios.post(`${baseUrl}/api/check-username`, { username });
        const isAvailable = response.data.available;

        if (showStatus) {
            usernameStatus.value = isAvailable ? 'available' : 'taken';
            usernameStatusMessage.value = isAvailable
                ? 'Username is available'
                : 'Username is already taken';
        }

        return isAvailable;

    } catch (error) {
        console.error('Error checking username:', error);
        if (showStatus) {
            usernameStatus.value = 'error';
            usernameStatusMessage.value = 'Error checking availability';
        }
        return false;
    }
}

function isValidEmailFormat(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

async function checkEmailAvailability(email, showStatus = true) {
    if (!email || email.length < 3 || !isValidEmailFormat(email)) {
        if (showStatus) {
            emailStatus.value = null;
            emailStatusMessage.value = '';
        }
        return false;
    }

    if (!selectedUser.value && selectedUser.value?.email !== email) {
        if (showStatus) {
            emailStatus.value = 'checking';
            emailStatusMessage.value = 'Checking availability...';
        }

        try {
            let data = {
                email: email,
                is_update: selectedUser.value ? true : false,
            };

            if (selectedUser.value) {
                data.selected_current_user_email = userForm.email; // Include user ID for update check
            }

            const response = await axios.post(`${baseUrl}/api/check-email`, data);
            const isAvailable = response.data.available;

            if (showStatus) {
                emailStatus.value = isAvailable ? 'available' : 'taken';
                emailStatusMessage.value = isAvailable
                    ? 'Email is available'
                    : 'Email is already taken';
            }

            return isAvailable;

        } catch (error) {
            console.error('Error checking email:', error);
            if (showStatus) {
                emailStatus.value = 'error';
                emailStatusMessage.value = 'Error checking availability';
            }
            return false;
        }
    }
}

function selectUsername(username) {
    userForm.username = username;
    usernameRecommendations.value = [];
    checkUsernameAvailability(username);
}

async function createUser() {
    createUserloading.value = true;
    if (!userForm.fullname || !userForm.email) {
        toast.error('Please fill in all required fields');
        createUserloading.value = false;
        return;
    }

    if (!selectedUser.value) {
        if (!userForm.username) {
            toast.error('Please fill in all required fields');
            createUserloading.value = false;
            return;
        }

        if (usernameStatus.value !== 'available') {
            toast.error('Username is not available');
            createUserloading.value = false;
            return;
        }

        if (userForm.username.length < 3) {
            toast.error('Username must be at least 3 characters long');
            createUserloading.value = false;
            return;
        }

        // validate username again
        if (!(await checkUsernameAvailability(userForm.username))) {
            toast.error('Username is not available');
            createUserloading.value = false;
            return;
        }
    }

    if (selectedUser.value && userForm.email !== selectedUser.value.email) {
        if (emailStatus.value !== 'available') {
            toast.error('Email is not available');
            createUserloading.value = false;
            return;
        }

        // validate email again
        if (!(await checkEmailAvailability(userForm.email))) {
            toast.error('Email is not available');
            createUserloading.value = false;
            return;
        }

        if (!isValidEmailFormat(userForm.email)) {
            toast.error('Invalid email format');
            createUserloading.value = false;
            return;
        }
    }

    if (userForm.is_update_password && !isPasswordValid.value) {
        toast.error('Password does not meet the requirements');
        createUserloading.value = false;
        return;
    }

    try {
        if (selectedUser.value) {
            // Update existing user
            const response = await axios.put(`${baseUrl}/api/users/${userForm.id}`, userForm);
            if (response.status === 200) {
                toast.success('User updated successfully!');
                fetchUsers(pagination.value.current_page);
                closeModal();
                resetForm();
                selectedUser.value = null;
            }
        } else {
            // Create new user
            const response = await axios.post(`${baseUrl}/api/users`, userForm);
            if (response.status === 201) {
                toast.success('User created successfully!');
                fetchUsers(pagination.value.current_page);
                closeModal();
                resetForm();
                selectedUser.value = null;
            }
        }
    } catch (error) {
        console.error(error);
        toast.error('Failed to create user. Please try again.');
    } finally {
        createUserloading.value = false;
    }
}


const handleDownloadToggle = async (user, newValue) => {
    try {
        // Disini anda bisa menambahkan API call untuk update status
        await axios.post(`${baseUrl}/api/users/${user.id}/update-download-permission`, {
            download_access: newValue
        })

        // Optional: Tampilkan notifikasi sukses
        toast.success('Download permission updated successfully')
    } catch (error) {
        // Jika gagal, kembalikan nilai ke state sebelumnya
        user.download_access = !newValue

        // Tampilkan error
        toast.error('Failed to update download permission')
        console.error('Error updating download status:', error)
    }
}

const handleModificationJobTrackerMasterToggle = async (user, newValue) => {
    try {
        // Disini anda bisa menambahkan API call untuk update status
        await axios.post(`${baseUrl}/api/users/${user.id}/update-modification-job-tracker-master-permission`, {
            modification_job_tracker_master_access: newValue
        })

        // Optional: Tampilkan notifikasi sukses
        toast.success('Modification Job Tracker Master permission updated successfully')
    } catch (error) {
        // Jika gagal, kembalikan nilai ke state sebelumnya
        user.modification_job_tracker_master_access = !newValue

        // Tampilkan error
        toast.error('Failed to update Modification Job Tracker Master permission')
        console.error('Error updating modification job tracker master permission:', error)
    }
}

function resetForm() {
    userForm.id = 0;
    userForm.fullname = '';
    userForm.username = '';
    userForm.email = '';
    userForm.password = '';
    userForm.is_update_password = false;
    userForm.download_access = false;
    usernameRecommendations.value = [];
    usernameStatus.value = null;
    emailStatus.value = null;
    usernameStatusMessage.value = '';
    emailStatusMessage.value = '';
    showPassword.value = false;
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
    fetchUsers(pagination.value.current_page || 1);
});

watch(() => userForm.username, (newUsername) => {
    if (usernameCheckTimeout) clearTimeout(usernameCheckTimeout);

    if (newUsername && newUsername.length > 2) {
        usernameCheckTimeout = setTimeout(() => {
            checkUsernameAvailability(newUsername);
        }, 500);
    } else {
        usernameStatus.value = null;
        usernameStatusMessage.value = '';
    }
});

watch(() => userForm.email, (newEmail) => {
    if (emailCheckTimeout) clearTimeout(emailCheckTimeout);

    if (newEmail && newEmail.length > 2) {
        emailCheckTimeout = setTimeout(() => {
            checkEmailAvailability(newEmail);
        }, 500);
    } else {
        emailStatus.value = null;
        emailStatusMessage.value = '';
    }
});


/* ------------------------------ ON MOUNT ------------------------------ */
onMounted(async () => {
    if (!currentUserStore.user) {
        await currentUserStore.fetchUser();
    }
    fetchUsers();
});
</script>