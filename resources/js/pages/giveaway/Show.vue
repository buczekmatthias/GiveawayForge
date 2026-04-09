<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Check, Clock, Trophy, Users } from 'lucide-vue-next';
import { computed } from 'vue';
import EntryRequirementDecorativeText from '@/components/EntryRequirementDecorativeText.vue';
import FormItem from '@/components/FormItem.vue';
import GiveawayStatusIndicator from '@/components/GiveawayStatusIndicator.vue';
import Heading from '@/components/Heading.vue';
import ParticipantsTable from '@/components/ParticipantsTable.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Separator } from '@/components/ui/separator';
import giveaways from '@/routes/giveaways';
import type {
    EntryRequirement,
    EntryRequirementTypeValue,
    Giveaway,
    Participant,
} from '@/types';

defineOptions({
    layout: {
        contentContainerClass:
            'max-w-[96rem] grid xl:grid-cols-[1.5fr_1fr] xl:gap-4 items-start',
    },
});

type Props = {
    giveaway: Giveaway;
    entry_requirements: EntryRequirement[];
    participants: { data: Participant[]; meta: any };
};

type Answer = {
    slug?: string;
    type: EntryRequirementTypeValue;
    answer?: string;
};

const props = defineProps<Props>();

const only = ['giveaway', 'entry_requirements'];

const hasUserJoinedTheGiveaway = computed(
    (): { hasJoined: boolean; entries: number } => {
        const e = props.entry_requirements.filter(
            (e: EntryRequirement) => e.has_user_used_this_requirement,
        );

        return {
            hasJoined: e.length > 0,
            entries: e.reduce((n, { entries }) => n + entries, 0),
        };
    },
);

const answers = computed((): Answer[] =>
    props.entry_requirements
        .filter((entry: EntryRequirement) => entry.type !== 'button_click')
        .map((entry: EntryRequirement) => ({
            slug: entry.slug,
            type: entry.type,
            answer: '',
        })),
);

const getAnswerIndexBySlug = (slug: string): number =>
    answers.value.findIndex((ans: Answer) => ans.slug === slug);

const submitEntry = (req: EntryRequirement): void => {
    const data: Answer = { type: req.type };

    if (req.type !== 'button_click') {
        data.answer = answers.value[getAnswerIndexBySlug(req.slug)].answer;
    }

    router.post(
        giveaways.entry.store({
            giveaway: props.giveaway,
            entry_requirement: req,
        }),
        data,
        { only },
    );
};
</script>

<template>
    <Head :title="giveaway.title" />

    <div class="flex flex-col gap-4 lg:sticky lg:top-0">
        <template v-if="giveaway.banner">
            <img
                :src="giveaway.banner"
                alt="Giveaway banner"
                class="col-span-full max-h-56 w-full object-cover"
            />
        </template>

        <GiveawayStatusIndicator :status="giveaway.status" />

        <Heading :title="giveaway.title" :description="giveaway.description" />

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-[auto_auto_auto]">
            <div class="flex items-start gap-4">
                <Clock class="mt-1 size-5" />
                <Heading
                    title="Duration"
                    :description="`${giveaway.starts_at} - ${giveaway.ends_at}`"
                    variant="small"
                />
            </div>

            <div class="flex items-start gap-4">
                <Trophy class="mt-1 size-5" />
                <Heading
                    title="Winners count"
                    :description="`${giveaway.winners_count} ${giveaway.winners_count === 1 ? 'winner' : 'winners'}`"
                    variant="small"
                />
            </div>

            <div class="flex items-start gap-4">
                <Users class="mt-1 size-5" />
                <Heading
                    title="Participants"
                    :description="`${giveaway.participants_count} ${giveaway.participants_count === 1 ? 'participant' : 'participants'}`"
                    variant="small"
                />
            </div>
        </div>

        <Button
            as-child
            v-if="giveaway.can.update && !giveaway.has_started"
            class="cursor-pointer"
        >
            <Link :href="giveaways.early.start(giveaway)" method="patch" :only>
                Start giveaway now
            </Link>
        </Button>

        <Button
            as-child
            v-if="
                giveaway.can.update &&
                giveaway.has_started &&
                !giveaway.has_ended
            "
            class="cursor-pointer"
        >
            <Link :href="giveaways.early.end(giveaway)" method="patch" :only>
                End giveaway now
            </Link>
        </Button>

        <Separator />

        <Heading
            v-if="!giveaway.has_started"
            title="Giveaway hasn't started yet"
            description="Please come back after start date which you can see above"
        />

        <Heading
            v-if="giveaway.has_ended"
            title="Giveaway has ended"
            :description="
                giveaway.status === 'ended'
                    ? 'Wait for winners to be announced'
                    : 'Check the winners below'
            "
        />

        <template v-if="giveaway.has_started && !giveaway.has_ended">
            <div
                class="flex gap-2 rounded-md border-2 border-emerald-600 bg-emerald-400/10 p-3"
                v-if="hasUserJoinedTheGiveaway.hasJoined"
            >
                <Check class="mt-1 size-5 text-emerald-600" />
                <Heading
                    variant="small"
                    title="You have joined the giveaway"
                    :description="`You own total of ${hasUserJoinedTheGiveaway.entries} ${hasUserJoinedTheGiveaway.entries === 1 ? 'entry' : 'entries'}`"
                />
            </div>

            <div class="rounded-md border">
                <Heading
                    title="Join the giveaway"
                    variant="small"
                    class="m-2.5"
                />
                <div
                    class="bg-red-700 p-3 text-white"
                    v-if="$page.flash.wrong_answer"
                >
                    <p class="text-sm">{{ $page.flash.wrong_answer }}</p>
                </div>
                <div
                    class="grid gap-3 p-2.5 not-last:border-y"
                    v-for="req in entry_requirements"
                    :key="req.slug"
                >
                    <EntryRequirementDecorativeText :type="req.type" />
                    <div
                        class="grid grid-cols-[1fr_auto] gap-2"
                        :class="
                            req.type === 'button_click'
                                ? 'items-center'
                                : 'items-end'
                        "
                    >
                        <template v-if="!req.has_user_used_this_requirement">
                            <p
                                class="max-md:text-sm"
                                :class="{
                                    'col-span-full':
                                        req.type !== 'button_click',
                                }"
                            >
                                {{ req.label }}
                            </p>
                            <template v-if="req.type !== 'button_click'">
                                <FormItem
                                    v-if="req.type === 'secret_code'"
                                    id="secret_code"
                                    label="Secret_code"
                                >
                                    <Input
                                        id="secret_code"
                                        v-model="
                                            answers[
                                                getAnswerIndexBySlug(req.slug)
                                            ].answer
                                        "
                                    />
                                </FormItem>
                                <template v-if="req.type === 'question'">
                                    <p class="col-span-full">
                                        {{ req.config.question }}
                                    </p>
                                    <FormItem id="answer" label="Answer">
                                        <Input
                                            id="answer"
                                            v-model="
                                                answers[
                                                    getAnswerIndexBySlug(
                                                        req.slug,
                                                    )
                                                ].answer
                                            "
                                        />
                                    </FormItem>
                                </template>
                            </template>
                            <Button
                                @click="submitEntry(req)"
                                class="cursor-pointer"
                                variant="secondary"
                            >
                                + {{ req.entries }}
                            </Button>
                        </template>
                        <div class="flex items-center gap-3" v-else>
                            <Check class="text-emerald-600" />
                            <Heading
                                variant="small"
                                title="Action done"
                                :description="`+ ${req.entries} ${req.entries === 1 ? 'entry' : 'entries'} added`"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <Separator class="lg:hidden" />

    <ParticipantsTable
        v-if="giveaway.has_started"
        class="lg:sticky lg:top-0 xl:col-start-2"
        :participants
    />
</template>
