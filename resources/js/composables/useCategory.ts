// resources/js/composables/useStaffs.ts
import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query';
import axios from 'axios';

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
    return response.data.category; // ✅ FIX
};

// API: Create Category
export const createCategoryApi = async (category: string) => {
    const response = await axios.post('/api/category', { category });
    return response.data;
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

// Composable for create
export const useCreateCategory = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: createCategoryApi,
        onSuccess: () => {
            // refresh category list after insert
            queryClient.invalidateQueries({ queryKey: ['category'] });
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
