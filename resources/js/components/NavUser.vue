<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { User } from 'lucide-vue-next';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
// import {
//     SidebarMenu,
//     SidebarMenuButton,
//     SidebarMenuItem,
//     useSidebar,
// } from '@/components/ui/sidebar';
import UserMenuContent from '@/components/UserMenuContent.vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
// const { isMobile, state } = useSidebar();
</script>

<template>
    <!-- <SidebarMenu>
        <SidebarMenuItem> -->
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button
                variant="outline"
                class="size-10 cursor-pointer rounded-full p-0"
            >
                <User class="size-5" />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent
            class="w-(--reka-dropdown-menu-trigger-width) min-w-56 rounded-lg"
            side="bottom"
            align="end"
            :side-offset="4"
        >
            <UserMenuContent :user="user" />
        </DropdownMenuContent>
    </DropdownMenu>
    <!-- </SidebarMenuItem>
    </SidebarMenu> -->
</template>
