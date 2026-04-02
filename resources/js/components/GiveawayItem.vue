<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Clock, Trophy, Users } from 'lucide-vue-next';
import Heading from '@/components/Heading.vue';
import { Card } from '@/components/ui/card';
import type { Giveaway } from '@/types';
import GiveawayStatusIndicator from './GiveawayStatusIndicator.vue';

type Props = {
    giveaway: Giveaway;
};

defineProps<Props>();
</script>

<template>
    <Link href="#" class="group">
        <Card class="gap-4 px-2 duration-150 group-hover:bg-accent/20">
            <GiveawayStatusIndicator :status="giveaway.status" />

            <Heading variant="small" :title="giveaway.title" />

            <div class="mt-2 flex flex-wrap gap-3">
                <div class="flex items-center gap-2">
                    <Clock class="size-4" />
                    <p class="max-md:text-sm">{{ giveaway.ends_at }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <Trophy class="size-4" />
                    <p class="max-md:text-sm">
                        {{ giveaway.winners_count }}
                        {{ giveaway.winners_count > 1 ? 'winners' : 'winner' }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <Users class="size-4" />
                    <p class="max-md:text-sm">
                        {{ giveaway.participants_count }}
                        {{
                            giveaway.participants_count > 1
                                ? 'participants'
                                : 'participant'
                        }}
                    </p>
                </div>
            </div>
        </Card>
    </Link>
</template>
