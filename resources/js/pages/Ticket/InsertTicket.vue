<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

import axios from 'axios';
import { ref } from 'vue';

// Create Ticket API fields
const title = ref('');
const description = ref('');
const category_id = ref('');
const priority_id = ref('');
const message = ref('');

// Create Ticket function
const createTicket = async () => {
    try {
        const response = await axios.post('/api/tickets', {
            title: title.value,
            description: description.value,
            category_id: category_id.value,
            priority_id: priority_id.value,
        });

        message.value = response.data.message;
    } catch (error) {
        console.log(error);
        message.value = 'Error creating ticket';
    }
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Menu',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Menu" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="grid auto-rows-min gap-4 md:grid-cols-3"></div>

            <!-- <div align="center">My Life Summary</div> -->

            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-6 md:min-h-min dark:border-sidebar-border"
            >
                <!-- 🌟 INSERTED: Create Ticket Form -->
                <h2 class="mb-4 text-xl font-semibold">Create Ticket</h2>

                <div class="flex w-full flex-col gap-4">
                    <input
                        v-model="title"
                        type="text"
                        placeholder="Ticket Title"
                        class="rounded border p-2"
                    />

                    <textarea
                        v-model="description"
                        placeholder="Description"
                        class="rounded border p-2"
                        rows="4"
                    ></textarea>

                    <input
                        v-model="category_id"
                        type="text"
                        placeholder="Category ID"
                        class="rounded border p-2"
                    />

                    <input
                        v-model="priority_id"
                        type="text"
                        placeholder="Priority ID"
                        class="rounded border p-2"
                    />

                    <button
                        @click="createTicket"
                        class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                    >
                        Create Ticket
                    </button>

                    <p class="font-semibold text-green-600">{{ message }}</p>
                </div>

                <!-- End Create Ticket Form -->
            </div>
        </div>
    </AppLayout>
</template>
