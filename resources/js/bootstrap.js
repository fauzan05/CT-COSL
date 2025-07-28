// resources/js/bootstrap.js atau app.js
import axios from "axios";

// Setup axios untuk Laravel
window.axios = axios;

// Set default headers
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Setup CSRF token dari meta tag
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
    console.error(
        "CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token"
    );
}

// Setup Sanctum (jika pakai Sanctum untuk auth)
axios.defaults.withCredentials = true;

// Setup axios interceptor untuk handle 401 globally
export function setupAxiosInterceptors() {
    axios.interceptors.response.use(
        (response) => {
            return response;
        },
        (error) => {
            // Handle 401 unauthorized globally
            if (error.response?.status === 401) {
                // Import store di dalam interceptor untuk menghindari circular dependency
                import("@/stores/currentUser").then(
                    ({ useCurrentUserStore }) => {
                        const userStore = useCurrentUserStore();
                        userStore.handleUnauthorized();
                    }
                );
            }

            // Handle 419 (CSRF token mismatch) - refresh page
            if (error.response?.status === 419) {
                window.location.reload();
            }

            return Promise.reject(error);
        }
    );
}

// Call setupAxiosInterceptors after DOM is ready
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", setupAxiosInterceptors);
} else {
    setupAxiosInterceptors();
}
