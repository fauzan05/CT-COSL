<template>
    <div class="min-h-screen flex items-center justify-center relative">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img :src="imgBgSrc" class="w-full h-screen object-cover" alt="background" />
            <!-- Overlay untuk membuat background sedikit gelap -->
            <div class="absolute inset-0 bg-black/30 backdrop-blur-[1px]"></div>
        </div>
        <!-- Card Container dengan fixed height -->
        <div class="w-full max-w-sm sm:max-w-md px-4 sm:px-0 relative z-10">
            <!-- Glass Card Container dengan fixed height -->
            <div class="h-auto backdrop-blur-md bg-white/30 rounded-3xl shadow-2xl overflow-hidden flex flex-col">
                <!-- Header with Logo -->
                <div
                    class="relative h-24 bg-gradient-to-r from-black/70 to-gray-900/70 flex items-center justify-center flex-shrink-0">
                    <div
                        class="absolute -bottom-12 p-4 backdrop-blur-sm bg-white/40 rounded-2xl shadow-xl border border-white/50">
                        <img class="h-20 rounded-sm object-contain" :src="imgSrc" alt="company-logo" />
                    </div>
                </div>
                <!-- Main Content Area dengan scroll jika konten melebihi tinggi -->
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
                    <!-- Login Form -->
                    <div class="px-8 pb-8" :class="error ? 'pt-8' : 'pt-20'">
                        <h2 class="text-2xl font-bold text-center mb-8 text-white/90">Internal System Login</h2>
                        <form @submit.prevent="login">
                            <!-- Username Field -->
                            <div class="mb-6">
                                <label class="block text-white/80 text-sm font-medium mb-2" for="username">
                                    Username
                                </label>
                                <div class="relative group">
                                    <input
                                        class="w-full px-4 py-3 pl-11 bg-white/20 border border-white/90 rounded-xl text-white placeholder-white/90 focus:outline-none focus:border-white/30 focus:ring-2 focus:ring-white/20 transition-all duration-300"
                                        id="username" type="text" placeholder="Enter your username"
                                        v-model="loginForm.username">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-white group-focus-within:text-white/60 transition-colors duration-300"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <!-- Password Field -->
                            <div class="mb-6">
                                <label class="block text-white/80 text-sm font-medium mb-2" for="password">
                                    Password
                                </label>
                                <div class="relative group">
                                    <input
                                        class="w-full px-4 py-3 pl-11 pr-11 bg-white/20 border border-white/90 rounded-xl text-white placeholder-white/90 focus:outline-none focus:border-white/30 focus:ring-2 focus:ring-white/20 transition-all duration-300"
                                        id="password" :type="showPassword ? 'text' : 'password'"
                                        placeholder="Enter your password" v-model="loginForm.password">
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
                                    <button type="button" @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-white hover:text-white/50 transition-colors duration-300">
                                        <!-- Show Password Icon -->
                                        <svg v-if="!showPassword" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
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
                                <div class="flex justify-end mt-2">
                                    <RouterLink to="/forgot-password"
                                        class="text-sm text-white hover:text-white/50 transition-colors duration-300">
                                        Forgot password?
                                    </RouterLink>
                                </div>
                            </div>
                            <!-- Login Button -->
                            <div class="mt-8 mb-6">
                                <button type="submit"
                                    class="w-full bg-gradient-to-r from-blue-500/90 to-blue-600/90 hover:from-blue-500 hover:to-blue-600 text-white font-semibold py-3 px-4 rounded-xl transform hover:scale-[1.02] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                                    <span v-if="!loading">Login</span>
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
                                            Processing...
                                        </div>
                                    </span>
                                </button>
                            </div>
                            <!-- Help Section -->
                            <div class="text-center text-sm text-white/60">
                                Need help? Contact <a href="#"
                                    class="text-white hover:text-white/90 font-medium transition-colors duration-300">IT
                                    Support</a>
                            </div>
                        </form>
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
const error = ref('');
const loginForm = ref({
    username: "",
    password: "",
    remember: false,
});
const baseUrl = import.meta.env.VITE_API_URL;
const imgSrc = `${baseUrl}/assets/images/company/company-logo.png`;
const imgBgSrc = `${baseUrl}/assets/images/company/company-bg-login.jpeg`;
const loading = ref(false);
const showPassword = ref(false);

const login = async () => {
    error.value = null;
    loading.value = true;
    try {
        await axios.post(`${baseUrl}/api/login`, {
            username: loginForm.value.username,
            password: loginForm.value.password,
            remember: loginForm.value.remember
        });
        // Contoh redirect
        window.location.href = baseUrl + '/dashboard';
    } catch (err) {
        if (err.response && err.response.data && err.response.data.message) {
            error.value = err.response.data.message;
        } else {
            error.value = 'Login failed. Please try again.';
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
</style>