import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query';
import axios from 'axios';

/* =======================
   Types
======================= */

export interface Staff {
    id: number;
    name: string;
    email?: string;
}

export interface Ticket {
    id: number;
    title: string;
    description: string;
    category_id: number;
    priority_id: number;
    staff_id: number;
    status: string;
    staff: Staff | null;
}

/* =======================
   Queries
======================= */

// Fetch all tickets
export const fetchTickets = async (): Promise<Ticket[]> => {
    const response = await axios.get('/api/tickets');
    return response.data.tickets;
};

// Fetch single ticket
export const fetchTicketById = async (id: number): Promise<Ticket> => {
    const response = await axios.get(`/api/tickets/${id}`);
    return response.data.ticket;
};

// Fetch status options
export const fetchStatusOptions = async (): Promise<string[]> => {
    const response = await axios.get('/api/tickets/status/options');
    return response.data.status_options;
};

/* =======================
   Mutations (Pattern 1)
======================= */

export interface UpdateTicketPayload {
    id: number;
    title: string;
    description: string;
    category_id: number;
    priority_id: number;
    staff_id: number | null;
    status: string;
}

export const updateTicket = async ({
    id,
    ...data
}: UpdateTicketPayload) => {
    const response = await axios.patch(`/api/tickets/${id}`, data);
    return response.data;
};

/* =======================
   Composables
======================= */

export const useTickets = () =>
    useQuery<Ticket[]>({
        queryKey: ['tickets'],
        queryFn: fetchTickets,
    });

export const useTicket = (id: number) =>
    useQuery<Ticket>({
        queryKey: ['ticket', id],
        queryFn: () => fetchTicketById(id),
        enabled: !!id,
    });

export const useStatusOptions = () =>
    useQuery<string[]>({
        queryKey: ['ticket-status-options'],
        queryFn: fetchStatusOptions,
        staleTime: 1000 * 60 * 10,
    });

export const useUpdateTicket = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: updateTicket,
        onSuccess: (_, variables) => {
            queryClient.invalidateQueries({
                queryKey: ['ticket', variables.id],
            });
            queryClient.invalidateQueries({
                queryKey: ['tickets'],
            });
        },
    });
};
