<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link, usePage } from '@inertiajs/vue3';

const props = withDefaults(defineProps<{ canRegister: boolean }>(), {
    canRegister: true,
});

const page = usePage();
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div
        class="flex min-h-screen flex-col items-center justify-between bg-[#f8f9fa] p-8 dark:bg-[#0a0a0a]"
    >
        <!-- Top Navigation -->
        <nav class="flex w-full max-w-6xl justify-end gap-4 text-sm">
            <Link
                v-if="page.props.auth.user"
                :href="dashboard()"
                class="rounded border px-5 py-2 hover:bg-gray-100 dark:hover:bg-gray-800"
            >
                Dashboard
            </Link>

            <!-- <template v-else>
                <Link :href="login()" class="px-5 py-2 hover:underline">
                    Log in
                </Link>

                <Link
                    v-if="canRegister"
                    :href="register()"
                    class="rounded border px-5 py-2 hover:bg-gray-100 dark:hover:bg-gray-800"
                >
                    Register
                </Link>
            </template> -->
        </nav>

        <!-- Main Hero Section -->
        <div class="flex max-w-3xl flex-col items-center text-center">
            <h1
                class="mb-4 text-4xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]"
            >
                Ticket Management System
            </h1>

            <p
                class="mb-8 text-lg leading-relaxed text-gray-600 dark:text-gray-400"
            >
                A simple and efficient platform to create, track, and manage
                support tickets.<br />
                Fast, lightweight, and built for productivity.
            </p>

            <div class="flex gap-4">
                <Link
                    v-if="!page.props.auth.user"
                    :href="register()"
                    class="rounded-lg bg-[#f53003] px-6 py-3 text-white hover:bg-[#d92700]"
                >
                    Get Started
                </Link>

                <Link
                    v-if="!page.props.auth.user"
                    :href="login()"
                    class="rounded-lg border px-6 py-3 hover:bg-gray-100 dark:hover:bg-gray-800"
                >
                    Log In
                </Link>

                <Link
                    v-if="page.props.auth.user"
                    :href="dashboard()"
                    class="rounded-lg bg-[#f53003] px-6 py-3 text-white hover:bg-[#d92700]"
                >
                    Go to Dashboard
                </Link>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-16 text-sm text-gray-500 dark:text-gray-600">
            © {{ new Date().getFullYear() }} Ticket Management System. All
            rights reserved.
        </footer>
    </div>
</template>
