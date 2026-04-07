<script setup lang="ts">
import { provide } from 'vue';
import InputError from '@/components/InputError.vue';
import InputInfo from '@/components/InputInfo.vue';
import { Label } from '@/components/ui/label';

type Props = {
    id?: string;
    label?: string;
    error?: string | undefined;
    info?: string;
    required?: boolean;
};

const props = defineProps<Props>();

provide('required', props.required);
</script>

<template>
    <div class="grid gap-2">
        <Label :for="id" v-if="label">
            {{ label }}<span v-if="required" class="text-red-600">*</span>
        </Label>
        <slot />
        <InputInfo :message="info" />
        <InputError :message="error" />
    </div>
</template>
