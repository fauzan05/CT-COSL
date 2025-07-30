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
                <JobDescriptionInput v-model="jobTracker.job_descriptions" />
                <CustomerInput v-model="jobTracker.customer" />
                <BJDistrictInput v-model="jobTracker.bj_district" />
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Well Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Well Name
                        </label>
                        <input v-model="jobTracker.well_name" type="text"
                            class="w-full px-3 py-2 h-9 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter well name" />
                    </div>

                    <!-- Company Man -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Company Man
                        </label>
                        <input v-model="jobTracker.company_man" type="text"
                            class="w-full px-3 py-2 h-9 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter company man name" />
                    </div>

                    <!-- BJ Representative -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            BJ Representative
                        </label>
                        <input v-model="jobTracker.bj_representative" type="text"
                            class="w-full px-3 py-2 h-9 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter BJ representative name" />
                    </div>

                    <!-- Job Start Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Job Start Date
                        </label>
                        <input v-model="jobTracker.job_start_date" type="date"
                            class="w-full px-3 py-2 h-9 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <!-- Job Finish Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Job Finish Date
                        </label>
                        <input v-model="jobTracker.job_finish_date" type="date"
                            class="w-full px-3 py-2 h-9 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <!-- Job Days -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Job Days
                        </label>
                        <input v-model.number="jobTracker.job_days" type="number" min="0"
                            class="w-full px-3 py-2 h-9 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                <CasingLinerSizeInput v-model="jobTracker.casing_liner_size" />
                <CompletionSizeInput v-model="jobTracker.completion_size" />
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    <!-- Max Deviation -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Max Deviation
                        </label>
                        <input v-model.number="jobTracker.max_deviation" type="number" step="0.01" min="0"
                            class="w-full h-9 px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter max deviation" />
                    </div>

                    <!-- Dynamic Fields with Units -->
                    <div v-for="field in fieldsWithUnits" :key="field.key">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ field.label }}
                        </label>
                        <div class="flex gap-2">
                            <input v-model.number="jobTracker[field.valueKey]" type="number" step="0.01"
                                :min="field.min || 0"
                                class="flex-1 px-3 h-9 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :placeholder="field.placeholder" />

                            <Listbox v-model="jobTracker[field.unitKey]">
                                <div class="relative w-20">
                                    <ListboxButton
                                        class="relative w-full cursor-default rounded-lg bg-white dark:bg-gray-700 py-2 pl-3 pr-8 text-left border border-gray-300 dark:border-gray-600 focus:outline-none focus-visible:border-blue-500 focus-visible:ring-2 focus-visible:ring-blue-500 sm:text-sm">
                                        <span class="block truncate text-gray-900 dark:text-white">
                                            {{ jobTracker[field.unitKey] }}
                                        </span>
                                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                            <ChevronUpDownIcon class="h-4 w-4 text-gray-400" aria-hidden="true" />
                                        </span>
                                    </ListboxButton>

                                    <transition leave-active-class="transition duration-100 ease-in"
                                        leave-from-class="opacity-100" leave-to-class="opacity-0">
                                        <ListboxOptions
                                            class="absolute mt-1 max-h-60 w-full overflow-auto rounded-lg bg-white dark:bg-gray-700 py-1 text-base shadow-lg ring ring-gray-200 ring-opacity-5 focus:outline-none sm:text-sm z-10">
                                            <ListboxOption v-for="unit in field.units" :key="unit"
                                                v-slot="{ active, selected }" :value="unit" as="template">
                                                <li :class="[
                                                    active ? 'bg-blue-100 dark:bg-gray-600 text-blue-900 dark:text-white' : 'text-gray-900 dark:text-gray-300',
                                                    'relative cursor-default select-none py-2 pl-8 pr-4',
                                                ]">
                                                    <span
                                                        :class="[selected ? 'font-medium' : 'font-normal', 'block truncate']">
                                                        {{ unit }}
                                                    </span>
                                                    <span v-if="selected"
                                                        class="absolute inset-y-0 left-0 flex items-center pl-2 text-blue-600 dark:text-blue-400">
                                                        <CheckIcon class="h-4 w-4" aria-hidden="true" />
                                                    </span>
                                                </li>
                                            </ListboxOption>
                                        </ListboxOptions>
                                    </transition>
                                </div>
                            </Listbox>
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
                <NozzleTypeInput v-model="jobTracker.nozzle_type" />
                <MaxBHAODInput v-model="jobTracker.max_bha_od" />
                <MaxDepthInput class="mb-5" v-model="jobTracker.max_depths" />
                <ControlCabinInput v-model="jobTracker.control_cabin" />
                <PowerPackInput v-model="jobTracker.power_pack" />
                <PowerReelInput v-model="jobTracker.power_reel" />
                <CJInjectorInput v-model="jobTracker.cj_injector" />
                <BOPInput v-model="jobTracker.bop" />
                <CTSizeInput v-model="jobTracker.ct_size" />
                <CTGradeInput v-model="jobTracker.ct_grade" />
                <WTInput v-model="jobTracker.wt" />
                <CTStringInput v-model="jobTracker.ct_string" />
                <N2ConverterInput v-model="jobTracker.n2_converter" />
                <N2TankInput v-model="jobTracker.n2_tanks" />
                <ContainerInput v-model="jobTracker.containers" />
                <InjectorGoosneckInput v-model="jobTracker.injector_goosnecks" />
                <MiscellaneousToolInput v-model="jobTracker.miscellaneous_tools" />
            </div>

            <!-- Personnel Section -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                <h3
                    class="text-lg font-semibold text-gray-800 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                    Personnel
                </h3>
                <CTSupervisorInput v-model="jobTracker.ct_supervisor" />
                <CTPersonnelInput class="mb-5" v-model="jobTracker.ct_personnels" />
                <NitrogenSupervisorInput v-model="jobTracker.nitrogen_supervisor" />
                <NitrogenPersonnelInput class="mb-5" v-model="jobTracker.nitrogen_personnels" />
                <!-- Pump Supervisor -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Pump Supervisor
                    </label>
                    <input v-model="jobTracker.pump_supervisor" type="text"
                        class="w-full px-3 py-2 h-9 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter pump supervisor" />
                </div>
                <PumpPersonnelInput class="mb-5" v-model="jobTracker.pump_personnels" />
            </div>

            <!-- Treatment Section -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                <h3
                    class="text-lg font-semibold text-gray-800 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                    Treatment
                </h3>
                <div class="bg-white mb-5 dark:bg-slate-700 rounded-xl shadow-sm p-6">
                    <!-- Main Title -->
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                        Acid Treatment
                    </h3>

                    <!-- Grid Layout -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Acid Type Section -->
                        <div class="space-y-4">
                            <AcidTypeInput v-model="jobTracker.acid_types" />
                        </div>

                        <!-- Acid Volume Section -->
                        <div class="space-y-4">
                            <AcidVolumeInput v-model="jobTracker.acid_volumes" />
                        </div>
                    </div>
                </div>
                <!-- Nitrogen Volume -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nitrogen Volume
                    </label>
                    <div class="flex space-x-2 mt-3">
                        <input v-model.number="jobTracker.nitrogen_volume" type="number" step="0.01" min="0"
                            class="flex-1 px-3 py-2 h-9 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Volume" />
                        <Listbox v-model="jobTracker.nitrogen_volume_unit">
                            <div class="relative w-24">
                                <ListboxButton
                                    class="relative w-full cursor-default rounded-lg bg-white dark:bg-gray-700 py-2 pl-3 pr-10 text-left focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm border border-gray-300 dark:border-gray-600 dark:text-white">
                                    <span class="block truncate">{{ jobTracker.nitrogen_volume_unit }}</span>
                                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                                    </span>
                                </ListboxButton>

                                <transition leave-active-class="transition duration-100 ease-in"
                                    leave-from-class="opacity-100" leave-to-class="opacity-0">
                                    <ListboxOptions
                                        class="absolute mt-1 max-h-60 w-full overflow-auto rounded-lg bg-white dark:bg-gray-700 py-1 text-base shadow-lg ring-1 ring-gray-200 ring-opacity-5 focus:outline-none sm:text-sm z-10">
                                        <ListboxOption v-slot="{ active, selected }" v-for="unit in volumeUnits"
                                            :key="unit.value" :value="unit.value" as="template">
                                            <li :class="[
                                                active ? 'bg-blue-100 text-blue-900 dark:bg-gray-600 dark:text-white' : 'text-gray-900 dark:text-gray-100',
                                                'relative cursor-default select-none py-2 pl-10 pr-4',
                                            ]">
                                                <span :class="[
                                                    selected ? 'font-medium' : 'font-normal',
                                                    'block truncate',
                                                ]">{{ unit.label }}</span>
                                                <span v-if="selected"
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-blue-600 dark:text-blue-400">
                                                    <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                                </span>
                                            </li>
                                        </ListboxOption>
                                    </ListboxOptions>
                                </transition>
                            </div>
                        </Listbox>
                    </div>
                </div>
                <!-- Cement Volume -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Cement Volume
                    </label>
                    <div class="flex space-x-2">
                        <input v-model.number="jobTracker.cement_volume" type="number" step="0.01" min="0"
                            class="flex-1 px-3 py-2 border h-9 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Volume" />
                        <Listbox v-model="jobTracker.cement_volume_unit">
                            <div class="relative w-24">
                                <ListboxButton
                                    class="relative w-full cursor-default rounded-lg bg-white dark:bg-gray-700 py-2 pl-3 pr-10 text-left focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm border border-gray-300 dark:border-gray-600 dark:text-white">
                                    <span class="block truncate">{{ jobTracker.cement_volume_unit }}</span>
                                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                                    </span>
                                </ListboxButton>

                                <transition leave-active-class="transition duration-100 ease-in"
                                    leave-from-class="opacity-100" leave-to-class="opacity-0">
                                    <ListboxOptions
                                        class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-gray-700 py-1 text-base shadow-lg ring-1 ring-gray-200 ring-opacity-5 focus:outline-none sm:text-sm z-10">
                                        <ListboxOption v-slot="{ active, selected }" v-for="unit in cementVolumeUnits"
                                            :key="unit.value" :value="unit.value" as="template">
                                            <li :class="[
                                                active ? 'bg-blue-100 text-blue-900 dark:bg-gray-600 dark:text-white' : 'text-gray-900 dark:text-gray-100',
                                                'relative cursor-default select-none py-2 pl-10 pr-4',
                                            ]">
                                                <span :class="[
                                                    selected ? 'font-medium' : 'font-normal',
                                                    'block truncate',
                                                ]">{{ unit.label }}</span>
                                                <span v-if="selected"
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-blue-600 dark:text-blue-400">
                                                    <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                                </span>
                                            </li>
                                        </ListboxOption>
                                    </ListboxOptions>
                                </transition>
                            </div>
                        </Listbox>
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
                    <Listbox v-model="jobTracker.revenue_currency">
                        <div class="relative w-32">
                            <ListboxButton
                                class="relative w-full cursor-default rounded-lg bg-white dark:bg-gray-700 py-2 pl-3 pr-10 text-left focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm border border-gray-300 dark:border-gray-600 dark:text-white">
                                <span class="block truncate">{{ jobTracker.revenue_currency }}</span>
                                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                                </span>
                            </ListboxButton>

                            <transition leave-active-class="transition duration-100 ease-in" leave-from-class="opacity-100"
                                leave-to-class="opacity-0">
                                <ListboxOptions
                                    class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-gray-700 py-1 text-base shadow-lg ring-1 ring-gray-200 ring-opacity-5 focus:outline-none sm:text-sm z-10">
                                    <ListboxOption v-slot="{ active, selected }" v-for="currency in revenueCurrencies"
                                        :key="currency.value" :value="currency.value" as="template">
                                        <li :class="[
                                            active ? 'bg-blue-100 text-blue-900 dark:bg-gray-600 dark:text-white' : 'text-gray-900 dark:text-gray-100',
                                            'relative cursor-default select-none py-2 pl-10 pr-4',
                                        ]">
                                            <span :class="[
                                                selected ? 'font-medium' : 'font-normal',
                                                'block truncate',
                                            ]">{{ currency.label }}</span>
                                            <span v-if="selected"
                                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-blue-600 dark:text-blue-400">
                                                <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                            </span>
                                        </li>
                                    </ListboxOption>
                                </ListboxOptions>
                            </transition>
                        </div>
                    </Listbox>
                </div>

                <!-- Revenue Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-3">
                    <div class="bg-white mb-5 dark:bg-slate-700 rounded-xl shadow-sm p-6">
                        <!-- Main Title -->
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                            Equipment
                        </h3>

                        <!-- Grid Layout -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
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
                                    Nitrogen Revenue
                                </label>
                                <input v-model.number="jobTracker.revenue_nitrogen_equipment" type="number" step="0.01"
                                    min="0"
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
                        </div>
                    </div>
                    <div class="bg-white mb-5 dark:bg-slate-700 rounded-xl shadow-sm p-6">
                        <!-- Main Title -->
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                            Products
                        </h3>

                        <!-- Grid Layout -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
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
                                <input v-model.number="jobTracker.revenue_nitrogen_product" type="number" step="0.01"
                                    min="0"
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
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Personnel Charges
                    </label>
                    <input v-model.number="jobTracker.personnel_charges" type="number" step="0.01" min="0"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="0.00" />
                </div>

                <div class="mb-5">
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
import { ref, computed, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import {
    Listbox,
    ListboxButton,
    ListboxOption,
    ListboxOptions
} from '@headlessui/vue'
import {
    ChevronUpDownIcon, CheckIcon
} from '@heroicons/vue/20/solid';
import { useToast } from 'vue-toastification'
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
import NozzleTypeInput from "../../forms/JobTrackers/NozzleTypeInput.vue";
import MaxBHAODInput from "../../forms/JobTrackers/MaxBHAODInput.vue";
import ControlCabinInput from "../../forms/JobTrackers/ControlCabinInput.vue";
import PowerPackInput from "../../forms/JobTrackers/PowerPackInput.vue";
import PowerReelInput from "../../forms/JobTrackers/PowerReelInput.vue";
import CJInjectorInput from "../../forms/JobTrackers/CJInjectorInput.vue";
import BOPInput from "../../forms/JobTrackers/BOPInput.vue";
import CTSizeInput from "../../forms/JobTrackers/CTSizeInput.vue";
import CTGradeInput from "../../forms/JobTrackers/CTGradeInput.vue";
import WTInput from "../../forms/JobTrackers/WTInput.vue";
import CTStringInput from "../../forms/JobTrackers/CTStringInput.vue";
import N2ConverterInput from "../../forms/JobTrackers/N2ConverterInput.vue";
import N2TankInput from "../../forms/JobTrackers/N2TankInput.vue";
import ContainerInput from "../../forms/JobTrackers/ContainerInput.vue";
import InjectorGoosneckInput from "../../forms/JobTrackers/InjectorGoosneckInput.vue";
import MiscellaneousToolInput from "../../forms/JobTrackers/MiscellaneousToolInput.vue";
import CTSupervisorInput from "../../forms/JobTrackers/CTSupervisorInput.vue";
import NitrogenSupervisorInput from "../../forms/JobTrackers/NitrogenSupervisorInput.vue";
import NitrogenPersonnelInput from "../../forms/JobTrackers/NitrogenPersonnelInput.vue";
import PumpPersonnelInput from "../../forms/JobTrackers/PumpPersonnelInput.vue";
import AcidTypeInput from "../../forms/JobTrackers/AcidTypeInput.vue";
import AcidVolumeInput from "../../forms/JobTrackers/AcidVolumeInput.vue";

const route = useRoute();
// Form state
const isSubmitting = ref(false);
const isEdit = computed(() => !!route.params.id);
const baseUrl = import.meta.env.VITE_API_URL
// toast
const toast = useToast();

// Form data
const jobTracker = ref({
    job_descriptions: [],
    well_name: "",
    company_man: "",
    bj_representative: "",
    job_start_date: "",
    job_finish_date: "",
    job_days: 0,
    max_deviation: 0,
    max_depths: [
        {
            value: 0,
            unit: "ft",
        }
    ],
    customer: "",
    bj_district: "",
    field_location: "",
    field_type: "",
    well_status: "",
    well_type: "",
    wellhead_x_over: "",
    casing_liner_size: {
        size: '',
        unit: "in",
    },
    completion_size: {
        size: '',
        unit: "in",
    },
    nozzle_type: "",
    max_bha_od: {
        size: '',
        unit: "in",
    },
    control_cabin: "",
    power_pack: "",
    power_reel: "",
    cj_injector: "",
    bop: "",
    ct_size: {
        size: '',
        unit: "in",
    },
    ct_grade: "",
    wt: {
        size: '',
        unit: "in",
    },
    ct_string: "",
    n2_converter: "",
    n2_tanks: [],
    containers: [],
    injector_goosnecks: [],
    miscellaneous_tools: [],
    ct_supervisor: "",
    ct_personnels: [],
    nitrogen_supervisor: "",
    nitrogen_personnels: [],
    pump_supervisor: "",
    pump_personnels: [
        {
            value: ''
        }
    ],
    acid_types: [
        {
            value: ''
        }
    ],
    acid_volumes: [
        {
            value: 0,
            unit: "Gals",
        }
    ],
    depth_md: 0,
    depth_md_unit: "ft",
    depth_tvd: 0,
    depth_tvd_unit: "ft",
    bh_pressure: 0,
    bh_pressure_unit: "psi",
    bh_temp: 0,
    bh_temp_unit: "°F",
    nitrogen_volume: 0,
    nitrogen_volume_unit: "Gals",
    cement_volume: 0,
    cement_volume_unit: "Bbls",
    revenue_currency: "USD",
    revenue_coiled_tubing: 0,
    revenue_pumping: 0,
    revenue_special_tools: 0,
    revenue_acid: 0,
    revenue_nitrogen_equipment: 0,
    revenue_nitrogen_product: 0,
    revenue_cement: 0,
    personnel_charges: 0,
    service_charges: 0,
    other_charges: 0,
    total_revenue: 0
});

// Fields configuration
const fieldsWithUnits = ref([
    {
        key: 'depth_md',
        label: 'Depth MD',
        valueKey: 'depth_md',
        unitKey: 'depth_md_unit',
        placeholder: 'Depth',
        units: ['ft', 'm'],
        defaultUnit: 'ft'
    },
    {
        key: 'depth_tvd',
        label: 'Depth TVD',
        valueKey: 'depth_tvd',
        unitKey: 'depth_tvd_unit',
        placeholder: 'TVD',
        units: ['ft', 'm'],
        defaultUnit: 'ft'
    },
    {
        key: 'bh_pressure',
        label: 'BH Pressure',
        valueKey: 'bh_pressure',
        unitKey: 'bh_pressure_unit',
        placeholder: 'Pressure',
        units: ['psi', 'bar', 'kPa'],
        defaultUnit: 'psi'
    },
    {
        key: 'bh_temp',
        label: 'BH Temperature',
        valueKey: 'bh_temp',
        unitKey: 'bh_temp_unit',
        placeholder: 'Temperature',
        units: ['°F', '°C'],
        defaultUnit: '°F',
        min: undefined // No minimum for temperature
    }
])

const volumeUnits = [
    { value: 'Gals', label: 'Gals' },
    { value: 'Liters', label: 'Liters' },
    { value: 'Bbls', label: 'Bbls' },
]

const cementVolumeUnits = [
    { value: 'Bbls', label: 'Bbls' },
    { value: 'Gals', label: 'Gals' },
    { value: 'Liters', label: 'Liters' },
]

const revenueCurrencies = [
    { value: 'USD', label: 'USD' },
    { value: 'EUR', label: 'EUR' },
    { value: 'GBP', label: 'GBP' },
    { value: 'IDR', label: 'IDR' },
]

// Computed properties
const totalRevenue = computed(() => {
    const revenues = [
        jobTracker.value.revenue_coiled_tubing || 0,
        jobTracker.value.revenue_pumping || 0,
        jobTracker.value.revenue_special_tools || 0,
        jobTracker.value.revenue_acid || 0,
        jobTracker.value.revenue_nitrogen_product || 0,
        jobTracker.value.revenue_nitrogen_equipment || 0,
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
        // cek apakah ini edit atau create
        console.log("Submitting job tracker data:", jobTracker.value);
        if (isEdit.value) {
            // Update existing job tracker
            await axios.put(`${baseUrl}/api/job-trackers/${route.params.id}`, jobTracker.value);
            toast.success("Job tracker updated successfully!");
        } else {
            // Create new job tracker
            // use csrf
            await axios.post(`${baseUrl}/api/job-trackers`, jobTracker.value);
            toast.success("Job tracker created successfully!");
        }
    } catch (error) {
        console.error("Error saving job tracker:", error);
        toast.error("Failed to save job tracker data.");
    } finally {
        isSubmitting.value = false;
    }
};

const loadJobTracker = async (id) => {
    try {
        // load from api
        let response = await axios.get(`${baseUrl}/api/job-trackers/${id}`);
        jobTracker.value = response.data;
        // remove time in job_start_date and job_finish_date and matching the timezone
        jobTracker.value.job_start_date = new Date(jobTracker.value.job_start_date).toISOString().split('T')[0];
        jobTracker.value.job_finish_date = new Date(jobTracker.value.job_finish_date).toISOString().split('T')[0];

        // set acid types to jobTracker
        jobTracker.value.acid_types = jobTracker.value.acid_types.map(acid => ({
            value: acid.value
        }));

    } catch (error) {
        console.error("Error loading job tracker:", error);
    }
};

// Lifecycle
// onMounted(async () => {
//     if (isEdit.value && route.params.id) {
//         await loadJobTracker(route.params.id);
//     }
// });

watch(
  () => route.params.id,
  async (newId) => {
    if (isEdit.value && newId) {
      await loadJobTracker(newId);
    }
  },
  { immediate: true } // agar langsung jalan saat pertama kali mount juga
);

</script>
