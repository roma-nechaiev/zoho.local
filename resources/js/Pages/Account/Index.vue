<script setup lang="ts">
import SiteLayout from "@/Layouts/SiteLayout.vue";
import { Head } from "@inertiajs/vue3";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import CreateAccountForm from "./Partials/CreateAccountForm.vue";
import CreateDialForm from "./Partials/CreateDialForm.vue";
import EmptyState from "@/Components/EmptyState.vue";

defineProps<{
    accounts?: {
        id: string;
        Account_Name: string;
        Phone: string;
        Website: string;
    }[];
}>();
</script>

<template>
    <Head title="Accounts" />

    <SiteLayout>
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight">Accounts</h1>
            <CreateAccountForm />
        </div>

        <Table v-if="accounts?.length">
            <TableHeader>
                <TableRow>
                    <TableHead class="w-[100px]">Id</TableHead>
                    <TableHead>Account Name</TableHead>
                    <TableHead>Phone</TableHead>
                    <TableHead>Website</TableHead>
                    <TableHead class="text-right">
                        <span class="sr-only">Actions</span>
                    </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="account in accounts">
                    <TableCell class="font-medium">
                        {{ account.id }}
                    </TableCell>
                    <TableCell>
                        {{ account.Account_Name }}
                    </TableCell>
                    <TableCell>{{ account.Phone }}</TableCell>
                    <TableCell>{{ account.Website }}</TableCell>
                    <TableCell class="text-right">
                        <CreateDialForm :account="account.id" />
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
        <EmptyState v-else>No Accounts found</EmptyState>
    </SiteLayout>
</template>
