// resources/js/composables/useStaffs.ts
import { ref } from 'vue';
import axios from 'axios';
import { useQuery } from '@tanstack/vue-query';

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

// API: Fetch all Staffs
export const fetchStaff = async (): Promise<Staff[]> => {
    const response = await axios.get('/api/staff');
    return response.data.Staffs;
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

// Composable for detail
// export const useStaff = (id: number) => {
//     return useQuery<Staff>({
//         queryKey: ['Staff', id],
//         queryFn: () => fetchStaffById(id),
//     });
// };
