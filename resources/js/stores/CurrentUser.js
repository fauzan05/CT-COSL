import { defineStore } from "pinia";
import axios from "axios";

export const useCurrentUserStore = defineStore("currentUser", {
    state: () => ({
        user: null,
        loading: false,
        error: null,
    }),

    actions: {
        async fetchUser() {
            const baseUrl = document.querySelector(
                'meta[name="base-url"]'
            ).content;
            this.loading = true;
            this.error = null;
            try {
                const response = await axios.get(baseUrl + "/api/current-user");
                this.user = response.data;
            } catch (err) {
                this.error =
                    err.response?.data?.message || "Failed to fetch current user";
                this.user = null;
            } finally {
                this.loading = false;
            }
        },

        setUser(user) {
            this.user = user;
        },

        clearUser() {
            this.user = null;
        },
    },

    persist: true, // optional, kalau mau user tetap login setelah reload
});
