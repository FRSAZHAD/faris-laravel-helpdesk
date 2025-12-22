// resources/js/composables/useStaffs.ts
import { ref } from 'vue';
import axios from 'axios';
import { useQuery } from '@tanstack/vue-query';

// Staff interface
export interface Category {
    id: number;
    category: string;
    created_at: string;
    updated_at: string;
    deleted_at: string;
}

// API: Fetch all Staffs
export const fetchCategory = async (): Promise<Category[]> => {
    const response = await axios.get('/api/category');
    return response.data.Category;
};

// API: Fetch 1 Staff
// export const fetchStaffById = async (id: number): Promise<Staff> => {
//     const response = await axios.get(`/api/Staffs/${id}`);
//     return response.data.Staff;
// };

// Composable for list
export const useCategory = () => {
    return useQuery<Category[]>({
        queryKey: ['category'],
        queryFn: fetchCategory,
    });
};

// Composable for detail
// export const useStaff = (id: number) => {
//     return useQuery<Staff>({
//         queryKey: ['Staff', id],
//         queryFn: () => fetchStaffById(id),
//     });
// };
