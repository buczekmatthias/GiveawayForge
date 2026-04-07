<script setup lang="ts">
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';

type Props = {
    label: string;
    id: string;
};

defineProps<Props>();

const model = defineModel<boolean>();
</script>

<template>
    <div class="flex items-center space-x-2 *:cursor-pointer">
        <Switch :id v-model="model" />
        <Label :for="id">{{ label }}</Label>
    </div>
</template>
