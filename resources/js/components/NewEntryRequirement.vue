<script setup lang="ts">
import { Button } from '@/components/ui/button';
import type { EntryRequirementTypeValue } from '@/types';

type Props = {
    existingRequirements: any[];
};

const props = defineProps<Props>();

type EntryRequirementType = {
    type: string;
    value: EntryRequirementTypeValue;
    shouldRenderMultipleTimes: boolean;
};

const emit = defineEmits(['typeSelect']);

const types: EntryRequirementType[] = [
    {
        type: 'Button click',
        value: 'button_click',
        shouldRenderMultipleTimes: false,
    },
    {
        type: 'Secret code',
        value: 'secret_code',
        shouldRenderMultipleTimes: true,
    },
    {
        type: 'Question',
        value: 'question',
        shouldRenderMultipleTimes: true,
    },
];

const shouldRender = (type: EntryRequirementType): boolean => {
    if (type.shouldRenderMultipleTimes) {
        return true;
    }

    return !props.existingRequirements.some(
        (entry) => entry.type === type.value,
    );
};
</script>

<template>
    <div class="grid gap-2 rounded-md border p-2 md:grid-cols-2">
        <p class="col-span-full text-center">Select entry type</p>
        <template v-for="type in types" :key="type.value">
            <Button
                @click="emit('typeSelect', type.value)"
                v-if="shouldRender(type)"
                variant="outline"
                class="cursor-pointer"
            >
                {{ type.type }}
            </Button>
        </template>
    </div>
</template>
