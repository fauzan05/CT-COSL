<template>
    <!-- modal create/update/delete type -->
    <TransitionRoot appear :show="appStore.isTypeModalOpen" as="template">
        <Dialog as="div" @close="closeModal" class="relative z-50">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" />
            </TransitionChild>
            <div class="fixed inset-0 overflow-y-auto">
                <div
                    class="flex min-h-full items-center justify-center p-4 text-center"
                >
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel
                            class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800/80 p-6 text-left align-middle shadow-xl transition-all"
                        >
                            <!-- Close Button -->
                            <button
                                @click="closeModal"
                                class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors duration-200 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                                aria-label="Close modal"
                            >
                                <svg
                                    class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                            <DialogTitle
                                as="h3"
                                class="text-lg font-medium leading-6 text-gray-900 mb-4 dark:text-white"
                            >
                                <i class="fa-solid fa-folder-plus"></i> &nbsp;
                                {{ modalTitle }}
                            </DialogTitle>
                            <form
                                @submit.prevent="saveNewType"
                                class="flex flex-col md:flex-row items-center justify-center gap-6"
                            >
                                <!-- Form Details - Full width on mobile, right column on desktop -->
                                <div
                                    class="w-full mx-3 flex flex-col justify-between"
                                >
                                    <div
                                        v-if="
                                            appStore.typeFormAction != 'delete'
                                        "
                                        class="w-full mt-4"
                                    >
                                        <!-- Name -->
                                        <div
                                            class="mb-4 flex items-center justify-center"
                                        >
                                            <label
                                                for="name"
                                                class="block me-3 text-sm font-medium text-gray-700 mb-2 dark:text-white"
                                            >
                                                Name
                                            </label>
                                            <input
                                                ref="nameInput"
                                                type="text"
                                                id="name"
                                                v-model="typeForm.name"
                                                class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div
                                        v-else
                                        class="w-full mt-4 text-center space-y-4"
                                    >
                                        <p
                                            class="text-gray-700 dark:text-gray-200"
                                        >
                                            Are you sure you want to delete the
                                            type
                                            <span
                                                class="font-semibold text-red-600"
                                                >{{ typeForm.name }}</span
                                            >? Deleting this type will also
                                            remove all data associated with it.
                                            This action cannot be undone.
                                        </p>
                                    </div>
                                    <div>
                                        <div
                                            class="mt-6 flex justify-center space-x-3"
                                        >
                                            <button
                                                type="button"
                                                class="inline-flex justify-center cursor-pointer rounded-md border dark:text-white/75 border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:hover:bg-gray-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
                                                @click="closeModal"
                                            >
                                                Cancel
                                            </button>
                                            <button
                                                type="submit"
                                                :disabled="loading"
                                                class="inline-flex justify-center cursor-pointer rounded-md border border-transparent px-4 py-2 text-sm font-medium text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2"
                                                :class="
                                                    titleButton == 'Delete type'
                                                        ? 'bg-red-600 hover:bg-red-700 focus-visible:ring-red-500'
                                                        : 'bg-blue-600 hover:bg-blue-700 focus-visible:ring-blue-500'
                                                "
                                            >
                                                <span v-if="!loading">{{
                                                    titleButton
                                                }}</span>
                                                <span
                                                    v-else
                                                    class="flex items-center"
                                                >
                                                    <svg
                                                        class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <circle
                                                            class="opacity-25"
                                                            cx="12"
                                                            cy="12"
                                                            r="10"
                                                            stroke="currentColor"
                                                            stroke-width="4"
                                                        ></circle>
                                                        <path
                                                            class="opacity-75"
                                                            fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                                        ></path>
                                                    </svg>
                                                    Processing..
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
<script setup>
/* ==================== IMPORTS ==================== */
import { ref, computed, watch, nextTick } from "vue";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionRoot,
    TransitionChild,
} from "@headlessui/vue";
import { useAppStore } from "@/stores/useAppStore";
import { useToast } from "vue-toastification";

/* ==================== STORES & LIBS ==================== */
const appStore = useAppStore();
const toast = useToast();

/* ==================== STATE ==================== */
const loading = ref(false);
const nameInput = ref(null);
const baseUrl = document.querySelector('meta[name="base-url"]').content;
const currentMenu = ref('');
const typeForm = ref({
    id: 0,
    name: "",
});

/* ==================== COMPUTED ==================== */
const modalTitle = computed(() => {
    if (appStore.typeFormAction === "create") {
        return "Create New Type";
    } else if (appStore.typeFormAction === "update") {
        return "Update Type";
    } else {
        return "Delete Confirmation";
    }
});

const titleButton = computed(() => {
    if (appStore.typeFormAction === "create") {
        return "Add Type";
    } else if (appStore.typeFormAction === "update") {
        return "Update Type";
    } else {
        return "Delete Type";
    }
});

/* ==================== WATCHERS ==================== */
watch(
    () => appStore.isTypeModalOpen,
    (isOpen) => {
        if (isOpen && appStore.typeFormAction !== "delete") {
            nextTick(() => {
                nameInput.value?.focus();
            });
        }
    }
);

watch(
    () => appStore.selectedTypeData,
    (newVal) => {
      if (newVal) {
            typeForm.value.id = newVal.id;
            typeForm.value.name = newVal.name;
        }
    },
    { immediate: true }
);

watch(
    () => appStore.selectedDropdownMenu,
    (newVal) => {
      if (newVal) {
            currentMenu.value = newVal.name;
        }
    },
    { immediate: true }
);

/* ==================== METHODS ==================== */
const closeModal = () => {
    appStore.isTypeModalOpen = false;
    typeForm.value.id = 0;
    typeForm.value.name = "";
};

const saveNewType = async () => {
    try {
        loading.value = true;
        let message = "";
        if (currentMenu.value === "Toolstring Coiled Tubing") {
            if (appStore.typeFormAction === "update") {
                message = `Type ${typeForm.value.name} updated successfully!`;
                await axios.put(
                    `${baseUrl}/api/toolstring-types/${typeForm.value.id}`,
                    {
                        name: typeForm.value.name,
                    }
                );
                await appStore.getToolstringTypes();
                closeModal();
                toast.success(message);
                return;
            }

            if (appStore.typeFormAction === "delete") {
                message = `Type ${typeForm.value.name} deleted successfully!`;
                await axios.delete(
                    `${baseUrl}/api/toolstring-types/${typeForm.value.id}`
                );
                await appStore.getToolstringTypes();
                closeModal();
                toast.success(message);
                return;
            }

            // Default: create
            await axios.post(`${baseUrl}/api/toolstring-types`, {
                name: typeForm.value.name,
            });
            await appStore.getToolstringTypes();
        } else if (currentMenu.value === "Wellstack") {
            if (appStore.typeFormAction === "update") {
                message = `Type ${typeForm.value.name} updated successfully!`;
                await axios.put(
                    `${baseUrl}/api/wellstack-types/${typeForm.value.id}`,
                    {
                        name: typeForm.value.name,
                    }
                );
                await appStore.getWellstackTypes();
                closeModal();
                toast.success(message);
                return;
            }

            if (appStore.typeFormAction === "delete") {
                message = `Type ${typeForm.value.name} deleted successfully!`;
                await axios.delete(
                    `${baseUrl}/api/wellstack-types/${typeForm.value.id}`
                );
                await appStore.getWellstackTypes();
                closeModal();
                toast.success(message);
                return;
            }

            // Default: create
            await axios.post(`${baseUrl}/api/wellstack-types`, {
                name: typeForm.value.name,
            });
            await appStore.getWellstackTypes();
        }

        message = `Type ${typeForm.value.name} created successfully!`;
        closeModal();
        toast.success(message);
    } catch (error) {
        console.error("Error saving type:", error);
        toast.error("Failed to save type. Please try again.");
    } finally {
        loading.value = false;
    }
};
</script>
