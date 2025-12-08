<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Skeleton from '@/components/ui/skeleton/Skeleton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard, InsertTicket, TicketDetail } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { useQuery } from '@tanstack/vue-query';
import axios from 'axios';
import { h } from 'vue';  // <-- Add this import

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

interface Ticket {
    id: number;
    title: string;
    description: string;
    category_id: number;
    priority_id: number;
    status: string;
}

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'List Tickets', href: '' },
];

// Fetch tickets
const fetchTickets = async (): Promise<Ticket[]> => {
    const response = await axios.get('/api/tickets');
    return response.data.tickets;
};

// Vue Query
const { data: tickets, isLoading } = useQuery<Ticket[]>({
    queryKey: ['tickets'],
    queryFn: fetchTickets,
});
</script>

<template>
    <Head title="List Tickets" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- Add Ticket Button -->
        <div class="p-4 text-right">
            <Link :href="InsertTicket()">
                <Button class="bg-blue-500 hover:bg-blue-700 text-white rounded px-4 py-2">
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
                class="w-full shadow-sm border border-gray-200 rounded-lg"
            >
                <!-- Table Columns -->
                <Column field="id" header="ID" sortable class="text-center" />
                <Column field="title" header="Title" sortable class="text-left">
                <template #body="{ data }">
                    <Link :href="TicketDetail(data.id)">
                    {{ data.title }}
                    </Link>
                </template>
                </Column>
                <Column field="description" header="Description" sortable class="text-left" />
                <Column field="category_id" header="Category" sortable class="text-center" />
                <Column field="priority_id" header="Priority" sortable class="text-center" />
                <Column field="status" header="Status" sortable class="text-center" />
            </DataTable>

            <!-- Skeleton loading -->
            <div v-else>
                <div v-for="n in 5" :key="n" class="mb-2 border rounded p-2">
                    <Skeleton class="h-4 w-full mb-1" />
                    <Skeleton class="h-4 w-full mb-1" />
                    <Skeleton class="h-4 w-full mb-1" />
                    <Skeleton class="h-4 w-full mb-1" />
                    <Skeleton class="h-4 w-full mb-1" />
                    <Skeleton class="h-4 w-full" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
