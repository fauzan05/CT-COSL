<template>
    <div class="min-h-screen bg-gray-50 dark:bg-slate-900/50 p-6">
        <!-- Header dengan tombol back -->
        <div class="mb-8 bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm">
            <div class="flex items-center space-x-4">
                <button @click="$router.back()"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-200 rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </button>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                        {{
                            isEdit
                            ? "Edit Job Tracker"
                            : "Create New Job Tracker"
                        }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        {{ isEdit ? 'Update the job tracker details below.'
                            : 'Fill in the details below to create a new job tracker.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Form content -->
        <form @submit.prevent="handleSubmit" class="space-y-6">
            <!-- General Information Section -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                <h3
                    class="text-lg font-semibold text-gray-800 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                    General Information
                </h3>
                <!-- Job Description -->
                <JobDescriptionInput v-model="jobTracker.job_description" />
                <CustomerInput v-model="jobTracker.customer" />
                <BJDistrictInput v-model="jobTracker.bj_district" />
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Well Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Well Name
                        </label>
                        <input v-model="jobTracker.well_name" type="text" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter well name" />
                    </div>

                    <!-- Company Man -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Company Man
                        </label>
                        <input v-model="jobTracker.company_man" type="text"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter company man name" />
                    </div>

                    <!-- BJ Representative -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            BJ Representative
                        </label>
                        <input v-model="jobTracker.bj_representative" type="text"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter BJ representative name" />
                    </div>

                    <!-- Job Start Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Job Start Date
                        </label>
                        <input v-model="jobTracker.job_start_date" type="date"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <!-- Job Finish Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Job Finish Date
                        </label>
                        <input v-model="jobTracker.job_finish_date" type="date"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <!-- Job Days -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Job Days
                        </label>
                        <input v-model.number="jobTracker.job_days" type="number" min="0"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter number of days" />
                    </div>
                </div>
            </div>

            <!-- Well Information Section -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                <h3
                    class="text-lg font-semibold text-gray-800 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                    Well Information
                </h3>
                <FieldLocationInput v-model="jobTracker.field_location" />
                <FieldTypeInput v-model="jobTracker.field_type" />
                <WellStatusInput v-model="jobTracker.well_status" />
                <WellTypeInput v-model="jobTracker.well_type" />
                <WellheadXOverInput v-model="jobTracker.wellhead_x_over" />
                <CasingLinerSizeInput v-model="jobTracker.casing_linear_size" />
                <CompletionSizeInput v-model="jobTracker.completion_size" />
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Max Deviation -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Max Deviation
                        </label>
                        <input v-model.number="jobTracker.max_deviation" type="number" step="0.01" min="0"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter max deviation" />
                    </div>

                    <!-- Depth MD -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Depth MD
                        </label>
                        <div class="flex space-x-2">
                            <input v-model.number="jobTracker.depth_md" type="number" step="0.01" min="0"
                                class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Depth" />
                            <select v-model="jobTracker.depth_md_unit"
                                class="w-20 px-2 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="ft">ft</option>
                                <option value="m">m</option>
                            </select>
                        </div>
                    </div>

                    <!-- Depth TVD -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Depth TVD
                        </label>
                        <div class="flex space-x-2">
                            <input v-model.number="jobTracker.depth_tvd" type="number" step="0.01" min="0"
                                class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="TVD" />
                            <select v-model="jobTracker.depth_tvd_unit"
                                class="w-20 px-2 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="ft">ft</option>
                                <option value="m">m</option>
                            </select>
                        </div>
                    </div>

                    <!-- BH Pressure -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            BH Pressure
                        </label>
                        <div class="flex space-x-2">
                            <input v-model.number="jobTracker.bh_pressure" type="number" step="0.01" min="0"
                                class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Pressure" />
                            <select v-model="jobTracker.bh_pressure_unit"
                                class="w-20 px-2 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="psi">psi</option>
                                <option value="bar">bar</option>
                                <option value="kPa">kPa</option>
                            </select>
                        </div>
                    </div>

                    <!-- BH Temperature -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            BH Temperature
                        </label>
                        <div class="flex space-x-2">
                            <input v-model.number="jobTracker.bh_temp" type="number" step="0.01"
                                class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Temperature" />
                            <select v-model="jobTracker.bh_temp_unit"
                                class="w-20 px-2 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="°F">°F</option>
                                <option value="°C">°C</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Equipment and Tools Section -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                <h3
                    class="text-lg font-semibold text-gray-800 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                    Equipment and Tools
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <MaxDepthInput v-model="jobTracker.max_depths" />
                </div>
            </div>

            <!-- Personnel Section -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                <h3
                    class="text-lg font-semibold text-gray-800 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                    Personnel
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <CTPersonnelInput v-model="jobTracker.ct_personnels" />
                </div>
            </div>

            <!-- Treatment Section -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                <h3
                    class="text-lg font-semibold text-gray-800 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                    Treatment
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nitrogen Volume -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nitrogen Volume
                        </label>
                        <div class="flex space-x-2">
                            <input v-model.number="jobTracker.nitrogen_volume" type="number" step="0.01" min="0"
                                class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Volume" />
                            <select v-model="jobTracker.nitrogen_volume_unit"
                                class="w-24 px-2 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="Gals">Gals</option>
                                <option value="Liters">Liters</option>
                                <option value="Bbls">Bbls</option>
                            </select>
                        </div>
                    </div>

                    <!-- Cement Volume -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Cement Volume
                        </label>
                        <div class="flex space-x-2">
                            <input v-model.number="jobTracker.cement_volume" type="number" step="0.01" min="0"
                                class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Volume" />
                            <select v-model="jobTracker.cement_volume_unit"
                                class="w-24 px-2 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="Bbls">Bbls</option>
                                <option value="Gals">Gals</option>
                                <option value="Liters">Liters</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Section -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                <h3
                    class="text-lg font-semibold text-gray-800 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                    Revenue Information
                </h3>

                <!-- Currency Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Currency
                    </label>
                    <select v-model="jobTracker.revenue_currency"
                        class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                        <option value="GBP">GBP</option>
                        <option value="IDR">IDR</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Revenue Fields -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Coiled Tubing Revenue
                        </label>
                        <input v-model.number="jobTracker.revenue_coiled_tubing" type="number" step="0.01" min="0"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="0.00" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Pumping Revenue
                        </label>
                        <input v-model.number="jobTracker.revenue_pumping" type="number" step="0.01" min="0"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="0.00" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Special Tools Revenue
                        </label>
                        <input v-model.number="jobTracker.revenue_special_tools" type="number" step="0.01" min="0"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="0.00" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Acid Revenue
                        </label>
                        <input v-model.number="jobTracker.revenue_acid" type="number" step="0.01" min="0"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="0.00" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nitrogen Revenue
                        </label>
                        <input v-model.number="jobTracker.revenue_nitrogen" type="number" step="0.01" min="0"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="0.00" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Cement Revenue
                        </label>
                        <input v-model.number="jobTracker.revenue_cement" type="number" step="0.01" min="0"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="0.00" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Personnel Charges
                        </label>
                        <input v-model.number="jobTracker.personnel_charges" type="number" step="0.01" min="0"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="0.00" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Service Charges
                        </label>
                        <input v-model.number="jobTracker.service_charges" type="number" step="0.01" min="0"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="0.00" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Other Charges
                        </label>
                        <input v-model.number="jobTracker.other_charges" type="number" step="0.01" min="0"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="0.00" />
                    </div>
                </div>

                <!-- Total Revenue (Read-only, calculated) -->
                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border-l-4 border-blue-500">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold text-gray-800 dark:text-white">Total Revenue:</span>
                        <span class="text-xl font-bold text-blue-600 dark:text-blue-400">
                            {{ jobTracker.revenue_currency }}
                            {{ formatCurrency(totalRevenue) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                <button type="button" @click="$router.back()"
                    class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                    Cancel
                </button>
                <button type="submit" :disabled="isSubmitting"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white rounded-lg transition-colors duration-200 flex items-center space-x-2">
                    <svg v-if="isSubmitting" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span>{{
                        isSubmitting
                        ? "Saving..."
                        : isEdit
                            ? "Update"
                            : "Create"
                    }}</span>
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import JobDescriptionInput from "../../forms/JobTrackers/JobDescriptionInput.vue";
import MaxDepthInput from "../../forms/JobTrackers/MaxDepthInput.vue";
import CTPersonnelInput from "../../forms/JobTrackers/CTPersonnelInput.vue";
import CustomerInput from "../../forms/JobTrackers/CustomerInput.vue";
import BJDistrictInput from "../../forms/JobTrackers/BJDistrictInput.vue";
import FieldLocationInput from "../../forms/JobTrackers/FieldLocationInput.vue";
import FieldTypeInput from "../../forms/JobTrackers/FieldTypeInput.vue";
import WellStatusInput from "../../forms/JobTrackers/WellStatusInput.vue";
import WellTypeInput from "../../forms/JobTrackers/WellTypeInput.vue";
import WellheadXOverInput from "../../forms/JobTrackers/WellheadXOverInput.vue";
import CasingLinerSizeInput from "../../forms/JobTrackers/CasingLinerSizeInput.vue";
import CompletionSizeInput from "../../forms/JobTrackers/CompletionSizeInput.vue";
const router = useRouter();
const route = useRoute();

// Form state
const isSubmitting = ref(false);
const isEdit = computed(() => !!route.params.id);

// Form data
const jobTracker = ref({
    job_description: [],
    well_name: "",
    company_man: "",
    bj_representative: "",
    job_start_date: "",
    job_finish_date: "",
    job_days: null,
    max_deviation: null,
    max_depths: [
        {
            value: 0,
            unit: "ft",
        }
    ],
    ct_personnels: [
        {
            name: "",
        }
    ],
    customer: "",
    bj_district: "",
    field_location: "",
    field_type: "",
    well_status: "",
    well_type: "",
    wellhead_x_over: "",
    casing_linear_size: {
        size: "",
        unit: "in",
    },
    completion_size: {
        size: "",
        unit: "in",
    },
    depth_md: null,
    depth_md_unit: "ft",
    depth_tvd: null,
    depth_tvd_unit: "ft",
    bh_pressure: null,
    bh_pressure_unit: "psi",
    bh_temp: null,
    bh_temp_unit: "°F",
    nitrogen_volume: null,
    nitrogen_volume_unit: "Gals",
    cement_volume: null,
    cement_volume_unit: "Bbls",
    revenue_currency: "USD",
    revenue_coiled_tubing: null,
    revenue_pumping: null,
    revenue_special_tools: null,
    revenue_acid: null,
    revenue_nitrogen: null,
    revenue_cement: null,
    personnel_charges: null,
    service_charges: null,
    other_charges: null,
});

// Computed properties
const totalRevenue = computed(() => {
    const revenues = [
        jobTracker.value.revenue_coiled_tubing || 0,
        jobTracker.value.revenue_pumping || 0,
        jobTracker.value.revenue_special_tools || 0,
        jobTracker.value.revenue_acid || 0,
        jobTracker.value.revenue_nitrogen || 0,
        jobTracker.value.revenue_cement || 0,
        jobTracker.value.personnel_charges || 0,
        jobTracker.value.service_charges || 0,
        jobTracker.value.other_charges || 0,
    ];
    return revenues.reduce((sum, value) => sum + Number(value), 0);
});

// Methods
const formatCurrency = (amount) => {
    return new Intl.NumberFormat("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(amount || 0);
};

const handleSubmit = async () => {
    try {
        isSubmitting.value = true;

        // Add total revenue to form data
        const formData = {
            ...jobTracker.value,
            total_revenue: totalRevenue.value,
        };

        if (isEdit.value) {
            // Update existing job tracker
            console.log("Updating job tracker:", formData);
            // await updateJobTracker(route.params.id, formData);
        } else {
            // Create new job tracker
            console.log("Creating job tracker:", formData);
            // await createJobTracker(formData);
        }

        // Success feedback
        alert(
            isEdit.value
                ? "Job tracker updated successfully!"
                : "Job tracker created successfully!"
        );

        // Navigate back to list
        router.push({ name: "job-tracker-list" });
    } catch (error) {
        console.error("Error saving job tracker:", error);
        alert("Error saving job tracker. Please try again.");
    } finally {
        isSubmitting.value = false;
    }
};

const loadJobTracker = async (id) => {
    try {
        // Load existing job tracker data for editing
        console.log("Loading job tracker:", id);
        // const data = await getJobTracker(id);
        // Object.assign(jobTracker.value, data);
    } catch (error) {
        console.error("Error loading job tracker:", error);
        alert("Error loading job tracker data.");
    }
};

// Lifecycle
onMounted(() => {
    if (isEdit.value && route.params.id) {
        loadJobTracker(route.params.id);
    }
});
</script>
