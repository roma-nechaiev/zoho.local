<script setup lang="ts">
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import NavLink from "@/Components/NavLink.vue";
</script>

<template>
    <header class="border-border bg-muted text-accent-foreground">
        <div className="container flex h-14  gap-6 items-stretch">
            <div class="flex shrink-0 items-center">
                <ApplicationLogo class="size-9 fill-primary" />
            </div>
            <nav class="-my-px flex flex-1 gap-4">
                <NavLink
                    :href="route('home')"
                    :active="route().current('home')"
                >
                    Accounts
                </NavLink>
                <NavLink
                    :href="route('dials.index')"
                    :active="route().current('dials.index')"
                    >Dials
                </NavLink>
            </nav>
        </div>
    </header>
</template>
