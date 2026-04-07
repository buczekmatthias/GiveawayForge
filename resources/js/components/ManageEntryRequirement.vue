<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import {
    MessageCircleQuestion,
    MousePointerClick,
    RectangleEllipsis,
    Trash,
} from 'lucide-vue-next';
import FormItem from '@/components/FormItem.vue';
import { Input } from '@/components/ui/input';
import type { EntryRequirement } from '@/types';

type Props = {
    form: any;
    index: number;
};

defineProps<Props>();

const model = defineModel<EntryRequirement>();

type DefaultLabels = {
    [key: string]: string;
};

const defaultLabels = usePage().props.defaultLabels as DefaultLabels;

const emit = defineEmits(['removeEntry']);
</script>

<template>
    <div class="grid gap-4 rounded-md border p-2.5 md:grid-cols-[2fr_1fr]">
        <div class="col-span-full flex items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <template v-if="model!.type === 'button_click'">
                    <MousePointerClick class="size-5 md:size-6" />
                    <p class="text-sm md:text-base">Click the button to join</p>
                </template>
                <template v-else-if="model!.type === 'secret_code'">
                    <RectangleEllipsis class="size-5 md:size-6" />
                    <p class="text-sm md:text-base">Provide secret code</p>
                </template>
                <template v-else>
                    <MessageCircleQuestion class="size-5 md:size-6" />
                    <p class="text-sm md:text-base">Answer the question</p>
                </template>
            </div>
            <button @click="emit('removeEntry')" class="cursor-pointer">
                <Trash class="size-5 text-red-500 md:size-6" />
            </button>
        </div>

        <FormItem
            id="label"
            label="Label"
            info="Keep input empty to use default label"
            :error="form.errors.entry_requirements?.[index].label"
        >
            <Input
                id="label"
                :placeholder="defaultLabels[model!.type]"
                v-model="model!.label"
            />
        </FormItem>

        <FormItem
            id="entries"
            label="Entries"
            info="Must be at least 1 entry"
            :error="form.errors.entry_requirements?.[index].entries"
        >
            <Input
                id="entries"
                type="number"
                placeholder="1"
                :min="1"
                v-model="model!.entries"
            />
        </FormItem>

        <FormItem
            id="secret_code"
            required
            label="Secret code"
            v-if="model!.type === 'secret_code'"
            :error="
                form.errors[`entry_requirements.${index}.config.secret_code`]
            "
        >
            <Input
                id="secret_code"
                placeholder="sa#gfa2gSSD53"
                v-model="model!.config.secret_code"
            />
        </FormItem>

        <template v-if="model!.type === 'question'">
            <FormItem
                id="question"
                required
                label="Question"
                :error="
                    form.errors[`entry_requirements.${index}.config.question`]
                "
            >
                <Input
                    id="question"
                    placeholder="Are you hungry?"
                    v-model="model!.config.question"
                />
            </FormItem>

            <FormItem
                id="answer"
                required
                label="Answer"
                :error="
                    form.errors[`entry_requirements.${index}.config.answer`]
                "
            >
                <Input
                    id="answer"
                    placeholder="Yes, I am."
                    v-model="model!.config.answer"
                />
            </FormItem>
        </template>
    </div>
</template>
