<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard, ListTickets } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import axios from 'axios';
import { ref,onMounted } from 'vue';

// Create Ticket API fields
const title = ref('');
const description = ref('');
const category_id = ref('');
const priority_id = ref('');
const staff_id = ref('');
const message = ref('');

const staffs = ref<any[]>([]);
const selectedStaff = ref<string | null>(null);

// Fetch staffs from the backend API
const fetchStaffs = async () => {
    try {
        const response = await axios.get('/api/staff');

        staffs.value = response.data.Staffs.map((staff: any) => ({
            id: staff.id,
            name: staff.name,
        }));
    } catch (error) {
        console.log("Error fetching staffs:", error);
    }
};

// Fetch staffs when the component is mounted
onMounted(() => {
    fetchStaffs();
});


// Create Ticket function
const createTicket = async () => {
    try {
        const response = await axios.post('/api/tickets', {
            title: title.value,
            description: description.value,
            category_id: category_id.value,
            priority_id: priority_id.value,
            staff_id: selectedStaff.value,
        });

        message.value = response.data.message;
        // Redirect to ListTickets page after successful creation
        router.visit(ListTickets().url);
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
                    <InputText
                        v-model="title"
                        type="text"
                        placeholder="Ticket Title"
                        class="rounded border p-2"
                    />

                    <Textarea
                        v-model="description"
                        placeholder="Description"
                        class="rounded border p-2"
                        rows="4"
                    ></Textarea>

                    <Dropdown 
                        v-model="selectedStaff" 
                        :options="staffs" 
                        option-label="name" 
                        option-value="id" 
                        placeholder="Select a Staff" 
                        filter
                        class="w-full" 
                    />

                    <InputText
                        v-model="category_id"
                        type="text"
                        placeholder="Category ID"
                        class="rounded border p-2"
                    />

                    <InputText
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
