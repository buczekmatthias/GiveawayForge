<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
</script>

<template>
    <Button
        v-if="$page.props.auth.can_create_giveaway"
        variant="secondary"
        class="mt-auto"
        as-child
    >
        <Link href="#">
            <Plus class="size-5" />
            Create giveaway
        </Link>
    </Button>
</template>
