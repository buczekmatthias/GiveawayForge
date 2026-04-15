<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import FormButton from '@/components/FormButton.vue';
import FormItem from '@/components/FormItem.vue';
import Heading from '@/components/Heading.vue';
import ManageEntryRequirement from '@/components/ManageEntryRequirement.vue';
import NewEntryRequirement from '@/components/NewEntryRequirement.vue';
import Switch from '@/components/Switch.vue';
import { Input } from '@/components/ui/input';
import { Separator } from '@/components/ui/separator';
import { Textarea } from '@/components/ui/textarea';
import giveaways from '@/routes/giveaways';
import type {
    EntryRequirementTypeValue,
    FormEntryRequirement,
    Giveaway,
} from '@/types';

type Props = {
    giveaway: Giveaway;
};

const props = defineProps<Props>();

const editGiveawayForm = useForm({
    title: props.giveaway.title,
    description: props.giveaway.description,
    delete_banner: false,
    winners_count: props.giveaway.winners_count,
    entry_requirements: props.giveaway
        .entry_requirements as FormEntryRequirement[],
});

const mounted = ref(false);
onMounted(() => (mounted.value = true));

const isFormValid = computed((): boolean => {
    return (
        editGiveawayForm.title.length > 0 &&
        editGiveawayForm.winners_count > 0 &&
        editGiveawayForm.entry_requirements.length > 0
    );
});

const addNewRequirement = (entryType: EntryRequirementTypeValue): void => {
    const entry: FormEntryRequirement = {
        type: entryType,
        entries: 1,
        label: '',
    };

    if (entryType === 'secret_code') {
        entry.config = {
            secret_code: '',
        };
    }

    if (entryType === 'question') {
        entry.config = {
            question: '',
            answer: '',
        };
    }

    editGiveawayForm.entry_requirements.push(entry);
};

const removeEntry = (index: number) => {
    editGiveawayForm.entry_requirements =
        editGiveawayForm.entry_requirements.filter((_, i) => i !== index);
};

const submitForm = () => {
    if (!isFormValid.value) {
        return;
    }

    editGiveawayForm.patch(giveaways.update(props.giveaway).url);
};
</script>

<template>
    <Head title="Update giveaway" />

    <div class="flex flex-col gap-6">
        <Heading
            title="Edit giveaway"
            description="Adjust your giveaway content"
        />

        <FormItem
            id="title"
            required
            label="Giveaway title"
            :error="editGiveawayForm.errors.title"
        >
            <Input
                id="title"
                placeholder="Example title..."
                required
                v-model="editGiveawayForm.title"
            />
        </FormItem>

        <FormItem
            id="description"
            label="Giveaway description"
            :error="editGiveawayForm.errors.description"
        >
            <Textarea
                id="description"
                placeholder="Example description..."
                v-model="editGiveawayForm.description"
                class="h-40 resize-none"
            />
        </FormItem>

        <div class="flex flex-col" v-if="giveaway.banner">
            <Heading variant="small" title="Banner" />
            <img :src="giveaway.banner" class="mb-3" />
            <Switch
                id="banner"
                label="Remove banner"
                v-model="editGiveawayForm.delete_banner"
            />
        </div>

        <FormItem
            id="winners_count"
            label="Giveaway winners count"
            :error="editGiveawayForm.errors.winners_count"
            info="There must be at least 1 winner"
        >
            <Input
                type="number"
                id="winners_count"
                placeholder="1"
                :min="1"
                required
                v-model="editGiveawayForm.winners_count"
            />
        </FormItem>

        <Separator />

        <Heading
            title="Entries"
            description="Define what participants must to do to obtain entries"
        />

        <ManageEntryRequirement
            v-for="(requirement, i) in editGiveawayForm.entry_requirements"
            :key="i"
            v-model="editGiveawayForm.entry_requirements[i]"
            @remove-entry="removeEntry(i)"
            :form="editGiveawayForm"
            :index="i"
        />

        <NewEntryRequirement
            @type-select="addNewRequirement"
            :existing-requirements="editGiveawayForm.entry_requirements"
        />

        <Separator />

        <FormButton
            @click="submitForm"
            :is-processing="editGiveawayForm.processing"
            :disabled="mounted ? !isFormValid : undefined"
            class="cursor-pointer md:self-end"
        >
            Update giveaway
        </FormButton>
    </div>
</template>
