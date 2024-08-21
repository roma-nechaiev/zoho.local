<script setup lang="ts">
import SiteLayout from "@/Layouts/SiteLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import { Button } from "@/Components/ui/button";
import { Badge } from "@/Components/ui/badge";
import EmptyState from "@/Components/EmptyState.vue";

defineProps<{
    dials?: {
        id: string;
        Deal_Name: string;
        Stage: {};
    }[];
}>();
</script>

<template>
    <Head title="Dials" />

    <SiteLayout>
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight">Dials</h1>
        </div>
        <Table v-if="dials">
            <TableHeader>
                <TableRow>
                    <TableHead class="w-[100px]">Id</TableHead>
                    <TableHead>Name</TableHead>
                    <TableHead>Stage</TableHead>
                    <TableHead class="text-right">
                        <span class="sr-only">Actions</span>
                    </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="dial in dials">
                    <TableCell class="font-medium">
                        {{ dial.id }}
                    </TableCell>
                    <TableCell>{{ dial.Deal_Name }}</TableCell>
                    <TableCell>
                        <Badge variant="secondary">{{ dial.Stage }}</Badge>
                    </TableCell>
                    <TableCell class="text-right">
                        <Button variant="secondary" size="sm">Edit</Button>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
        <EmptyState v-else>No Accounts found</EmptyState>
    </SiteLayout>
</template>
