import {
    defineStore
} from 'pinia';
import axios from "axios";
const baseUrl = document.querySelector('meta[name="base-url"]').content;

export const useAppStore = defineStore("app", {
    state: () => ({
        isCategoryModalOpen: false,
        toolstringCategories: [],
        categoryFormAction: 'create',
        selectedCategoryData: null,
    }),
    actions: {
        async getToolstringCategories() {
            try {
                const res = await axios.get(
                    `${baseUrl}/api/toolstring-categories`
                );
                this.toolstringCategories = res.data;
            } catch (error) {
                console.error(error);
                throw error;
            }
        },
    },
});
