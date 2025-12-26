<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Skeleton from '@/components/ui/skeleton/Skeleton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard, InsertCategory, InsertStaff } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

import Column from 'primevue/column';
import DataTable from 'primevue/datatable';

import { useCategory } from '@/composables/useCategory';
import { useStaff } from '@/composables/useStaff';

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
                class="w-full rounded-lg border border-gray-200 shadow-sm"
            >
                <div class="p-4 text-right">
                    <Link :href="InsertStaff()">
                        <Button
                            class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-700"
                        >
                            Add New Staff
                        </Button>
                    </Link>
                </div>
                <!-- Table Columns -->
                <Column header="#" class="text-center">
                    <template #body="{ index }">
                        {{ index + 1 }}
                    </template>
                </Column>
                <!-- <Column field="id" header="ID" sortable class="text-center" /> -->
                <Column field="name" header="name" sortable class="text-left" />
                <Column
                    field="email"
                    header="email"
                    sortable
                    class="text-left"
                />
                <Column
                    field="role"
                    header="role"
                    sortable
                    class="text-center"
                />
            </DataTable>

            <DataTable
                v-if="!isLoading"
                :value="Category"
                responsiveLayout="scroll"
                class="w-full rounded-lg border border-gray-200 shadow-sm"
            >
                <div class="p-4 text-right">
                    <Link :href="InsertCategory()">
                        <Button
                            class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-700"
                        >
                            Add New Category
                        </Button>
                    </Link>
                </div>
                <!-- Table Columns -->
                 <Column header="#" class="text-center">
                    <template #body="{ index }">
                        {{ index + 1 }}
                    </template>
                </Column>
                <!-- <Column field="id" header="ID" sortable class="text-center" /> -->
                <Column
                    field="category"
                    header="Category"
                    sortable
                    class="text-left"
                />
            </DataTable>

            <!-- Skeleton loading -->
            <div v-else>
                <div v-for="n in 5" :key="n" class="mb-2 rounded border p-2">
                    <Skeleton class="mb-1 h-4 w-full" />
                    <Skeleton class="mb-1 h-4 w-full" />
                    <Skeleton class="mb-1 h-4 w-full" />
                    <Skeleton class="mb-1 h-4 w-full" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
