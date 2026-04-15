<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
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
    winners: Participant[];
    class?: HTMLAttributes['class'];
};

const props = defineProps<Props>();
</script>

<template>
    <div :class="cn('flex flex-col gap-2', props.class)">
        <Heading title="Winners" />
        <Table container-class="rounded-md border">
            <TableHeader>
                <TableRow>
                    <TableHead class="w-16 text-center">#</TableHead>
                    <TableHead>Name</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <template v-if="winners.length > 0">
                    <TableRow v-for="(winner, i) in winners" :key="i">
                        <TableCell class="text-center">
                            {{ i + 1 }}
                        </TableCell>
                        <TableCell>{{ winner.name }}</TableCell>
                    </TableRow>
                </template>
                <TableRow v-else>
                    <TableCell :colspan="2" class="text-center">
                        Yet to be announced
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
</template>
