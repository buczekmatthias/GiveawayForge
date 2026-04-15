<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Gift, Grid, Users } from 'lucide-vue-next';
import Heading from '@/components/Heading.vue';
import NavUser from '@/components/NavUser.vue';
import { Button } from '@/components/ui/button';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { cn } from '@/lib/utils';
import { home } from '@/routes';
import staff from '@/routes/staff';
import type { NavItem } from '@/types';

type Props = {
    contentContainerClass?: string;
};

defineProps<Props>();

const navigation: NavItem[] = [
    {
        title: 'Dashboard',
        href: staff.dashboard(),
        icon: Grid,
    },
    {
        title: 'Users',
        href: staff.users.index(),
        icon: Users,
    },
    {
        title: 'Giveaways',
        href: staff.giveaways.index(),
        icon: Gift,
    },
];

const { isCurrentUrl } = useCurrentUrl();
</script>

<template>
    <div class="grid h-dvh w-full grid-cols-[auto_1fr] grid-rows-[auto_1fr]">
        <div class="col-span-full flex items-center gap-4 border-b px-4 py-3">
            <Link :href="home()" class="mr-auto">
                <Heading variant="small" :title="$page.props.name" />
            </Link>
            <NavUser />
        </div>
        <div class="flex flex-col justify-center gap-4 border-r p-2">
            <template v-for="item in navigation" :key="item.title">
                <TooltipProvider>
                    <Tooltip>
                        <TooltipTrigger as-child>
                            <Button
                                :variant="
                                    isCurrentUrl(item.href)
                                        ? 'default'
                                        : 'ghost'
                                "
                                :class="{
                                    'pointer-events-none': isCurrentUrl(
                                        item.href,
                                    ),
                                }"
                                as-child
                            >
                                <Link :href="item.href">
                                    <component :is="item.icon" />
                                </Link>
                            </Button>
                        </TooltipTrigger>
                        <TooltipContent side="right">
                            <p>{{ item.title }}</p>
                        </TooltipContent>
                    </Tooltip>
                </TooltipProvider>
            </template>
        </div>
        <div
            :class="
                cn(
                    'mx-auto flex w-full max-w-5xl flex-col gap-6 overflow-auto p-4',
                    contentContainerClass,
                )
            "
        >
            <slot />
        </div>
    </div>
</template>
