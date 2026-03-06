<script setup lang="ts">
import Button from 'primevue/button';
import Skeleton from '@/components/ui/skeleton/Skeleton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard, InsertCategory, InsertStaff } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import 'primeicons/primeicons.css'
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import { ref } from 'vue';

import { useCategory, useDeleteCategory } from '@/composables/useCategory';
import { useStaff, useDeleteStaff } from '@/composables/useStaff';

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'List Staff', href: '' },
];

const { data: staff, isLoading } = useStaff();
const { data: Category } = useCategory();

const deleteDialogVisible = ref(false);
const selectedItem = ref<any>(null);
const deleteType = ref<'staff' | 'category' | null>(null);

const openDeleteDialog = (row: any, type: 'staff' | 'category') => {
    selectedItem.value = row;
    deleteType.value = type;
    deleteDialogVisible.value = true;
};

const closeDeleteDialog = () => {
    deleteDialogVisible.value = false;
    selectedItem.value = null;
    deleteType.value = null;
};

const { mutate: deleteStaff } = useDeleteStaff();
const { mutate: deleteCategory } = useDeleteCategory();

const handleDelete = () => {
    if (!selectedItem.value || !deleteType.value) return;

    const id = selectedItem.value.id;

    if (deleteType.value === 'staff') {
        deleteStaff(id);
    } else if (deleteType.value === 'category') {
        deleteCategory(id);
    }

    deleteDialogVisible.value = false;
    selectedItem.value = null;
    deleteType.value = null;
};
</script>

<template>
    <Head title="Admin Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- PrimeVue DataTable -->
        <div class="p-4">
            <h2 class="mb-4 text-xl font-semibold">Administration</h2>
            <DataTable
                v-if="!isLoading"
                :value="staff"
                responsiveLayout="scroll"
                paginator
                :rows="10"
                :rowsPerPageOptions="[5, 10, 20, 50]"
                paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                class="w-full rounded-lg border border-gray-200 shadow-sm"
            >
                <div class="p-4 text-right">
                    <Link :href="InsertStaff()">
                        <Button
                            class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-700"
                        >
                            Add New Staff
                        </Button>
                    </Link>
                </div>
                <!-- Table Columns -->
                <Column header="#" class="text-center">
                    <template #body="{ index }">
                        {{ index + 1 }}
                    </template>
                </Column>
                <!-- <Column field="id" header="ID" sortable class="text-center" /> -->
                <Column field="name" header="name" sortable class="text-left" />
                <Column
                    field="email"
                    header="email"
                    sortable
                    class="text-left"
                />
                <Column
                    field="role"
                    header="role"
                    sortable
                    class="text-center"
                />
                <Column header="Delete" class="text-center">
                    <template #body="{ data }">
                        <Button
                            icon="pi pi-trash"
                            severity="danger"
                            text
                            rounded
                            @click="openDeleteDialog(data, 'staff')"
                        />
                    </template>
                </Column>
                <Dialog
                    v-model:visible="deleteDialogVisible"
                    header="Confirm Delete"
                    modal
                    :style="{ width: '400px' }"
                >
                    <div v-if="selectedItem">
                        <p>
                            Are you sure you want to delete
                            <strong>
                                {{
                                    deleteType === 'staff'
                                        ? selectedItem.name
                                        : selectedItem.category
                                }}
                            </strong>
                            ?
                        </p>

                        <div class="mt-4 flex justify-end gap-2">
                            <Button
                                label="Cancel"
                                severity="secondary"
                                text
                                @click="closeDeleteDialog"
                            />
                            <Button
                                label="Delete"
                                severity="danger"
                                @click="handleDelete"
                            />
                        </div>
                    </div>
                </Dialog>
            </DataTable>

            <DataTable
                v-if="!isLoading"
                :value="Category"
                paginator
                :rows="10"
                :rowsPerPageOptions="[5, 10, 20, 50]"
                paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                responsiveLayout="scroll"
                class="w-full rounded-lg border border-gray-200 shadow-sm"
            >
                <div class="p-4 text-right">
                    <Link :href="InsertCategory()">
                        <Button
                            class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-700"
                        >
                            Add New Category
                        </Button>
                    </Link>
                </div>
                <!-- Table Columns -->
                <Column header="#" class="text-center">
                    <template #body="{ index }">
                        {{ index + 1 }}
                    </template>
                </Column>
                <!-- <Column field="id" header="ID" sortable class="text-center" /> -->
                <Column
                    field="category"
                    header="Category"
                    sortable
                    class="text-left"
                />
                <Column header="Delete" class="text-center">
                    <template #body="slotProps">
                        <Button
                            icon="pi pi-trash"
                            severity="danger"
                            text
                            rounded
                            @click="openDeleteDialog(slotProps.data, 'category')"
                        />
                    </template>
                </Column>
                <Dialog
                    v-model:visible="deleteDialogVisible"
                    header="Confirm Delete"
                    modal
                    :style="{ width: '400px' }"
                >
                    <div v-if="selectedItem">
                        <p>
                            Are you sure you want to delete
                            <strong>
                                {{
                                    deleteType === 'staff'
                                        ? selectedItem.name
                                        : selectedItem.category
                                }}
                            </strong>
                            ?
                        </p>

                        <div class="mt-4 flex justify-end gap-2">
                            <Button
                                label="Cancel"
                                severity="secondary"
                                text
                                @click="closeDeleteDialog"
                            />
                            <Button
                                label="Delete"
                                severity="danger"
                                @click="handleDelete"
                            />
                        </div>
                    </div>
                </Dialog>
            </DataTable>

            <!-- Skeleton loading -->
            <div v-else>
                <div v-for="n in 5" :key="n" class="mb-2 rounded border p-2">
                    <Skeleton class="mb-1 h-4 w-full" />
                    <Skeleton class="mb-1 h-4 w-full" />
                    <Skeleton class="mb-1 h-4 w-full" />
                    <Skeleton class="mb-1 h-4 w-full" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
