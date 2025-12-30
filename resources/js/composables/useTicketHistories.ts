import { useQuery } from '@tanstack/vue-query';
import axios from 'axios';

export function useTicketHistories(ticketId: number) {
    return useQuery({
        queryKey: ['ticket-histories', ticketId],
        queryFn: async () => {
            const { data } = await axios.get(
                `/tickets/${ticketId}/histories`
            );

            return data;
        },
        enabled: !!ticketId,
    });
}