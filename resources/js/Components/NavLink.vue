<script setup lang="ts">
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps<{
    href: string;
    active?: boolean;
}>();

const classes = computed(() =>
    props.active
        ? "inline-flex items-center border-b-2 border-primary px-1 py-1 text-sm font-medium text-foreground transition-colors  "
        : "inline-flex items-center border-b-2 border-transparent px-1 py-1 text-sm font-medium text-foreground transition-colors hover:border-primary",
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
