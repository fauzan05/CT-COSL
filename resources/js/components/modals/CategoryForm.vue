<template>
    <!-- modal create new category -->
    <TransitionRoot appear :show="appStore.isCategoryModalOpen" as="template">
        <Dialog as="div" @close="closeModal" class="relative z-50">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
                leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" />
            </TransitionChild>
            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95">
                        <DialogPanel
                            class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800/80 p-6 text-left align-middle shadow-xl transition-all">
                            <!-- Tombol silang di sudut kanan atas -->
                            <button @click="closeModal"
                                class="absolute top-4 right-6 text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-white transition-colors text-2xl leading-none"
                                aria-label="Close modal">
                                &times;
                            </button>
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4 dark:text-white">
                                <i class="fa-solid fa-folder-plus"></i> &nbsp; {{ modalTitle }}
                            </DialogTitle>
                            <form @submit.prevent="saveNewCategory"
                                class="flex flex-col md:flex-row items-center justify-center gap-6">
                                <!-- Form Details - Full width on mobile, right column on desktop -->
                                <div class="w-full mx-3 flex flex-col justify-between">
                                    <div v-if="appStore.categoryFormAction != 'delete'" class="w-full mt-4">
                                        <!-- Name -->
                                        <div class="mb-4 flex items-center justify-center">
                                            <label for="name"
                                                class="block me-3 text-sm font-medium text-gray-700 mb-2 dark:text-white">
                                                Name
                                            </label>
                                            <input type="text" id="name" v-model="categoryForm.name"
                                                class="w-full px-3 dark:text-white py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                required>
                                        </div>
                                    </div>
                                    <div v-else class="w-full mt-4 text-center space-y-4">
                                        <p class="text-gray-700 dark:text-gray-200">
                                            Are you sure you want to delete the category
                                            <span class="font-semibold text-red-600">{{ categoryForm.name }}</span>?
                                            Deleting this category will also remove all data associated with it. This action
                                            cannot be undone.
                                        </p>
                                    </div>
                                    <div>
                                        <div class="mt-6 flex justify-center space-x-3">
                                            <button type="button"
                                                class="inline-flex justify-center cursor-pointer rounded-md border dark:text-white/75 border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:hover:bg-gray-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
                                                @click="closeModal">
                                                Cancel
                                            </button>
                                            <button type="submit" :disabled="loading"
                                                class="inline-flex justify-center cursor-pointer rounded-md border border-transparent px-4 py-2 text-sm font-medium text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2"
                                                :class="titleButton == 'Delete Category' ? 'bg-red-600 hover:bg-red-700 focus-visible:ring-red-500' : 'bg-blue-600 hover:bg-blue-700 focus-visible:ring-blue-500'">
                                                <span v-if="!loading">{{ titleButton }}</span>
                                                <span v-else class="flex items-center">
                                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                            stroke="currentColor" stroke-width="4">
                                                        </circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
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
import { ref, computed, watch, nextTick } from 'vue';
import { Dialog, DialogPanel, DialogTitle, TransitionRoot, TransitionChild } from '@headlessui/vue';
import { useAppStore } from '@/stores/useAppStore';
import { useToast } from 'vue-toastification'
const toast = useToast()
const appStore = useAppStore();

const loading = ref(false);
const baseUrl = document.querySelector('meta[name="base-url"]').content;
const categoryForm = ref({
    id: 0,
    name: ""
});

watch(() => appStore.selectedCategoryData, async (newVal) => {
    if (newVal) {
        categoryForm.value.id = newVal.id;
        categoryForm.value.name = newVal.name;
    }
}, { immediate: true });

const modalTitle = computed(() => {
    if (appStore.categoryFormAction === 'create') {
        return 'Create New Category';
    } else if (appStore.categoryFormAction === 'update') {
        return 'Update Category';
    } else {
        return 'Delete Confirmation';
    }
});

const titleButton = computed(() => {
    if (appStore.categoryFormAction === 'create') {
        return 'Add Category';
    } else if (appStore.categoryFormAction === 'update') {
        return 'Update Category';
    } else {
        return 'Delete Category';
    }
});

const saveNewCategory = async () => {
    try {
        let message = '';
        loading.value = true
        if (appStore.categoryFormAction === 'update') {
            message = `Category ${categoryForm.value.name} updated successfully!`
            await axios.put(`${baseUrl}/api/toolstring-categories/${categoryForm.value.id}`, {
                name: categoryForm.value.name,
            });
            await appStore.getToolstringCategories()
            closeModal()
            toast.success(message)
            return
        } else if (appStore.categoryFormAction === 'delete') {
            message = `Category ${categoryForm.value.name} deleted successfully!`
            await axios.delete(`${baseUrl}/api/toolstring-categories/${categoryForm.value.id}`);
            await appStore.getToolstringCategories()
            closeModal()
            toast.success(message)
            return
        }
        // Default action is create
        await axios.post(`${baseUrl}/api/toolstring-categories`, {
            name: categoryForm.value.name,
        });
        await appStore.getToolstringCategories()

        message = `Category ${categoryForm.value.name} created successfully!`
        closeModal()
        toast.success(message)
    } catch (error) {
        console.error('Error creating category:', error)
        toast.error('Failed to create category. Please try again.')
    } finally {
        loading.value = false
    }
}

const closeModal = () => {
    appStore.isCategoryModalOpen = false;
    categoryForm.value.id = 0
    categoryForm.value.name = ""
}

</script>
<style lang="">
</style>