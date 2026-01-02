<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import InputText from 'primevue/inputtext';
import { ref } from 'vue';

import { useCreateCategory } from '@/composables/useCategory';

// Create Staff API fields
const category = ref('');
const message = ref('');

// TanStack mutation
const { mutate, isPending } = useCreateCategory();

// Create Staff function
const createCategory = () => {
    if (!category.value) return;

    mutate(category.value, {
        onSuccess: (data) => {
            message.value = data.message;
            category.value = '';
        },
        onError: () => {
            message.value = 'Error creating Category';
        },
    });
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
                <h2 class="mb-4 text-xl font-semibold">Create Category</h2>

                <div class="flex w-full flex-col gap-4">
                    <InputText
                        v-model="category"
                        placeholder="Category Name"
                        class="p-inputtext w-full"
                    />

                    <button
                        @click="createCategory"
                        :disabled="isPending"
                        class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                    >
                        {{ isPending ? 'Creating...' : 'Create Category' }}
                    </button>

                    <p class="font-semibold text-green-600">{{ message }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
