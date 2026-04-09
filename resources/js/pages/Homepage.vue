<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ChevronRight } from 'lucide-vue-next';
import GiveawayItem from '@/components/GiveawayItem.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import type { Giveaway } from '@/types';

defineOptions({
    layout: {
        contentContainerClass: 'max-w-4xl gap-10',
    },
});

type Props = {
    endedCreatedGiveawaysCount: number;
    createdGiveaways: Giveaway[];
    activeJoinedGiveaways: Giveaway[];
};

defineProps<Props>();
</script>

<template>
    <Head title="Homepage" />

    <Button
        variant="secondary"
        as-child
        class="h-fit justify-between"
        v-if="endedCreatedGiveawaysCount > 0"
    >
        <Link href="#">
            <span class="whitespace-pre-wrap">
                {{ endedCreatedGiveawaysCount }} of your giveaways ended and is
                awaiting for winners to be selected
            </span>
            <ChevronRight class="size-6" />
        </Link>
    </Button>

    <div v-if="$page.props.auth.can_create_giveaway">
        <Heading title="Created giveaways" />

        <div class="flex flex-col gap-4" v-if="createdGiveaways.length > 0">
            <GiveawayItem
                :giveaway
                v-for="giveaway in createdGiveaways"
                :key="giveaway.slug"
            />
        </div>
        <p class="text-sm" v-else>Nothing to display here</p>
    </div>

    <div>
        <Heading title="Active joined giveaways" />

        <div
            class="flex flex-col gap-4"
            v-if="activeJoinedGiveaways.length > 0"
        >
            <GiveawayItem
                :giveaway
                v-for="giveaway in activeJoinedGiveaways"
                :key="giveaway.slug"
            />
        </div>
        <p class="text-sm" v-else>Nothing to display here</p>
    </div>
</template>
