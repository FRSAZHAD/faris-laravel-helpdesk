<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import Skeleton from '@/components/ui/skeleton/Skeleton.vue';
import { dashboard, ListTickets } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';
// import { Link } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
// import { InsertTicket } from '@/routes';

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
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'List Tickets',
        href: '',
    },
];

// Tickets data
const tickets = ref<Ticket[]>([]);
const loading = ref(false);
const statusOptions = ref<string[]>([]);
const message = ref('');
const page = usePage();
const ticketId = Number(page.url.split('/').pop());

// Fetch ticket
const fetchTicket = async () => {
    loading.value = true;
    try {
        const response = await axios.get(`/api/tickets/${ticketId}`);
        tickets.value = [response.data.ticket];
    } catch (err) {
        console.error(err);
    } finally {
        loading.value = false;
    }
};

const fetchStatusOptions = async () => {
    try {
        const response = await axios.get('/api/tickets/status/options');
        statusOptions.value = response.data.status_options;
    } catch (error) {
        console.error(error);
    }
};

const updateStatus = async () => {
    try {
        const response = await axios.patch(`/api/tickets/${ticketId}/status`, {
            status: tickets.value[0].status
            
        });
        message.value = response.data.message
        // alert('Status updated!');
    } catch (err) {
        console.error(err);
        alert('Failed to update status.');
    }
};



onMounted(() => {
    fetchTicket();
    fetchStatusOptions();
});

</script>

<template>

    <Head title="Ticket Detail" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4">
            <div class="p-4 text-right">
            <Button class="bg-blue-500" @click="updateStatus"> Update Status </Button>
            </div>

            <Card v-for="ticket in tickets" :key="ticket.id || 'placeholder'">
            <!-- Card Header -->
            <CardHeader>
                <CardTitle>
                Ticket
                <span v-if="!loading">{{ ticket.id }} Details</span>
                <Skeleton v-else class="h-6 w-32" />
                </CardTitle>
            </CardHeader>

            <!-- Card Content -->
            <CardContent class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <!-- Title -->
                <div>
                <h4 class="font-bold text-gray-700">Title</h4>
                <p v-if="!loading">{{ ticket.title }}</p>
                <Skeleton v-else class="h-4 w-full" />
                </div>

                <!-- Description -->
                <div>
                <h4 class="font-bold text-gray-700">Description</h4>
                <p v-if="!loading">{{ ticket.description }}</p>
                <Skeleton v-else class="h-4 w-full" />
                </div>

                <!-- Category -->
                <div>
                <h4 class="font-bold text-gray-700">Category</h4>
                <p v-if="!loading">{{ ticket.category_id }}</p>
                <Skeleton v-else class="h-4 w-full" />
                </div>

                <!-- Priority -->
                <div>
                <h4 class="font-bold text-gray-700">Priority</h4>
                <p v-if="!loading">{{ ticket.priority_id }}</p>
                <Skeleton v-else class="h-4 w-full" />
                </div>

                <!-- Status -->
                <div>
                <h4 class="font-bold text-gray-700">Status</h4>
                <p class="font-semibold text-green-600" v-if="!loading">{{ message }}</p>
                <Skeleton v-else class="h-4 w-24 mb-1" />
                <select
                    v-if="!loading"
                    v-model="ticket.status"
                    class="border rounded p-2 w-full"
                >
                    <option
                    class="font-bold text-gray-700"
                    v-for="option in statusOptions"
                    :key="option"
                    :value="option"
                    >
                    {{ option.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) }}
                    </option>
                </select>
                <Skeleton v-else class="h-8 w-full" />
                </div>
            </CardContent>

            <CardFooter></CardFooter>
            </Card>

            <Link :href="ListTickets()">
            <Button> back </Button>
            </Link>
        </div>
    </AppLayout>

</template>
