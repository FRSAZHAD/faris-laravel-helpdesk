<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Skeleton from '@/components/ui/skeleton/Skeleton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard, InsertStaff } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { h } from 'vue';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

import { useStaff } from '@/composables/useStaff';
import { useCategory } from '@/composables/useCategory';

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'List Staff', href: '' },
];

const { data: staff, isLoading } = useStaff();
const { data: Category } = useCategory();
</script>

<template>
    <Head title="Staff List" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- PrimeVue DataTable -->
        <div class="p-4">
            <h2 class="mb-4 text-xl font-semibold">Administration</h2>
            <DataTable
                v-if="!isLoading"
                :value="staff"
                responsiveLayout="scroll"
                class="w-full shadow-sm border border-gray-200 rounded-lg"
            >
            
            <div class="p-4 text-right">
                <Link :href="InsertStaff()">
                    <Button class="bg-blue-500 hover:bg-blue-700 text-white rounded px-4 py-2">
                        Add New Staff
                    </Button>
                </Link>
            </div>
                <!-- Table Columns -->
                <Column field="id" header="ID" sortable class="text-center" />
                <Column field="name" header="name" sortable class="text-left"/>
                <Column field="email" header="email" sortable class="text-left" />
                <Column field="role" header="role" sortable class="text-center" />
            </DataTable>

            <DataTable
                v-if="!isLoading"
                :value="Category"
                responsiveLayout="scroll"
                class="w-full shadow-sm border border-gray-200 rounded-lg"
            >
            
            <div class="p-4 text-right">
                <Link :href="InsertStaff()">
                    <Button class="bg-blue-500 hover:bg-blue-700 text-white rounded px-4 py-2">
                        Add New Category
                    </Button>
                </Link>
            </div>
                <!-- Table Columns -->
                <Column field="id" header="ID" sortable class="text-center" />
                <Column field="category" header="Category" sortable class="text-left"/>
            </DataTable>

            <!-- Skeleton loading -->
            <div v-else>
                <div v-for="n in 5" :key="n" class="mb-2 border rounded p-2">
                    <Skeleton class="h-4 w-full mb-1" />
                    <Skeleton class="h-4 w-full mb-1" />
                    <Skeleton class="h-4 w-full mb-1" />
                    <Skeleton class="h-4 w-full mb-1" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
