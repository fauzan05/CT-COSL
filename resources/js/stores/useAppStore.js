import {
    defineStore
} from 'pinia';
import axios from "axios";
const baseUrl = import.meta.env.VITE_API_URL;

export const useAppStore = defineStore("app", {
    state: () => ({
        isTypeModalOpen: false,
        toolstringTypes: [],
        typeFormAction: 'create',
        selectedTypeData: null,
        wellstackTypes: [],
        selectedDropdownMenu: null,
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
        async getWellstackTypes() {
            try {
                const res = await axios.get(
                    `${baseUrl}/api/wellstack-types`
                );
                this.wellstackTypes = res.data;
                console.log("Wellstack Types:", this.wellstackTypes);
            } catch (error) {
                console.error(error);
                throw error;
            }
        },
    },
});
