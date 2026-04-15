<script setup lang="ts">
import { Head, InfiniteScroll, Link, useForm } from '@inertiajs/vue3';
import { Check } from 'lucide-vue-next';
import { capitalize, computed } from 'vue';
import Switch from '@/components/Switch.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import giveaways from '@/routes/giveaways';
import staff from '@/routes/staff';
import type { Giveaway, GiveawayStatus } from '@/types';

type Props = {
    paginatedGiveaways: { data: Giveaway[] };
    giveaway_statuses: GiveawayStatus[];
    filter: {
        search: string;
        status: string;
        hasBanner: boolean;
        activeColumn: string;
        order: string;
    };
    sortable_columns: string[];
};

const props = defineProps<Props>();

defineOptions({
    layout: {
        contentContainerClass: 'max-w-fit',
    },
});

const searchForm = useForm({
    search: props.filter.search,
    status: props.filter.status,
    hasBanner: props.filter.hasBanner,
    column: props.filter.activeColumn,
    order: props.filter.order,
});

const hasFilters = computed(
    (): boolean =>
        props.filter.search.length > 0 ||
        props.filter.status?.length > 0 ||
        props.filter.hasBanner,
);

const submitSearchForm = () => {
    searchForm.get(staff.giveaways.index().url, {
        only: ['paginatedGiveaways', 'filter'],
        reset: ['paginatedGiveaways'],
    });
};
</script>

<template>
    <Head title="Giveaways" />

    <div class="flex flex-col gap-4">
        <div class="grid grid-cols-[1fr_auto] gap-2.5">
            <Input
                v-model="searchForm.search"
                placeholder="Search by title or user's email..."
            />
            <Button @click="submitSearchForm">Search</Button>
        </div>
        <div class="flex flex-wrap items-center gap-4">
            <Select v-model="searchForm.status">
                <SelectTrigger class="cursor-pointer">
                    <SelectValue placeholder="Select status" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem :value="null" class="cursor-pointer">
                        Any
                    </SelectItem>
                    <SelectItem
                        :value="status"
                        v-for="status in giveaway_statuses"
                        :key="status"
                        class="cursor-pointer"
                    >
                        {{ capitalize(status) }}
                    </SelectItem>
                </SelectContent>
            </Select>

            <Select v-model="searchForm.column">
                <SelectTrigger class="cursor-pointer">
                    <SelectValue placeholder="Select column" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem
                        :value="column"
                        v-for="column in sortable_columns"
                        :key="column"
                        class="cursor-pointer"
                    >
                        {{ capitalize(column.replaceAll('_', ' ')) }}
                    </SelectItem>
                </SelectContent>
            </Select>

            <Select v-model="searchForm.order">
                <SelectTrigger class="cursor-pointer">
                    <SelectValue placeholder="Select order" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem
                        :value="order"
                        v-for="order in ['asc', 'desc']"
                        :key="order"
                        class="cursor-pointer"
                    >
                        {{ capitalize(order) }}
                    </SelectItem>
                </SelectContent>
            </Select>

            <Switch
                id="banner_switch"
                label="Only giveaways with banners"
                v-model="searchForm.hasBanner"
            />

            <TextLink :href="staff.giveaways.index()" v-if="hasFilters"
                >Reset search</TextLink
            >
        </div>
    </div>

    <InfiniteScroll
        items-element="#table-body"
        data="paginatedGiveaways"
        preserve-url
        manual
    >
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Title</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead>Banner</TableHead>
                    <TableHead>Starts at</TableHead>
                    <TableHead>Ends at</TableHead>
                    <TableHead>Winners</TableHead>
                    <TableHead>User</TableHead>
                    <TableHead>Participants</TableHead>
                    <TableHead>Created at</TableHead>
                    <TableHead class="w-32">Actions</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody id="table-body">
                <TableRow
                    v-for="giveaway in paginatedGiveaways.data"
                    :key="giveaway.slug"
                    class="group"
                >
                    <TableCell>{{ giveaway.title }}</TableCell>
                    <TableCell class="capitalize">
                        {{ giveaway.status }}
                    </TableCell>
                    <TableCell>
                        <Check class="mx-auto size-5" v-if="giveaway.banner" />
                    </TableCell>
                    <TableCell>{{ giveaway.starts_at }}</TableCell>
                    <TableCell>{{ giveaway.ends_at }}</TableCell>
                    <TableCell class="text-center">{{
                        giveaway.winners_count
                    }}</TableCell>
                    <TableCell>{{ giveaway.user?.email }}</TableCell>
                    <TableCell class="text-center">
                        {{ giveaway.participants_count }}
                    </TableCell>
                    <TableCell>{{ giveaway.created_at }}</TableCell>
                    <TableCell class="hidden group-hover:table-cell">
                        <Link :href="giveaways.show(giveaway)" class="mr-4">
                            View
                        </Link>
                        <Link
                            :href="giveaways.destroy(giveaway)"
                            :data="{ redirect_back: true }"
                            method="delete"
                            class="cursor-pointer"
                        >
                            Delete
                        </Link>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>

        <template #next="{ loading, fetch, hasMore, manualMode }">
            <Button
                v-if="manualMode && hasMore"
                @click="fetch"
                :disabled="loading"
                class="cursor-pointer"
            >
                {{ loading ? 'Loading...' : 'Load more' }}
            </Button>
        </template>
    </InfiniteScroll>
</template>
