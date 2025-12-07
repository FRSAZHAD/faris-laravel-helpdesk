<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard, InsertTicket, ListTickets } from '@/routes';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid } from 'lucide-vue-next';
import { ref } from 'vue';
import AppLogo from './AppLogo.vue';
const openDropdowns = ref<string[]>([]);

function toggleDropdown(title: string) {
    const index = openDropdowns.value.indexOf(title);
    if (index >= 0) openDropdowns.value.splice(index, 1);
    else openDropdowns.value.push(title);
}

function navigateChild(title: string) {
    openDropdowns.value = openDropdowns.value.filter((t) => t !== title);
}

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },

    {
        title: 'Ticket',
        icon: LayoutGrid,
        children: [
            {
                title: 'Insert Ticket',
                href: InsertTicket(),
            },
            {
                title: 'List Tickets',
                href: ListTickets(), // We'll create this route
            },
        ],
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <SidebarMenu>
                <SidebarMenuItem v-for="item in mainNavItems" :key="item.title">
                    <!-- Items without children -->
                    <template v-if="!item.children">
                        <Link :href="item.href">
                            <SidebarMenuButton size="lg">
                                {{ item.title }}
                            </SidebarMenuButton>
                        </Link>
                    </template>

                    <!-- Items with children -->
                    <template v-else>
                        <SidebarMenuButton
                            size="lg"
                            @click="toggleDropdown(item.title)"
                            class="flex items-center justify-between"
                        >
                            <span>{{ item.title }}</span>
                            <svg
                                class="h-4 w-4 transition-transform duration-200"
                                :class="{
                                    'rotate-90': openDropdowns.includes(
                                        item.title,
                                    ),
                                }"
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

                        <!-- Dropdown children -->
                        <SidebarMenu v-if="openDropdowns.includes(item.title)">
                            <SidebarMenuItem
                                v-for="child in item.children"
                                :key="child.title"
                            >
                                <SidebarMenuButton as-child>
                                    <Link
                                        :href="child.href"
                                        @click="navigateChild(item.title)"
                                    >
                                        {{ child.title }}
                                    </Link>
                                </SidebarMenuButton>
                            </SidebarMenuItem>
                        </SidebarMenu>
                    </template>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>

    <slot />
</template>
