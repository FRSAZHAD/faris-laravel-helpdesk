<script setup lang="ts">
/* ================================
 * Vue core
 * ================================ */
import { ref, computed, watch } from 'vue';

/* ================================
 * Inertia
 * ================================ */
import { Head, Link, usePage } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

/* ================================
 * Layouts
 * ================================ */
import AppLayout from '@/layouts/AppLayout.vue';

/* ================================
 * UI components
 * ================================ */
import Button from '@/components/ui/button/Button.vue';
import Skeleton from '@/components/ui/skeleton/Skeleton.vue';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';

/* ================================
 * PrimeVue components
 * ================================ */
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import InputNumber from 'primevue/inputnumber';

/* ================================
 * Composables
 * ================================ */
import {useTicket,useStatusOptions,useUpdateTicket,
} from '@/composables/useTickets';
import { useStaff } from '@/composables/useStaff';
import { useCategory } from '@/composables/useCategory';
import { useCreateTicketHistory } from '@/composables/useTickets';


/* ================================
 * Routes & types
 * ================================ */
import { dashboard, ListTickets } from '@/routes';
import type { BreadcrumbItem } from '@/types';

/* ================================
 * Page & IDs
 * ================================ */
const page = usePage();
const ticketId = Number(page.url.split('/').pop());

const statusClassMap: Record<string, string> = {
    Open: 'bg-blue-300 text-blue-700',
    'On-Hold': 'bg-yellow-300 text-yellow-700',
    Cancelled: 'bg-gray-300 text-gray-700',
    Closed: 'bg-green-300 text-green-700',
};

const getStatusClass = (status: string) =>
    statusClassMap[status] ?? 'bg-gray-100 text-gray-700';

/* ================================
 * Local state
 * ================================ */
const message = ref('');

const form = ref({
    title: '',
    description: '',
    category_id: null as number | null,
    priority_id: 0,
    staff_id: null as number | null,
    status: '',
});
const localHistories = ref<any[]>([]);

/* ================================
 * Queries
 * ================================ */
const { data: ticket, isLoading } = useTicket(ticketId);
const backendHistories = computed(() => ticket.value?.histories ?? []);
const histories = computed(() => [
    ...localHistories.value,
    ...backendHistories.value,
]);
const { data: staffs } = useStaff();
const { data: category } = useCategory();
const { data: statusOptions } = useStatusOptions();
const isHistoryLoading = isLoading;

/* ================================
 * Computed
 * ================================ */
const staffOptions = computed(() =>
    staffs?.value?.map(staff => ({
        label: staff.name,
        value: staff.id,
    })) ?? [],
);

const categoryOptions = computed(() =>
    category?.value?.map(category => ({
        label: category.category,
        value: category.id,
    })) ?? [],
);

const statusDropdownOptions = computed(() =>
    statusOptions?.value?.map(option => ({
        label: option
            .replace('_', ' ')
            .replace(/\b\w/g, l => l.toUpperCase()),
        value: option,
    })) ?? [],
);

/* ================================
 * Breadcrumbs
 * ================================ */
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'List Tickets', href: '' },
];

/* ================================
 * Mutations
 * ================================ */
const { mutate, isPending } = useUpdateTicket();
const { mutate: createHistory, isPending: isSavingHistory } =useCreateTicketHistory();

/* ================================
 * Watchers
 * ================================ */
watch(ticket, (t) => {
    if (!t) return;

    form.value = {
        title: t.title,
        description: t.description,
        category_id: t.category_id,
        priority_id: t.priority_id,
        staff_id: t.staff_id,
        status: t.status,
    };
});

/* ================================
 * Actions
 * ================================ */
const updateTicket = () => {
    if (!ticket.value) return;

    mutate(
        {
            id: ticket.value.id,
            ...form.value,
        },
        {
            onSuccess: (data) => {
                message.value =
                    data.message ?? 'Ticket updated successfully';
            },
            onError: () => {
                message.value = 'Failed to update ticket';
            },
        },
    );
};

const startAddHistory = () => {
    if (localHistories.value.some(h => h.__isNew)) return;

    localHistories.value.unshift({
        id: 'new',
        description: '',
        status: form.value.status || 'OPEN',
        attachment: null,
        created_at: new Date().toISOString(),
        __isNew: true,
    });
};

const confirmAddHistory = (row: any) => {
    if (!ticket.value) return;

    createHistory(
        {
            ticket_id: ticket.value.id,
            description: row.description,
            status: row.status,
            attachment: row.attachment ?? null,
        },
        {
            onSuccess: () => {
                // ✅ same idea as updateTicket
                // just clean UI state
                localHistories.value = localHistories.value.filter(
                    h => h !== row
                );
            },
            onError: () => {
                console.error('Failed to add history');
            },
        }
    );
};

const cancelAddHistory = () => {
    localHistories.value = localHistories.value.filter(
        h => !h.__isNew
    );
};

</script>


<template>
    <Head title="Ticket Detail" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4">
            <div class="p-4 text-right">
                <Button
                    class="bg-blue-500"
                    :disabled="isPending"
                    @click="updateTicket"
                >
                    {{ isPending ? 'Saving...' : 'Save Changes' }}
                </Button>
            </div>

            <Card v-if="ticket">
                <CardHeader>
                    <CardTitle>
                        Ticket
                        <span v-if="!isLoading">{{ ticket.id }} Details</span>
                        <Skeleton v-else class="h-6 w-32" />
                    </CardTitle>
                </CardHeader>

                <CardContent class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- Title -->
                    <div>
                        <h4 class="font-bold">Title</h4>
                        <InputText
                            v-if="!isLoading"
                            v-model="form.title"
                            class="w-full"
                            placeholder="Ticket title"
                        />
                        <Skeleton v-else class="h-4 w-full" />
                    </div>

                    <!-- Staff -->
                    <div>
                        <h4 class="font-bold">Staff Assigned</h4>
                        <Dropdown
                            v-if="!isLoading"
                            v-model="form.staff_id"
                            :options="staffOptions"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Select staff"
                            filter
                            class="w-full"
                        />
                        <Skeleton v-else class="h-4 w-full" />
                    </div>

                    <!-- Category -->
                    <div>
                        <h4 class="font-bold">Category</h4>
                        <Dropdown
                            v-if="!isLoading"
                            v-model="form.category_id"
                            :options="categoryOptions"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Select category"
                            filter
                            class="w-full"
                        />
                        <Skeleton v-else class="h-4 w-full" />
                    </div>

                    <!-- Priority -->
                    <div>
                        <h4 class="font-bold">Priority</h4>
                        <InputNumber
                            v-if="!isLoading"
                            v-model="form.priority_id"
                            class="w-full"
                            placeholder="Priority ID"
                            :useGrouping="false"
                        />
                        <Skeleton v-else class="h-4 w-full" />
                    </div>

                    <!-- Status -->
                    <div>
                        <h4 class="font-bold">Status</h4>

                        <div
                            v-if="!isLoading"
                            class="inline-block rounded px-3 py-2 text-sm font-medium"
                            :class="{
                                'bg-blue-100 text-blue-700': form.status === 'OPEN',
                                'bg-yellow-100 text-yellow-700': form.status === 'IN PROGRESS',
                                'bg-gray-100 text-gray-700': form.status === 'PENDING',
                                'bg-green-100 text-green-700': form.status === 'CLOSED',
                            }"
                        >
                            {{ form.status }}
                        </div>

                        <Skeleton v-else class="h-8 w-24" />
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <h4 class="font-bold">Description</h4>
                        <Textarea
                            v-if="!isLoading"
                            v-model="form.description"
                            rows="4"
                            class="w-full"
                            placeholder="Ticket description"
                        />
                        <Skeleton v-else class="h-4 w-full" />
                    </div>

                    <!-- Message -->
                    <p
                        v-if="message && !isLoading"
                        :class="[
                            'font-semibold md:col-span-2',
                            message.includes('Failed')
                                ? 'text-red-600'
                                : 'text-green-600',
                        ]"
                    >
                        {{ message }}
                    </p>
                </CardContent>

                <CardFooter />
            </Card>

            <Button
                @click="startAddHistory"
            >
                + Add
            </Button>

            <DataTable
                v-if="!isHistoryLoading"
                :value="histories"
                responsiveLayout="scroll"
                class="w-full"
            >
                <!-- # -->
                <Column header="#" style="width: 5%">
                    <template #body="{ index }">
                        {{ index + 1 }}
                    </template>
                </Column>

                <!-- Description -->
                <Column field="description" header="Description">
                    <template #body="{ data }">
                        <InputText
                            v-if="data.__isNew"
                            v-model="data.description"
                            class="w-full"
                            placeholder="Enter description"
                        />
                        <span v-else>
                            {{ data.description }}
                        </span>
                    </template>
                </Column>

                <!-- Date -->
                <Column header="Date">
                    <template #body="{ data }">
                        {{
                            new Date(data.created_at).toLocaleString()
                        }}
                    </template>
                </Column>

                <!-- Status -->
                <Column header="Status">
                    <template #body="{ data }">
                        <Dropdown
                            v-if="data.__isNew"
                            v-model="data.status"
                            :options="statusDropdownOptions"
                            optionLabel="label"
                            optionValue="value"
                            class="w-full"
                        />

                        <span
                            v-else
                            class="rounded px-2 py-1 font-medium"
                            :class="getStatusClass(data.status)"
                        >
                            {{ data.status }}
                        </span>
                    </template>
                </Column>

                <!-- Attachment -->
                <Column header="Attachment" style="width: 15%">
                    <template #body="{ data }">
                        <div v-if="data.__isNew" class="flex gap-2">
                            <Button
                                size="sm"
                                class="bg-blue-500 text-white"
                                :disabled="!data.description.trim()"
                                @click="confirmAddHistory(data)"
                            >
                                Save
                            </Button>

                            <Button
                                size="sm"
                                class="bg-gray-300 text-black"
                                @click="cancelAddHistory"
                            >
                                Cancel
                            </Button>
                        </div>

                        <span v-else class="text-xs text-gray-400">
                            -
                        </span>
                    </template>
                </Column>
            </DataTable>

            <!-- Skeleton loading -->
            <div v-else>
                <div v-for="n in 5" :key="n" class="mb-2 rounded border p-2">
                    <Skeleton class="mb-1 h-4 w-full" />
                    <Skeleton class="mb-1 h-4 w-full" />
                    <Skeleton class="mb-1 h-4 w-full" />
                    <Skeleton class="mb-1 h-4 w-full" />
                    <Skeleton class="mb-1 h-4 w-full" />
                    <Skeleton class="h-4 w-full" />
                </div>
            </div>
            <Link :href="ListTickets()">
                <Button>Back</Button>
            </Link>
        </div>
    </AppLayout>
</template>