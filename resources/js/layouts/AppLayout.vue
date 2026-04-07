<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { House } from 'lucide-vue-next';
import CreateGiveawayButton from '@/components/CreateGiveawayButton.vue';
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
import type { NavItem } from '@/types';

type Props = {
    contentContainerClass?: string;
};

defineProps<Props>();

const navigation: NavItem[] = [
    {
        title: 'Homepage',
        href: home(),
        icon: House,
    },
];

const { isCurrentUrl } = useCurrentUrl();
</script>

<template>
    <div class="grid h-dvh w-full grid-cols-[auto_1fr] grid-rows-[auto_1fr]">
        <div class="col-span-full flex items-center gap-4 border-b px-4 py-3">
            <Heading
                variant="small"
                :title="$page.props.name"
                class="mr-auto"
            />
            <CreateGiveawayButton />
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
