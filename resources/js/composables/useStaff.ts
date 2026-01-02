// resources/js/composables/useStaffs.ts
import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query';
import axios from 'axios';

// Staff interface
export interface Staff {
    id: number;
    name: string;
    email: string;
    role: string;
    created_at: string;
    updated_at: string;
    deleted_at: string;
}

// Role interface (for dropdown)
export interface RoleOption {
    name: string;
    code: string;
}

// API: Fetch all Staffs
export const fetchStaff = async (): Promise<Staff[]> => {
    const response = await axios.get('/api/staff');
    return response.data.Staffs;
};

// API: Fetch roles
export const fetchRoles = async (): Promise<RoleOption[]> => {
    const response = await axios.get('/api/roles');
    return response.data.map((role: string) => ({
        name: role
            .replace('_', ' ')
            .replace(/\b\w/g, (l: string) => l.toUpperCase()),
        code: role,
    }));
};

// API: Create Staff
export const createStaffApi = async (payload: {
    name: string;
    email: string;
    role: string | null;
}) => {
    const response = await axios.post('/api/staff', payload);
    return response.data;
};

// API: Fetch 1 Staff
// export const fetchStaffById = async (id: number): Promise<Staff> => {
//     const response = await axios.get(`/api/Staffs/${id}`);
//     return response.data.Staff;
// };

// Composable for list
export const useStaff = () => {
    return useQuery<Staff[]>({
        queryKey: ['staff'],
        queryFn: fetchStaff,
    });
};

// Composable for roles
export const useRoles = () => {
    return useQuery<RoleOption[]>({
        queryKey: ['roles'],
        queryFn: fetchRoles,
        staleTime: 1000 * 60 * 10, // roles rarely change
    });
};

// Composable for create
export const useCreateStaff = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: createStaffApi,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['staff'] });
        },
    });
};

// Composable for detail
// export const useStaff = (id: number) => {
//     return useQuery<Staff>({
//         queryKey: ['Staff', id],
//         queryFn: () => fetchStaffById(id),
//     });
// };
