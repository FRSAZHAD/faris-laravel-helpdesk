<script setup lang="ts">
import helpdeskLogo from '@/../img/helpdesk_logo.jpg';
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <img
        :src="helpdeskLogo"
        alt="helpdesk_logo"
        :class="className"
        v-bind="$attrs"
    />
</template>
