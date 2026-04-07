<script setup lang="ts">
import type { ButtonVariants } from '@/components/ui/button';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';

type Props = {
    isProcessing: boolean;
    disabled?: boolean;
    variant?: ButtonVariants['variant'];
};

defineProps<Props>();
</script>

<template>
    <Button :disabled="isProcessing || disabled" :variant>
        <Spinner v-if="isProcessing" />
        <slot />
    </Button>
</template>
