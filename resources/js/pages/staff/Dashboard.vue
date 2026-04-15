<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Gift, Users } from 'lucide-vue-next';
import { capitalize } from 'vue';
import Heading from '@/components/Heading.vue';
import { Card } from '@/components/ui/card';

type Props = {
    giveaways_count: {
        total: number;
        active: number;
        complete: number;
        created_this_month: number;
    };
    users_count: {
        total: number;
        created_this_month: number;
    };
    participants_count: {
        total: number;
        joined_this_month: number;
    };
};

defineProps<Props>();
</script>

<template>
    <Head title="Dashboard" />

    <Heading title="Dashboard" description="View brief summary about entries" />

    <div class="grid gap-4">
        <Card class="grid px-4 md:grid-cols-2">
            <div class="col-span-full flex items-center gap-2.5">
                <Gift class="size-5" />
                <Heading variant="small" title="Giveaways" />
            </div>

            <Heading
                v-for="[k, v] in Object.entries(giveaways_count)"
                :key="k"
                :title="v.toString()"
                :description="capitalize(k.replaceAll('_', ' '))"
                variant="small"
            />
        </Card>

        <Card class="grid px-4 md:grid-cols-2">
            <div class="col-span-full flex items-center gap-2.5">
                <Users class="size-5" />
                <Heading variant="small" title="Users" />
            </div>

            <Heading
                v-for="[k, v] in Object.entries(users_count)"
                :key="k"
                :title="v.toString()"
                :description="capitalize(k.replaceAll('_', ' '))"
                variant="small"
            />
        </Card>

        <Card class="grid px-4 md:grid-cols-2">
            <div class="col-span-full flex items-center gap-2.5">
                <Users class="size-5" />
                <Heading variant="small" title="Participants" />
            </div>

            <Heading
                v-for="[k, v] in Object.entries(participants_count)"
                :key="k"
                :title="v.toString()"
                :description="capitalize(k.replaceAll('_', ' '))"
                variant="small"
            />
        </Card>
    </div>
</template>
