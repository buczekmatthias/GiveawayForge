<script setup lang="ts">
import { InfiniteScroll } from '@inertiajs/vue3';
import type { HTMLAttributes } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { cn } from '@/lib/utils';
import type { Participant } from '@/types';
import Heading from './Heading.vue';

type Props = {
    participants: { data: Participant[]; meta: any };
    class?: HTMLAttributes['class'];
};

const props = defineProps<Props>();
</script>

<template>
    <div :class="cn('flex flex-col gap-2', props.class)">
        <InfiniteScroll data="participants" preserve-url only-next manual>
            <Heading title="Participants" />
            <Table container-class="max-h-80 rounded-md border lg:max-h-180">
                <TableHeader class="sticky top-0 z-10 bg-background">
                    <TableRow>
                        <TableHead>Name</TableHead>
                        <TableHead class="text-center">Entries</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow
                        v-for="(participant, i) in participants.data"
                        :key="i"
                    >
                        <TableCell>{{ participant.name }}</TableCell>
                        <TableCell class="text-center">
                            {{ participant.entries }}
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <p class="mt-2 text-center text-sm text-muted-foreground">
                Displaying {{ participants.meta.total > 0 ? 1 : 0 }} to
                {{ participants.meta.to ?? 0 }}
                of
                {{ participants.meta.total }}
            </p>

            <template #next="{ loading, fetch, hasMore }">
                <Button
                    v-if="hasMore"
                    @click="fetch"
                    :disabled="loading"
                    variant="secondary"
                    class="sticky bottom-0 z-20 w-full cursor-pointer"
                >
                    {{
                        loading
                            ? 'Loading more participants...'
                            : 'Load more participants'
                    }}
                </Button>
            </template>
        </InfiniteScroll>
    </div>
</template>
