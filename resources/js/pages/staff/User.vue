<script setup lang="ts">
import { Head, InfiniteScroll, Link, useForm } from '@inertiajs/vue3';
import { Check } from 'lucide-vue-next';
import { capitalize, computed } from 'vue';
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
import staff from '@/routes/staff';
import users from '@/routes/staff/users';
import type { Role, UserListItem } from '@/types';

type Props = {
    paginatedUsers: { data: UserListItem[] };
    user_roles: Role[];
    filter: {
        search: string;
        role: Role;
        activeColumn: string;
        order: string;
    };
    sortable_columns: string[];
};

const props = defineProps<Props>();

const searchForm = useForm({
    search: props.filter.search,
    role: props.filter.role,
    column: props.filter.activeColumn,
    order: props.filter.order,
});

const hasFilters = computed(
    (): boolean =>
        props.filter.search.length > 0 || props.filter.role.length > 0,
);

const submitSearchForm = () => {
    searchForm.get(staff.users.index().url, {
        only: ['paginatedUsers', 'filter'],
        reset: ['paginatedUsers'],
    });
};
</script>

<template>
    <Head title="Giveaways" />

    <div class="flex flex-col gap-4">
        <div class="grid grid-cols-[1fr_auto] gap-2.5">
            <Input
                v-model="searchForm.search"
                placeholder="Search by name or email..."
            />
            <Button @click="submitSearchForm" class="cursor-pointer">
                Search
            </Button>
        </div>
        <div class="flex flex-wrap items-center gap-4">
            <Select v-model="searchForm.role">
                <SelectTrigger class="cursor-pointer">
                    <SelectValue placeholder="Select role" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem :value="null" class="cursor-pointer">
                        Any
                    </SelectItem>
                    <SelectItem
                        :value="role"
                        v-for="role in user_roles"
                        :key="role"
                        class="cursor-pointer"
                    >
                        {{ capitalize(role) }}
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

            <TextLink :href="staff.users.index()" v-if="hasFilters">
                Reset search
            </TextLink>
        </div>
    </div>

    <InfiniteScroll
        items-element="#table-body"
        data="paginatedUsers"
        preserve-url
        manual
    >
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Name</TableHead>
                    <TableHead>Role</TableHead>
                    <TableHead>E-mail</TableHead>
                    <TableHead class="text-center">Verified</TableHead>
                    <TableHead>Created at</TableHead>
                    <TableHead class="w-36">Actions</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody id="table-body">
                <TableRow
                    v-for="user in paginatedUsers.data"
                    :key="user.slug"
                    class="group"
                >
                    <TableCell>{{ user.name }}</TableCell>
                    <TableCell class="capitalize">
                        {{ user.role }}
                    </TableCell>
                    <TableCell>
                        {{ user.email }}
                    </TableCell>
                    <TableCell>
                        <Check
                            class="mx-auto size-5"
                            v-if="user.email_verified_at"
                        />
                    </TableCell>
                    <TableCell>{{ user.created_at }}</TableCell>
                    <TableCell class="opacity-0 group-hover:opacity-100">
                        <Link
                            :href="users.update(user)"
                            :data="{ action: 'promote' }"
                            method="patch"
                            class="mr-4 cursor-pointer"
                            v-if="user.can.promote"
                        >
                            Promote
                        </Link>
                        <Link
                            :href="users.update(user)"
                            :data="{ action: 'demote' }"
                            method="patch"
                            class="mr-4 cursor-pointer"
                            v-if="user.can.demote"
                        >
                            Demote
                        </Link>
                        <Link
                            :href="users.destroy(user)"
                            method="delete"
                            class="cursor-pointer"
                            v-if="user.can.delete"
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
