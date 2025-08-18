<template>
    <div class="p-6 bg-gray-50 min-h-screen dark:bg-slate-900/50 dark:text-gray-100 rounded-xl">
        <div class="w-full px-4 py-6">
            <TabGroup>
                <TabList class="flex space-x-1 rounded-xl bg-slate-100 dark:bg-slate-800/50 p-1">
                    <Tab v-for="menu in menus" :key="menu.id" v-slot="{ selected }" as="template">
                        <button :class="[
                            'w-full text-center rounded-lg py-2.5 text-sm font-medium leading-5',
                            'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
                            selected
                                ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-white/[0.12] hover:text-blue-600'
                        ]">
                            {{ menu.name }}
                        </button>
                    </Tab>
                </TabList>
                <TabPanels class="mt-6">
                    <TabPanel>
                        <ToolstringCoiledTubingPanel />
                    </TabPanel>

                    <TabPanel>
                        <WellstackPanel />
                    </TabPanel>
                </TabPanels>
            </TabGroup>
        </div>
    </div>
</template>

<script setup>
import {
    TabGroup, TabList, Tab, TabPanels, TabPanel
} from '@headlessui/vue';

import ToolstringCoiledTubingPanel from './ReportsTab/ToolstringCoiledTubingPanel.vue';
import WellstackPanel from './ReportsTab/WellstackPanel.vue';
const menus = [
    { id: 1, name: 'Toolstring Coiled Tubing' },
    { id: 2, name: 'Wellstack' },
];
</script>