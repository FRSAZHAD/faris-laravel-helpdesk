// resources/js/composables/useTickets.ts

import axios from 'axios';
import { useQuery } from '@tanstack/vue-query';

// Ticket interface
export interface Ticket {
    id: number;
    title: string;
    description: string;
    category_id: number;
    priority_id: number;
    status: string;
}

// API: Fetch all tickets
export const fetchTickets = async (): Promise<Ticket[]> => {
    const response = await axios.get('/api/tickets');
    return response.data.tickets;
};

// API: Fetch ticket by ID
export const fetchTicketById = async (id: number): Promise<Ticket> => {
    if (!id) throw new Error("fetchTicketById requires a valid ID");
    const response = await axios.get(`/api/tickets/${id}`);
    return response.data.ticket;
};

// Composable: List all tickets
export const useTickets = () => {
    return useQuery<Ticket[]>({
        queryKey: ['tickets'],
        queryFn: fetchTickets,
    });
};

// Composable: Single ticket detail
export const useTicket = (id: number) => {
    return useQuery<Ticket>({
        queryKey: ['ticket', id],
        queryFn: () => fetchTicketById(id),
        enabled: !!id,   // prevents query before ID exists
    });
};
