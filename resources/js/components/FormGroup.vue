<script setup lang="ts">
import { inject } from 'vue';
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

type Props = {
    groupLabel: string;
    class?: HTMLAttributes['class'];
};

const props = defineProps<Props>();

const required = inject('required');
</script>

<template>
    <div :class="cn('relative rounded-md border p-2 pt-6', props.class)">
        <p class="absolute -top-2.5 left-2 bg-background px-3 text-sm">
            {{ groupLabel }} <sup v-if="required" class="text-red-600">*</sup>
        </p>
        <slot />
    </div>
</template>
