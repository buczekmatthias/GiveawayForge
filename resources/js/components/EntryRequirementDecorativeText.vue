<script setup lang="ts">
import {
    MessageCircleQuestion,
    MousePointerClick,
    RectangleEllipsis,
} from 'lucide-vue-next';
import type { EntryRequirementTypeValue } from '@/types';

type Props = {
    type: EntryRequirementTypeValue;
};

defineProps<Props>();
</script>

<template>
    <div class="flex items-center gap-2">
        <template v-if="type === 'button_click'">
            <MousePointerClick class="size-5 md:size-6" />
            <p class="text-sm md:text-base">Click the button to join</p>
        </template>
        <template v-else-if="type === 'secret_code'">
            <RectangleEllipsis class="size-5 md:size-6" />
            <p class="text-sm md:text-base">Provide secret code</p>
        </template>
        <template v-else>
            <MessageCircleQuestion class="size-5 md:size-6" />
            <p class="text-sm md:text-base">Answer the question</p>
        </template>
    </div>
</template>
