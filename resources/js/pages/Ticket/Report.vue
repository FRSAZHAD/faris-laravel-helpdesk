<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard, ListTickets } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Skeleton from '@/components/ui/skeleton/Skeleton.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { useDashboard } from '@/composables/useDashboard';
import Chart from 'primevue/chart';
import { computed } from 'vue';
import { useTickets } from '@/composables/useTickets';

// Create Ticket API fields
const { data, isLoading } = useDashboard();
const normalizeStatus = (status: string) =>
    status?.toUpperCase().replace('-', ' ');
const statusClassMap: Record<string, string> = {
    OPEN: 'bg-blue-300 text-blue-700',
    'ON HOLD': 'bg-yellow-300 text-yellow-700',
    CANCELLED: 'bg-gray-300 text-muted-foreground-700',
    CLOSED: 'bg-green-300 text-green-700',
};

const getStatusClass = (status: string) =>
    statusClassMap[normalizeStatus(status)] ??
    'bg-gray-100 text-muted-foreground-700';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Menu',
        href: dashboard().url,
    },
];

const { data: tickets } = useTickets();

const pieData = computed(() => {
    if (!data.value) {
        return {
            labels: ['OPEN', 'ON HOLD', 'CLOSED', 'CANCELLED'],
            datasets: [
                {
                    data: [0, 0, 0, 0],
                    backgroundColor: [
                        '#3b82f6',
                        '#f59e0b',
                        '#22c55e',
                        '#6b7280',
                    ],
                    borderWidth: 0,
                },
            ],
        };
    }

    return {
        labels: ['OPEN', 'ON HOLD', 'CLOSED', 'CANCELLED'],
        datasets: [
            {
                data: [
                    data.value.openTickets ?? 0,
                    data.value.onHoldTickets ?? 0,
                    data.value.closedTickets ?? 0,
                    data.value.cancelledTickets ?? 0,
                ],
                backgroundColor: [
                    '#3b82f6',
                    '#f59e0b',
                    '#22c55e',
                    '#6b7280',
                ],
                borderWidth: 0,
            },
        ],
    };
});

const pieOptions = {
    plugins: {
        legend: {
            position: 'bottom',
            labels: {
                color: '#9ca3af',
                padding: 16,
                usePointStyle: true,
                boxWidth: 10,
            },
        },
    },
    maintainAspectRatio: false,
};
</script>

<template>
    <Head title="Insert Ticket" />

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
                <div class="mb-6">
                    <h1 class="text-2xl font-bold">Reports</h1>
                    <p class="text-muted-foreground-500 text-sm">
                        Overview and analytics of system data
                    </p>

                    <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                        <div class="w-full max-w-lg rounded-xl border p-4">
                            <p class="text-sm text-muted-foreground-500">Total Tickets</p>
                            <Skeleton v-if="isLoading" class="h-8 w-20 mt-2" />
                            <p v-else class="mt-2 text-2xl font-bold">
                                {{ data?.totalTickets }}
                            </p>
                        </div>

                        <div class="w-full max-w-lg rounded-xl border p-4">
                            <p class="text-sm text-muted-foreground-500">Open Tickets</p>
                            <Skeleton v-if="isLoading" class="h-8 w-20 mt-2" />
                            <p v-else class="mt-2 text-2xl font-bold">
                                {{ data?.openTickets }}
                            </p>
                        </div>

                        <div class="w-full max-w-lg rounded-xl border p-4">
                            <p class="text-sm text-muted-foreground-500">Closed Tickets</p>
                            <Skeleton v-if="isLoading" class="h-8 w-20 mt-2" />
                            <p v-else class="mt-2 text-2xl font-bold">
                                {{ data?.closedTickets }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="w-full max-w-lg rounded-xl border p-4">
                            <h2 class="text-sm font-medium text-muted-foreground mb-4">
                                Ticket Status Distribution
                            </h2>

                            <div class="h-64">
                                <Chart
                                    type="pie"
                                    :data="pieData"
                                    :options="pieOptions"
                                    class="w-full h-full"
                                />
                            </div>
                        </div>
                    </div>
                    <!-- Ticket List Table -->
                    <div class="mt-8 rounded-xl border p-4">
                        <h2 class="text-sm font-medium text-muted-foreground mb-4">
                            Ticket List
                        </h2>

                        <Skeleton v-if="isLoading" class="h-40 w-full" />

                        <DataTable
                            v-else
                            :value="tickets"
                            responsiveLayout="scroll"
                            paginator
                            :rows="10"
                            :rowsPerPageOptions="[5, 10, 20, 50]"
                            paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                            class="w-full rounded-lg border border-gray-200 shadow-sm"
                        >
                            <Column header="No">
                                <template #body="slotProps">
                                    {{ slotProps.index + 1 }}
                                </template>
                            </Column>

                            <Column field="title" header="Ticket Title" />

                            <Column field="description" header="Description">
                                <template #body="slotProps">
                                    <span class="line-clamp-2">
                                        {{ slotProps.data.description }}
                                    </span>
                                </template>
                            </Column>

                            <Column field="staff_id" header="Staff Assigned" />

                            <Column field="priority_id" header="Priority" />

                            <Column field="category_id" header="Category" />
                        </DataTable>
                    </div>
                    
                </div>

                <!-- End Create Ticket Form -->
            </div>
        </div>
    </AppLayout>
</template>
