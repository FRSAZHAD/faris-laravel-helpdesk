<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import helpdeskLogo from '@/../img/helpdesk_logo.jpg';


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
