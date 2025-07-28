import { defineStore } from "pinia";
import axios from "axios";
const baseUrl = import.meta.env.VITE_API_URL;

export const useCurrentUserStore = defineStore("currentUser", {
    state: () => ({
        user: null,
        loading: false,
        error: null,
    }),

    getters: {
        isAuthenticated: (state) => !!state.user,
        isLoading: (state) => state.loading,
        hasError: (state) => !!state.error,
    },

    actions: {
        async fetchUser() {
            this.loading = true;
            this.error = null;

            try {
                const response = await axios.get(baseUrl + "/api/current-user");
                this.user = response.data;
                return response.data;
            } catch (err) {
                this.error =
                    err.response?.data?.message ||
                    "Failed to fetch current user";
                this.user = null;

                // Redirect to login if unauthorized
                if (err.response?.status === 401) {
                    this.clearUser();
                    // Laravel-style redirect
                    window.location.href = "/login";
                    // Atau jika pakai Laravel route names:
                    // window.location.href = route('login');
                }

                throw err;
            } finally {
                this.loading = false;
            }
        },

        setUser(user) {
            this.user = user;
            this.error = null;
        },

        clearUser() {
            this.user = null;
            this.error = null;
        },

        async logout() {
            try {
                // Laravel logout endpoint
                await axios.post("/logout");
            } catch (err) {
                console.error("Logout error:", err);
            } finally {
                this.clearUser();
                window.location.href = "/login";
            }
        },

        // Method untuk handle unauthorized errors dari mana saja
        handleUnauthorized() {
            this.clearUser();
            window.location.href = "/login";
        },
    },

    persist: true, // Keep user logged in after reload
});
