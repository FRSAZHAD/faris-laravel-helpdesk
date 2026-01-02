import { useQuery } from '@tanstack/vue-query';
import axios from 'axios';

export interface DashboardData {
    totalTickets: number;
    openTickets: number;
    closedTickets: number;
    recentTickets: {
        id: number;
        title: string;
        status: string;
        created_at: string;
    }[];
}

const fetchDashboard = async (): Promise<DashboardData> => {
    const response = await axios.get('/api/dashboard');
    return response.data;
};

export const useDashboard = () =>
    useQuery<DashboardData>({
        queryKey: ['dashboard'],
        queryFn: fetchDashboard,
    });
