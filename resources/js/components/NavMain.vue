<script setup lang="ts">
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';

import { urlIsActive } from '@/lib/utils';
import type { NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    items: NavItem[];
}>();

const page = usePage();

// Track which dropdowns are open
const openDropdowns = ref<string[]>([]);

function toggleDropdown(title: string) {
    const index = openDropdowns.value.indexOf(title);
    if (index >= 0) openDropdowns.value.splice(index, 1);
    else openDropdowns.value.push(title);
}
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Platform</SidebarGroupLabel>

        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">

                <!-- No children → simple link -->
                <template v-if="!item.children">
                    <SidebarMenuButton
                        as-child
                        :is-active="urlIsActive(item.href, page.url)"
                        :tooltip="item.title"
                    >
                        <Link :href="item.href">
                            <component :is="item.icon" />
                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>
                </template>

                <!-- Has children → dropdown -->
                <template v-else>
                    <SidebarMenuButton
                        class="flex items-center justify-between"
                        :tooltip="item.title"
                        @click="toggleDropdown(item.title)"
                    >
                        <div class="flex items-center gap-2">
                            <component :is="item.icon" />
                            <span>{{ item.title }}</span>
                        </div>

                        <svg
                            class="h-4 w-4 transition-transform duration-200"
                            :class="{ 'rotate-90': openDropdowns.includes(item.title) }"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </SidebarMenuButton>

                    <!-- Dropdown Items -->
                    <SidebarMenu v-if="openDropdowns.includes(item.title)">
                        <SidebarMenuItem
                            v-for="child in item.children"
                            :key="child.title"
                        >
                            <SidebarMenuButton
                                as-child
                                :is-active="urlIsActive(child.href, page.url)"
                                class="pl-8"
                            >
                                <Link :href="child.href">
                                    <span>{{ child.title }}</span>
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </template>

            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
