<template>
    <div class="p-6 bg-gray-50 min-h-screen dark:bg-slate-900/50 dark:text-gray-100 rounded-xl">
        <!-- Header -->
        <div class="max-w-auto mx-auto">
            <!-- Profile Photo Section -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 mb-8">
                <h2 class="text-xl font-semibold mb-6 text-gray-800 dark:text-white">Profile Photo</h2>
                <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6">
                    <div class="relative group">
                        <img v-if="profileForm.profile_image != ''" :src="profileForm.profile_image"
                            class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 dark:border-slate-600 shadow-lg">
                        <svg v-else width="100" height="100" class="h-32 w-32 rounded-full object-cover"
                            viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="50" cy="50" r="48" fill="#F3F4F6" stroke="#E5E7EB" stroke-width="4" />
                            <circle cx="50" cy="38" r="14" fill="#D1D5DB" />
                            <path d="M24 78C24 65.2975 35.2975 56 48 56H52C64.7025 56 76 65.2975 76 78V80H24V78Z"
                                fill="#D1D5DB" />
                        </svg>
                        <div class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
                            @click="triggerFileInput">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <input ref="fileInputRef" type="file" accept="image/*" @change="handleProfileImageUpload"
                        class="hidden">
                    <div class="text-center md:text-left">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-2">{{ profileForm.fullname }}</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">{{ profileForm.email }}</p>
                        <button @click="triggerFileInput"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Change Photo
                        </button>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                            JPG, PNG max 2MB
                        </p>
                    </div>
                </div>
            </div>

            <!-- Profile Info Section -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 mb-8">
                <h2 class="text-xl font-semibold mb-6 text-gray-800 dark:text-white">Profile Information</h2>
                <form @submit.prevent="updateProfile" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Full Name
                            </label>
                            <input v-model="profileForm.fullname" type="text"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-slate-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email
                            </label>
                            <input v-model="profileForm.email" type="email"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-slate-700 dark:text-white"
                                disabled>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Username
                        </label>
                        <input v-model="profileForm.username" type="tel"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-slate-700 dark:text-white"
                            disabled>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" :disabled="isLoading"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center space-x-2">
                            <svg v-if="isLoading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span>{{ isLoading ? 'Saving...' : 'Save Changes' }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Section -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-6 text-gray-800 dark:text-white">Change Password</h2>
                <form @submit.prevent="updatePassword" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Current Password
                        </label>
                        <div class="relative">
                            <input v-model="passwordForm.currentPassword"
                                :type="passwordForm.showCurrentPass ? 'text' : 'password'"
                                class="w-full px-4 py-2 pr-12 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-slate-700 dark:text-white">
                            <button type="button" @click="passwordForm.showCurrentPass = !passwordForm.showCurrentPass"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                <svg v-if="passwordForm.showCurrentPass" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21">
                                    </path>
                                </svg>
                                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            New Password
                        </label>
                        <div class="relative">
                            <input v-model="passwordForm.newPassword" :type="passwordForm.showNewPass ? 'text' : 'password'"
                                class="w-full px-4 py-2 pr-12 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-slate-700 dark:text-white">
                            <button type="button" @click="passwordForm.showNewPass = !passwordForm.showNewPass"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                <svg v-if="passwordForm.showNewPass" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21">
                                    </path>
                                </svg>
                                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        <!-- Password Requirements Checklist -->
                        <div class="mt-3 space-y-2">
                            <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Password Requirements:
                            </div>
                            <div v-for="(check, index) in passwordChecks" :key="index"
                                class="flex items-center space-x-2 text-sm"
                                :class="check.passed ? 'text-green-600 dark:text-green-400' : 'text-gray-400 dark:text-gray-600'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path v-if="check.passed" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                    <circle v-else cx="12" cy="12" r="10" stroke-width="2" />
                                </svg>
                                <span>{{ check.message }}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Confirm New Password
                        </label>
                        <div class="relative">
                            <input v-model="passwordForm.confirmPassword"
                                :type="passwordForm.showConfirmPass ? 'text' : 'password'"
                                class="w-full px-4 py-2 pr-12 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-slate-700 dark:text-white">
                            <button type="button" @click="passwordForm.showConfirmPass = !passwordForm.showConfirmPass"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                <svg v-if="passwordForm.showConfirmPass" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21">
                                    </path>
                                </svg>
                                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        <!-- password not match error message -->
                        <div v-if="passwordForm.newPassword && passwordForm.confirmPassword && passwordForm.newPassword !== passwordForm.confirmPassword"
                            class="text-red-500 text-sm mt-1">
                            Password confirmation does not match
                            <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" :disabled="isLoading || (passwordForm.newPassword !== passwordForm.confirmPassword || !isPasswordValid || passwordForm.currentPassword === '')"
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center space-x-2">
                            <svg v-if="isLoading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span>{{ isLoading ? 'Updating...' : 'Update Password' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
  
<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useCurrentUserStore } from '@/stores/CurrentUser';
import { useToast } from 'vue-toastification';

const currentUserStore = useCurrentUserStore();
const toast = useToast();
const baseUrl = import.meta.env.VITE_API_URL;

// State for profile data
const profile = reactive({
    fullname: '',
    email: '',
    username: '',
    profile_image: ''
})

// State untuk form password
const passwordForm = reactive({
    currentPassword: '',
    newPassword: '',
    confirmPassword: '',
    showCurrentPass: false,
    showNewPass: false,
    showConfirmPass: false
})

// State untuk form profile
const profileForm = reactive({
    fullname: profile.fullname,
    email: profile.email,
    username: profile.username,
    profile_image: profile.profile_image,
})

// State untuk loading dan messages
const isLoading = ref(false)

// Template refs
const fileInputRef = ref(null)

const handleProfileImageUpload = (event) => {
    const file = event.target.files[0]
    if (file) {
        if (file.size > 2 * 1024 * 1024) {
            toast.error('File size must be less than 2MB')
            return
        }

        if (!file.type.startsWith('image/')) {
            toast.error('Only image files are allowed')
            return
        }

        const reader = new FileReader()
        reader.onload = (e) => {
            profile.profile_image = e.target.result
            profileForm.profile_image = e.target.result
            // toast.success('Profile photo updated successfully')
        }
        reader.readAsDataURL(file)
    }
}

const triggerFileInput = () => {
    fileInputRef.value.click()
}

const updateProfile = async () => {
    isLoading.value = true
    try {
        let formData = new FormData();
        formData.append('fullname', profileForm.fullname);
        formData.append('email', profileForm.email);
        formData.append('username', profileForm.username);
        if (profileForm.profile_image) {
            const blob = await fetch(profileForm.profile_image).then(res => res.blob());
            formData.append('profile_image', blob, 'profile_image.png');
        }

        formData.append('_method', 'PUT');
        await axios.post(`${baseUrl}/api/users-profile`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        await currentUserStore.fetchUser();
        toast.success('Profile updated successfully')
    } catch (error) {
        toast.error('Failed to update profile')
    } finally {
        isLoading.value = false
    }
}

const isPasswordValid = computed(() => {
    return passwordChecks.value.every(check => check.passed)
})

const updatePassword = async () => {
    if (isPasswordValid.value && passwordForm.newPassword === passwordForm.confirmPassword) {
        isLoading.value = true
        try {
            await axios.put(`${baseUrl}/api/users-profile-change-password`, {
                current_password: passwordForm.currentPassword,
                new_password: passwordForm.newPassword,
                new_password_confirmation: passwordForm.confirmPassword
            });

            toast.success('Password updated successfully')
            // Reset form fields
            passwordForm.currentPassword = '';
            passwordForm.newPassword = '';
            passwordForm.confirmPassword = '';
        } catch (error) {
            if (error.response && error.response.status === 422) {
                toast.error('Current password is incorrect')
            } else {
                toast.error('Failed to update password')
            }
        } finally {
            isLoading.value = false
        }
    } else {
        toast.error('Please fix the errors before submitting')
    }
}

const passwordChecks = computed(() => [
    {
        message: 'Minimum 8 characters',
        passed: passwordForm.newPassword.length >= 8
    },
    {
        message: 'Contains uppercase letter',
        passed: /[A-Z]/.test(passwordForm.newPassword)
    },
    {
        message: 'Contains lowercase letter',
        passed: /[a-z]/.test(passwordForm.newPassword)
    },
    {
        message: 'Contains number',
        passed: /[0-9]/.test(passwordForm.newPassword)
    },
    {
        message: 'Contains special character',
        passed: /[!@#$%^&*]/.test(passwordForm.newPassword)
    }
])

onMounted(async () => {
    if (!currentUserStore.user) {
        await currentUserStore.fetchUser();
    }

    profile.fullname = currentUserStore.user.fullname;
    profile.email = currentUserStore.user.email;
    profile.username = currentUserStore.user.username;
    profile.profile_image = currentUserStore.user.profile_image || '';

    profileForm.fullname = profile.fullname;
    profileForm.email = profile.email;
    profileForm.username = profile.username;
    if (profile.profile_image != '') {
        profileForm.profile_image = baseUrl + profile.profile_image;
    }
});

</script>
  
<style scoped>
/* Custom styles jika diperlukan */
</style>