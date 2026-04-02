<script setup lang="ts">
import type { GiveawayStatus } from '@/types';

type Props = {
    status: GiveawayStatus;
};

defineProps<Props>();
</script>

<template>
    <p
        class="self-start rounded-md p-2 font-semibold capitalize max-md:text-sm"
        :class="{
            'bg-amber-600/15 text-amber-600 dark:bg-amber-300/15 dark:text-amber-300':
                status === 'scheduled',
            'bg-emerald-700/20 text-emerald-700 dark:bg-emerald-400/15 dark:text-emerald-400':
                status === 'active',
            'bg-red-600/20 text-red-600 dark:bg-red-400/15 dark:text-red-400':
                status === 'ended',
            'bg-fuchsia-600/20 text-fuchsia-600 dark:bg-fuchsia-400/15 dark:text-fuchsia-400':
                status === 'complete',
        }"
    >
        {{ status }}
    </p>
</template>
