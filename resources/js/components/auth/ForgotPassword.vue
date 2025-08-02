<template>
    <div class="min-h-screen flex items-center justify-center relative">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img :src="imgBgSrc" class="w-full h-screen object-cover" alt="background" />
            <!-- Overlay untuk membuat background sedikit gelap -->
            <div class="absolute inset-0 bg-black/30 backdrop-blur-[1px]"></div>
        </div>
        <!-- Card Container -->
        <div class="w-full max-w-sm sm:max-w-md px-4 sm:px-0 relative z-10">
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
                                    <p class="font-medium mb-1">Reset link sent successfully!</p>
                                    <p class="text-sm text-green-300">Please check your email and click the verification
                                        link to reset your password.</p>
                                </div>
                            </div>
                        </div>
                    </TransitionGroup>

                    <!-- Forgot Password Form -->
                    <div v-if="!success" class="px-8 pb-8" :class="error ? 'pt-8' : 'pt-20'">
                        <div class="text-center mb-8">
                            <h2 class="text-2xl font-bold text-white/90 mb-2">Forgot Password</h2>
                            <p class="text-white/70 text-sm">Enter your email address and we'll send you a link to reset
                                your password.</p>
                        </div>

                        <form @submit.prevent="sendResetLink">
                            <!-- Email Field -->
                            <div class="mb-6">
                                <label class="block text-white/80 text-sm font-medium mb-2" for="email">
                                    Email Address
                                </label>
                                <div class="relative group">
                                    <input
                                        class="w-full px-4 py-3 pl-11 bg-white/20 border border-white/90 rounded-xl text-white placeholder-white/90 focus:outline-none focus:border-white/30 focus:ring-2 focus:ring-white/20 transition-all duration-300"
                                        id="email" type="email" placeholder="Enter your email address"
                                        v-model="forgotForm.email" required>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-white group-focus-within:text-white/60 transition-colors duration-300"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                            </path>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="mt-8 mb-6">
                                <button type="submit" :disabled="loading"
                                    class="w-full bg-gradient-to-r from-blue-500/90 to-blue-600/90 hover:from-blue-500 hover:to-blue-600 disabled:from-gray-500/90 disabled:to-gray-600/90 disabled:cursor-not-allowed text-white font-semibold py-3 px-4 rounded-xl transform hover:scale-[1.02] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                                    <span v-if="!loading">Send Reset Link</span>
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
                                            Sending...
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
                            <h2 class="text-2xl font-bold text-white/90 mb-4">Check Your Email</h2>
                            <p class="text-white/70 text-sm mb-6 leading-relaxed">
                                We've sent a password reset link to <span class="text-white font-medium">{{ forgotForm.email
                                }}</span>.
                                Please check your email and click the link to reset your password.
                            </p>
                            <div class="space-y-4">
                                <button @click="sendResetLink" :disabled="loading"
                                    class="w-full bg-gradient-to-r from-gray-500/90 to-gray-600/90 hover:from-gray-500 hover:to-gray-600 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-gray-500/50">
                                    <span v-if="!loading">Resend Email</span>
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
                                            Sending...
                                        </div>
                                    </span>
                                </button>
                                <RouterLink to="/login"
                                    class="w-full bg-gradient-to-r from-blue-500/90 to-blue-600/90 hover:from-blue-500 hover:to-blue-600 text-white font-semibold py-3 px-4 rounded-xl transform hover:scale-[1.02] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                                    Back to Login
                                </RouterLink>
                            </div>
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
import { ref } from 'vue';
import axios from 'axios';

const error = ref('');
const success = ref(false);
const forgotForm = ref({
    email: "",
});

const baseUrl = import.meta.env.VITE_API_URL;
const imgSrc = `${baseUrl}/assets/images/company/company-logo.png`;
const imgBgSrc = `${baseUrl}/assets/images/company/company-bg-login.jpeg`;
const loading = ref(false);

const sendResetLink = async () => {
    error.value = '';
    loading.value = true;

    try {
        await axios.post(`${baseUrl}/api/forgot-password`, {
            email: forgotForm.value.email
        });

        success.value = true;
        error.value = '';
    } catch (err) {
        success.value = false;
        if (err.response && err.response.data && err.response.data.message) {
            error.value = err.response.data.message;
        } else {
            error.value = 'Failed to send reset link. Please try again.';
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