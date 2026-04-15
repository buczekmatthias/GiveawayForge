<script setup lang="ts">
import { InfiniteScroll, router } from '@inertiajs/vue3';
import GiveawayItem from '@/components/GiveawayItem.vue';
import Heading from '@/components/Heading.vue';
import giveaways from '@/routes/giveaways';
import type { Giveaway, GiveawayStatus } from '@/types';

type Props = {
    my_giveaways: { data: Giveaway[] };
    tabs: GiveawayStatus[];
    status_tab: string | null;
};

defineProps<Props>();

const changeTab = (status?: string) => {
    router.visit(giveaways.index(), {
        data: status ? { status } : {},
        only: ['my_giveaways', 'status_tab'],
        reset: ['my_giveaways'],
    });
};
</script>

<template>
    <div class="grid gap-4">
        <Heading
            title="Giveaways"
            description="List of giveaways you created or are part of"
        />

        <div class="flex gap-3">
            <button
                @click="changeTab()"
                class="cursor-pointer py-2"
                :class="{
                    'pointer-events-none rounded-md bg-accent px-4':
                        status_tab === null,
                    'underline-offset-8 hover:underline': status_tab !== null,
                }"
            >
                All
            </button>
            <button
                @click="changeTab(tab)"
                v-for="tab in tabs"
                :key="tab"
                class="cursor-pointer py-2 capitalize"
                :class="{
                    'pointer-events-none rounded-md bg-accent px-4':
                        status_tab === tab,
                    'underline-offset-8 hover:underline': status_tab !== tab,
                }"
            >
                {{ tab }}
            </button>
        </div>

        <InfiniteScroll data="my_giveaways" preserve-url>
            <div class="grid gap-4">
                <GiveawayItem
                    :giveaway
                    v-for="giveaway in my_giveaways.data"
                    :key="giveaway.slug"
                />
            </div>

            <p v-if="my_giveaways.data.length === 0">Nothing to display</p>
        </InfiniteScroll>
    </div>
</template>
