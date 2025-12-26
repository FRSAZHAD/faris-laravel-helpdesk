<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Skeleton from '@/components/ui/skeleton/Skeleton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard, InsertTicket, TicketDetail } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

import Column from 'primevue/column';
import DataTable from 'primevue/datatable';

import { useTickets } from '@/composables/useTickets';

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'List Tickets', href: '' },
];

const { data: tickets, isLoading } = useTickets();
</script>

<template>
    <Head title="List Tickets" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- Add Ticket Button -->
        <div class="p-4 text-right">
            <Link :href="InsertTicket()">
                <Button
                    class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-700"
                >
                    Add Ticket
                </Button>
            </Link>
        </div>

        <!-- PrimeVue DataTable -->
        <div class="p-4">
            <h2 class="mb-4 text-xl font-semibold">Tickets List</h2>

            <DataTable
                v-if="!isLoading"
                :value="tickets"
                responsiveLayout="scroll"
                class="w-full rounded-lg border border-gray-200 shadow-sm"
            >
                <!-- Table Columns -->
                <Column header="#" class="text-center">
                    <template #body="{ index }">
                        {{ index + 1 }}
                    </template>
                </Column>

                <!-- <Column field="id" header="Ticket ID" sortable class="text-center" /> -->
                <Column field="title" header="Title" sortable class="text-left">
                    <template #body="{ data }">
                        <Link :href="TicketDetail(data.id)">
                            {{ data.title }}
                        </Link>
                    </template>
                </Column>
                <Column
                    field="description"
                    header="Description"
                    sortable
                    class="text-left"
                />
                <Column
                    field="category_id"
                    header="Category"
                    sortable
                    class="text-center"
                />
                <Column
                    field="priority_id"
                    header="Priority"
                    sortable
                    class="text-center"
                />
                <Column
                    field="status"
                    header="Status"
                    sortable
                    class="text-center"
                />
            </DataTable>

            <!-- Skeleton loading -->
            <div v-else>
                <div v-for="n in 5" :key="n" class="mb-2 rounded border p-2">
                    <Skeleton class="mb-1 h-4 w-full" />
                    <Skeleton class="mb-1 h-4 w-full" />
                    <Skeleton class="mb-1 h-4 w-full" />
                    <Skeleton class="mb-1 h-4 w-full" />
                    <Skeleton class="mb-1 h-4 w-full" />
                    <Skeleton class="h-4 w-full" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
