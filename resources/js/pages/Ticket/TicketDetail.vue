<script setup lang="ts">
/* ================================
 * Vue core
 * ================================ */
import { ref, computed, watch } from 'vue';

/* ================================
 * Inertia
 * ================================ */
import { Head, Link, usePage } from '@inertiajs/vue3';

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

/* ================================
 * Queries
 * ================================ */
const { data: ticket, isLoading } = useTicket(ticketId);
const { data: staffs } = useStaff();
const { data: category } = useCategory();
const { data: statusOptions } = useStatusOptions();

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
                    <Dropdown
                        v-if="!isLoading"
                        v-model="form.status"
                        :options="statusDropdownOptions"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="Select status"
                        class="w-full"
                    />
                    <Skeleton v-else class="h-8 w-full" />
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

            <Link :href="ListTickets()">
                <Button>Back</Button>
            </Link>
        </div>
    </AppLayout>
</template>