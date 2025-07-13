import {
    defineStore
} from 'pinia';
import axios from "axios";
const baseUrl = document.querySelector('meta[name="base-url"]').content;

export const useAppStore = defineStore("app", {
    state: () => ({
        isTypeModalOpen: false,
        toolstringTypes: [],
        typeFormAction: 'create',
        selectedTypeData: null,
    }),
    actions: {
        async getToolstringTypes() {
            try {
                const res = await axios.get(
                    `${baseUrl}/api/toolstring-types`
                );
                this.toolstringTypes = res.data;
            } catch (error) {
                console.error(error);
                throw error;
            }
        },
    },
});
