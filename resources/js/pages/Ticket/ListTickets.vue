<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Skeleton from '@/components/ui/skeleton/Skeleton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard, InsertTicket, TicketDetail } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { useQuery } from '@tanstack/vue-query';
import axios from 'axios';

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
                <Button class="bg-blue-500"> Add Ticket </Button>
            </Link>
        </div>

        <!-- Tickets Table -->
        <div class="p-4">
            <h2 class="mb-4 text-xl font-semibold">Tickets List</h2>

            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Title</th>
                        <th class="border px-4 py-2">Description</th>
                        <th class="border px-4 py-2">Category</th>
                        <th class="border px-4 py-2">Priority</th>
                        <th class="border px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Skeleton rows while loading -->
                    <template v-if="isLoading">
                        <tr v-for="n in 5" :key="n">
                            <td class="border px-4 py-2">
                                <Skeleton class="h-4 w-full" />
                            </td>
                            <td class="border px-4 py-2">
                                <Skeleton class="h-4 w-full" />
                            </td>
                            <td class="border px-4 py-2">
                                <Skeleton class="h-4 w-full" />
                            </td>
                            <td class="border px-4 py-2">
                                <Skeleton class="h-4 w-full" />
                            </td>
                            <td class="border px-4 py-2">
                                <Skeleton class="h-4 w-full" />
                            </td>
                            <td class="border px-4 py-2">
                                <Skeleton class="h-4 w-full" />
                            </td>
                        </tr>
                    </template>

                    <!-- Real tickets -->
                    <template v-else>
                        <tr v-for="ticket in tickets" :key="ticket.id">
                            <td class="border px-4 py-2">{{ ticket.id }}</td>
                            <td class="border px-4 py-2">
                                <Link :href="TicketDetail(ticket.id)">
                                    {{ ticket.title }}
                                </Link>
                            </td>
                            <td class="border px-4 py-2">
                                {{ ticket.description }}
                            </td>
                            <td class="border px-4 py-2">
                                {{ ticket.category_id }}
                            </td>
                            <td class="border px-4 py-2">
                                {{ ticket.priority_id }}
                            </td>
                            <td class="border px-4 py-2">
                                {{ ticket.status }}
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
