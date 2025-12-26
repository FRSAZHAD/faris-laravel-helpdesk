<script setup lang="ts">
/* ================================
 * Inertia
 * ================================ */
import { Head } from '@inertiajs/vue3';

/* ================================
 * Layout
 * ================================ */
import AppLayout from '@/layouts/AppLayout.vue';

/* ================================
 * UI
 * ================================ */
import Button from '@/components/ui/button/Button.vue';
import Skeleton from '@/components/ui/skeleton/Skeleton.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

/* ================================
 * Routes & types
 * ================================ */
import { dashboard, ListTickets } from '@/routes';
import type { BreadcrumbItem } from '@/types';

import { useDashboard } from '@/composables/useDashboard';
/* ================================
 * Fetch dashboard data
 * ================================ */
const { data, isLoading } = useDashboard();

/* ================================
 * Breadcrumbs
 * ================================ */
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4">

            <!-- =======================
                 TOP CARDS (UI ONLY)
            ======================== -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="rounded-xl border p-4">
                    <p class="text-sm text-gray-500">Total Tickets</p>
                    <Skeleton v-if="isLoading" class="h-8 w-20 mt-2" />
                    <p v-else class="mt-2 text-2xl font-bold">
                        {{ data?.totalTickets }}
                    </p>
                </div>

                <div class="rounded-xl border p-4">
                    <p class="text-sm text-gray-500">Open Tickets</p>
                    <Skeleton v-if="isLoading" class="h-8 w-20 mt-2" />
                    <p v-else class="mt-2 text-2xl font-bold">
                        {{ data?.openTickets }}
                    </p>
                </div>

                <div class="rounded-xl border p-4">
                    <p class="text-sm text-gray-500">Closed Tickets</p>
                    <Skeleton v-if="isLoading" class="h-8 w-20 mt-2" />
                    <p v-else class="mt-2 text-2xl font-bold">
                        {{ data?.closedTickets }}
                    </p>
                </div>
            </div>


            <!-- =======================
                 RECENT TICKETS (UI ONLY)
            ======================== -->
            <div class="rounded-xl border p-4">
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="font-semibold">Recent Tickets</h3>
                    <Link :href="ListTickets()" class="text-sm underline">
                        View All
                    </Link>
                </div>

                <!-- Skeleton loading -->
                <div v-if="isLoading" class="space-y-2">
                    <Skeleton v-for="n in 5" :key="n" class="h-6 w-full" />
                </div>

                <!-- DataTable -->
                <DataTable
                    :value="data?.recentTickets"
                    size="small"
                    stripedRows
                    class="w-full"
                >
                    <Column field="title" style="width: 70%" />

                    <Column field="status" style="width: 30%">
                        <template #body="{ data }">
                            <span class="text-xs text-gray-400">
                                {{ data.status }}
                            </span>
                        </template>
                    </Column>
                </DataTable>

            </div>


        </div>
    </AppLayout>
</template>
