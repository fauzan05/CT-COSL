<template>
    <div class="min-h-screen flex items-center justify-center relative">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img :src="imgBgSrc" class="w-full h-full object-cover" alt="background" />
            <!-- Overlay untuk membuat background sedikit gelap -->
            <div class="absolute inset-0 bg-black/30 backdrop-blur-[1px]"></div>
        </div>
        <!-- Card Container -->
        <div class="w-full my-10 max-w-sm sm:max-w-md px-4 sm:px-0 relative z-10">
            <!-- Glass Card Container -->
            <div class="h-auto backdrop-blur-md bg-white/30 rounded-3xl shadow-2xl overflow-hidden flex flex-col">
                <!-- Header with Logo -->
                <div
                    class="relative h-24 bg-gradient-to-r from-black/70 to-gray-900/70 flex items-center justify-center flex-shrink-0">
                    <div
                        class="absolute -bottom-12 p-4 backdrop-blur-sm bg-white/40 rounded-2xl shadow-xl border border-white/50">
                        <img class="h-20 rounded-sm object-contain" :src="imgSrc" alt="company-logo" />
                    </div>
                </div>
                <!-- Main Content Area -->
                <div class="flex-1 overflow-y-auto">
                    <!-- Error Message -->
                    <TransitionGroup>
                        <div v-if="error"
                            class="mx-6 mt-16 backdrop-blur-md bg-red-500/10 border border-red-500/20 rounded-xl overflow-hidden">
                            <div class="px-4 py-3 text-red-200 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <p class="font-medium">{{ error }}</p>
                            </div>
                        </div>
                    </TransitionGroup>

                    <!-- Success Message -->
                    <TransitionGroup>
                        <div v-if="success"
                            class="mx-6 mt-16 backdrop-blur-md bg-green-500/10 border border-green-500/20 rounded-xl overflow-hidden">
                            <div class="px-4 py-3 text-green-200 flex items-center">
                                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="font-medium mb-1">Password reset successfully!</p>
                                    <p class="text-sm text-green-300">Your password has been updated. You can now login with
                                        your new password.</p>
                                </div>
                            </div>
                        </div>
                    </TransitionGroup>

                    <!-- Reset Password Form -->
                    <div v-if="!success" class="px-8 pb-8" :class="error ? 'pt-8' : 'pt-20'">
                        <div class="text-center mb-8">
                            <h2 class="text-2xl font-bold text-white/90 mb-2">Reset Password</h2>
                            <p class="text-white/70 text-sm">Enter your new password below to complete the reset process.
                            </p>
                        </div>

                        <form @submit.prevent="resetPassword">
                            <!-- New Password Field -->
                            <div class="mb-6">
                                <label class="block text-white/80 text-sm font-medium mb-2" for="newPassword">
                                    New Password
                                </label>
                                <div class="relative group">
                                    <input
                                        class="w-full px-4 py-3 pl-11 pr-11 bg-white/20 border border-white/90 rounded-xl text-white placeholder-white/90 focus:outline-none focus:border-white/30 focus:ring-2 focus:ring-white/20 transition-all duration-300"
                                        id="newPassword" :type="showNewPassword ? 'text' : 'password'"
                                        placeholder="Enter your new password" v-model="resetForm.newPassword" required>
                                    <!-- Password Icon (Left) -->
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-white group-focus-within:text-white/60 transition-colors duration-300"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <!-- Show/Hide Password Button (Right) -->
                                    <button type="button" @click="showNewPassword = !showNewPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-white hover:text-white/50 transition-colors duration-300">
                                        <!-- Show Password Icon -->
                                        <svg v-if="!showNewPassword" class="w-5 h-5" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd"
                                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <!-- Hide Password Icon -->
                                        <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"
                                                clip-rule="evenodd" />
                                            <path
                                                d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
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

                            <!-- Confirm New Password Field -->
                            <div class="mb-6">
                                <label class="block text-white/80 text-sm font-medium mb-2" for="confirmPassword">
                                    Confirm New Password
                                </label>
                                <div class="relative group">
                                    <input
                                        class="w-full px-4 py-3 pl-11 pr-11 bg-white/20 border border-white/90 rounded-xl text-white placeholder-white/90 focus:outline-none focus:border-white/30 focus:ring-2 focus:ring-white/20 transition-all duration-300"
                                        id="confirmPassword" :type="showConfirmPassword ? 'text' : 'password'"
                                        placeholder="Confirm your new password" v-model="resetForm.confirmPassword"
                                        required>
                                    <!-- Password Icon (Left) -->
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-white group-focus-within:text-white/60 transition-colors duration-300"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <!-- Show/Hide Password Button (Right) -->
                                    <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-white hover:text-white/50 transition-colors duration-300">
                                        <!-- Show Password Icon -->
                                        <svg v-if="!showConfirmPassword" class="w-5 h-5" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd"
                                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <!-- Hide Password Icon -->
                                        <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"
                                                clip-rule="evenodd" />
                                            <path
                                                d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                                        </svg>
                                    </button>
                                </div>
                                <!-- Password Match Indicator -->
                                <div v-if="resetForm.confirmPassword" class="mt-2 text-xs flex items-center">
                                    <svg v-if="passwordsMatch" class="w-5 h-5 mr-2 text-green-400" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <svg v-else class="w-5 h-5 mr-2 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-medium text-sm" :class="passwordsMatch ? 'text-green-400' : 'text-red-400'">
                                        {{ passwordsMatch ? 'Passwords match' : 'Passwords do not match' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="mt-8 mb-6">
                                <button type="submit" :disabled="loading || (!isPasswordValid || !passwordsMatch)"
                                    class="w-full bg-gradient-to-r from-blue-500/90 to-blue-600/90 hover:from-blue-500 hover:to-blue-600 disabled:from-gray-500/90 disabled:to-gray-600/90 disabled:cursor-not-allowed text-white font-semibold py-3 px-4 rounded-xl transform hover:scale-[1.02] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                                    <span v-if="!loading">Reset Password</span>
                                    <span v-else class="flex items-center justify-center">
                                        <div class="flex items-center justify-center">
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4">
                                                </circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                            Resetting...
                                        </div>
                                    </span>
                                </button>
                            </div>

                            <!-- Back to Login Link -->
                            <div class="text-center">
                                <RouterLink to="/login"
                                    class="text-sm text-white hover:text-white/50 transition-colors duration-300 flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Back to Login
                                </RouterLink>
                            </div>
                        </form>
                    </div>

                    <!-- Success State Content -->
                    <div v-else class="px-8 pb-8 pt-8">
                        <div class="text-center">
                            <div
                                class="mx-auto w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-8 h-8 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-white/90 mb-4">Password Reset Complete</h2>
                            <p class="text-white/70 text-sm mb-6 leading-relaxed">
                                Your password has been successfully updated. You can now login with your new password.
                            </p>
                            <RouterLink to="/login"
                                class="w-full bg-gradient-to-r from-blue-500/90 to-blue-600/90 hover:from-blue-500 hover:to-blue-600 text-white font-semibold py-3 px-4 rounded-xl transform hover:scale-[1.02] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                                Go to Login
                            </RouterLink>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div
                        class="py-4 text-center text-xs text-white/40 border-t border-white/10 backdrop-blur-md bg-white/5">
                        © 2025 CT-COSL. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const error = ref('');
const success = ref(false);
const resetForm = ref({
    email: "",
    newPassword: "",
    confirmPassword: "",
    token: ""
});

const baseUrl = import.meta.env.VITE_API_URL;
const imgSrc = `${baseUrl}/assets/images/company/company-logo.png`;
const imgBgSrc = `${baseUrl}/assets/images/company/company-bg-login.jpeg`;
const loading = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);

// Get token from URL parameters
onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get('token');
    resetForm.value.email = urlParams.get('email') || ''; // Optional email parameter

    if (!token) {
        error.value = 'Invalid or missing reset token. Please request a new password reset.';
    } else {
        resetForm.value.token = token;
    }
});

// Password validation
const passwordChecks = computed(() => [
    {
        message: 'Minimum 8 characters',
        passed: resetForm.value.newPassword.length >= 8
    },
    {
        message: 'Contains uppercase letter',
        passed: /[A-Z]/.test(resetForm.value.newPassword)
    },
    {
        message: 'Contains lowercase letter',
        passed: /[a-z]/.test(resetForm.value.newPassword)
    },
    {
        message: 'Contains number',
        passed: /[0-9]/.test(resetForm.value.newPassword)
    },
    {
        message: 'Contains special character',
        passed: /[!@#$%^&*]/.test(resetForm.value.newPassword)
    }
])

// Check if passwords match
const passwordsMatch = computed(() => {
    return resetForm.value.newPassword === resetForm.value.confirmPassword &&
        resetForm.value.confirmPassword.length > 0;
});

// Check if form is valid
const isPasswordValid = computed(() => {
    return passwordChecks.value.every(check => check.passed)
})

const resetPassword = async () => {
    if (!isPasswordValid.value) {
        error.value = 'Please ensure all password requirements are met and passwords match.';
        return;
    }

    error.value = '';
    loading.value = true;

    try {
        await axios.post(`${baseUrl}/api/reset-password`, {
            email: resetForm.value.email, // Email is not required for reset, but can be added if needed
            token: resetForm.value.token,
            password: resetForm.value.newPassword,
            password_confirmation: resetForm.value.confirmPassword
        });

        success.value = true;
        error.value = '';
    } catch (err) {
        success.value = false;
        if (err.response && err.response.data && err.response.data.message) {
            error.value = err.response.data.message;
        } else {
            error.value = 'Failed to reset password. Please try again or request a new reset link.';
        }
    } finally {
        loading.value = false;
    }
};

</script>

<style scoped>
/* Optional: Tambahkan animasi fade-in untuk background */
@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.fade-in {
    animation: fadeIn 1s ease-in;
}

/* Transition animations */
.v-enter-active,
.v-leave-active {
    transition: all 0.3s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>