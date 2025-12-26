<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import { ref } from 'vue';

import { useRoles, useCreateStaff } from '@/composables/useStaff';

// Create Staff API fields
const name = ref('');
const email = ref('');
const message = ref('');

// Store the selected value for the role
const selectedRole = ref<string | null>(null);

// TanStack queries & mutation
const { data: roles, isLoading: rolesLoading } = useRoles();
const { mutate, isPending } = useCreateStaff();

// Create Staff function
const createStaff = () => {
    if (!name.value || !email.value || !selectedRole.value) return;

    mutate(
        {
            name: name.value,
            email: email.value,
            role: selectedRole.value,
        },
        {
            onSuccess: (data) => {
                message.value = data.message;
                name.value = '';
                email.value = '';
                selectedRole.value = null;
            },
            onError: () => {
                message.value = 'Error creating staff';
            },
        },
    );
};

// Breadcrumbs for navigation
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Menu', href: dashboard().url },
];
</script>


<template>
    <Head title="Menu" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="grid auto-rows-min gap-4 md:grid-cols-3"></div>

            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-6 md:min-h-min dark:border-sidebar-border"
            >
                <h2 class="mb-4 text-xl font-semibold">Create Staff</h2>

                <div class="flex w-full flex-col gap-4">
                    <InputText
                        v-model="name"
                        placeholder="Staff Name"
                        class="p-inputtext w-full"
                    />
                    <InputText
                        v-model="email"
                        placeholder="Email"
                        class="p-inputtext w-full"
                    />

                    <h2 class="mb-4 text-xl font-semibold">Select Role</h2>
                    <!-- Filterable Dropdown with dynamic roles -->
                    <Dropdown
                        v-model="selectedRole"
                        :options="roles"
                        option-label="name"
                        option-value="code"
                        placeholder="Select a Role"
                        filter
                        :loading="rolesLoading"
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
