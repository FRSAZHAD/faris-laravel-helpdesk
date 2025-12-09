<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

// Create Staff API fields
const name = ref('');
const email = ref('');
const message = ref('');

// Store roles dynamically fetched from the backend
const roles = ref<any[]>([]);  // Define roles as an array of any type

// Store the selected value for the role
const selectedRole = ref<string | null>(null);

// Fetch roles from the backend API
const fetchRoles = async () => {
    try {
        const response = await axios.get('/api/roles'); // Fetch roles from the API
        roles.value = response.data.map((role: string) => ({
            name: role.replace('_', ' ').replace(/\b\w/g, (l) => l.toUpperCase()),  // Make role name user-friendly
            code: role
        }));
    } catch (error) {
        console.log("Error fetching roles:", error);
    }
};

// Fetch roles when the component is mounted
onMounted(() => {
    fetchRoles();
});

// Create Staff function
const createStaff = async () => {
    try {
        const response = await axios.post('/api/staff', {
            name: name.value,
            email: email.value,
            role: selectedRole.value, // Send the selected role to the backend
        });

        message.value = response.data.message;
    } catch (error) {
        console.log("Error creating staff:", error);
        message.value = 'Error creating staff';
    }
};

// Breadcrumbs for navigation
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Menu', href: dashboard().url },
];
</script>

<template>
    <Head title="Menu" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3"></div>

            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-6 md:min-h-min dark:border-sidebar-border">
                <h2 class="mb-4 text-xl font-semibold">Create Staff</h2>

                <div class="flex w-full flex-col gap-4">
                    <InputText v-model="name" placeholder="Staff Name" class="p-inputtext w-full" />
                    <InputText v-model="email" placeholder="Email" class="p-inputtext w-full" />

                    <h2 class="mb-4 text-xl font-semibold">Select Role</h2>
                    <!-- Filterable Dropdown with dynamic roles -->
                    <Dropdown 
                        v-model="selectedRole" 
                        :options="roles" 
                        option-label="name" 
                        option-value="code" 
                        placeholder="Select a Role" 
                        filter
                        class="w-full" 
                    />

                    <button
                        @click="createStaff"
                        class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                    >
                        Create Staff
                    </button>

                    <p class="font-semibold text-green-600">{{ message }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
