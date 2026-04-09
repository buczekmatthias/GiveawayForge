<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import type { ZonedDateTime } from '@internationalized/date';
import { getLocalTimeZone, now } from '@internationalized/date';
import { computed, onMounted, ref } from 'vue';
import Calendar from '@/components/Calendar.vue';
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
import type { EntryRequirementTypeValue, FormEntryRequirement } from '@/types';

const fileInputKey = ref<number>(0);

const newGiveawayForm = useForm({
    title: '',
    description: '',
    banner: null,
    starts_at: '',
    ends_at: '',
    winners_count: 1,
    entry_requirements: [
        {
            type: 'button_click',
            label: '',
            entries: 1,
        },
    ] as FormEntryRequirement[],
});

const mounted = ref(false);
onMounted(() => (mounted.value = true));

const startImmediately = ref<boolean>(true);

const isFormValid = computed((): boolean => {
    return (
        newGiveawayForm.title.length > 0 &&
        typeof newGiveawayForm.ends_at === 'object' &&
        newGiveawayForm.winners_count > 0 &&
        newGiveawayForm.entry_requirements.length > 0
    );
});

const resetBannerInput = (): void => {
    newGiveawayForm.banner = null;
    fileInputKey.value++;
};

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

    newGiveawayForm.entry_requirements.push(entry);
};

const removeEntry = (index: number) => {
    newGiveawayForm.entry_requirements =
        newGiveawayForm.entry_requirements.filter((_, i) => i !== index);
};

const submitForm = () => {
    if (!isFormValid.value) {
        return;
    }

    newGiveawayForm
        .transform((data) => ({
            ...data,
            starts_at: data.starts_at
                ? (
                      data.starts_at as unknown as ZonedDateTime
                  ).toAbsoluteString()
                : null,
            ends_at: (
                data.ends_at as unknown as ZonedDateTime
            ).toAbsoluteString(),
        }))
        .post(giveaways.store().url);
};
</script>

<template>
    <Head title="Create giveaway" />

    <div class="flex flex-col gap-6">
        <Heading
            title="New giveaway"
            description="Bless your community with new giveaway"
        />

        <FormItem
            id="title"
            required
            label="Giveaway title"
            :error="newGiveawayForm.errors.title"
        >
            <Input
                id="title"
                placeholder="Example title..."
                required
                v-model="newGiveawayForm.title"
            />
        </FormItem>

        <FormItem
            id="description"
            label="Giveaway description"
            :error="newGiveawayForm.errors.description"
        >
            <Textarea
                id="description"
                placeholder="Example description..."
                v-model="newGiveawayForm.description"
                class="h-40 resize-none"
            />
        </FormItem>

        <FormItem
            id="banner"
            label="Giveaway banner"
            :error="newGiveawayForm.errors.banner"
        >
            <Input
                type="file"
                id="banner"
                accept="image/png,image/jpeg"
                :key="fileInputKey"
                @change="newGiveawayForm.banner = $event.target.files[0]"
            />
            <button
                v-if="newGiveawayForm.banner !== null"
                class="cursor-pointer justify-self-end text-sky-500"
                @click="resetBannerInput"
            >
                Discard
            </button>
        </FormItem>

        <FormItem
            id="starts_at"
            :error="newGiveawayForm.errors.starts_at"
            :label="startImmediately ? 'Giveaway start' : ''"
        >
            <Calendar
                v-model="newGiveawayForm.starts_at"
                label="Giveaway start"
                :min-date-time="now(getLocalTimeZone())"
                v-if="!startImmediately"
            />
            <Switch
                v-model="startImmediately"
                label="Start immediately"
                id="svtart_immediately"
            />
        </FormItem>

        <FormItem
            id="ends_at"
            :error="newGiveawayForm.errors.ends_at"
            required
            info="Must be at least 10 minutes ahead of submission time"
        >
            <Calendar
                v-model="newGiveawayForm.ends_at"
                label="Giveaway end"
                :min-date-time="now(getLocalTimeZone()).add({ minutes: 10 })"
            />
        </FormItem>

        <FormItem
            id="winners_count"
            label="Giveaway winners count"
            :error="newGiveawayForm.errors.winners_count"
            info="There must be at least 1 winner"
        >
            <Input
                type="number"
                id="winners_count"
                placeholder="1"
                :min="1"
                required
                v-model="newGiveawayForm.winners_count"
            />
        </FormItem>

        <Separator />

        <Heading
            title="Entries"
            description="Define what participants must to do to obtain entries"
        />

        <ManageEntryRequirement
            v-for="(requirement, i) in newGiveawayForm.entry_requirements"
            :key="i"
            v-model="newGiveawayForm.entry_requirements[i]"
            @remove-entry="removeEntry(i)"
            :form="newGiveawayForm"
            :index="i"
        />

        <NewEntryRequirement
            @type-select="addNewRequirement"
            :existing-requirements="newGiveawayForm.entry_requirements"
        />

        <Separator />

        <FormButton
            @click="submitForm"
            :is-processing="newGiveawayForm.processing"
            :disabled="mounted ? !isFormValid : undefined"
            class="cursor-pointer md:self-end"
        >
            Create giveaway
        </FormButton>
    </div>
</template>
