<script setup>
import AppSidebar from "@/components/AppSidebar.vue";
import {
    SidebarProvider,
    SidebarInset,
    SidebarTrigger,
} from "@/components/ui/sidebar";
import { Head } from "@inertiajs/vue3";
import DataTable from "@/components/DataTable.vue";
import { h } from "vue";
import { Button } from "@/components/ui/button";
import { ArrowUpDown } from "lucide-vue-next";

const props = defineProps({
    audits: Array,
});

const columns = [
    {
        accessorKey: "created_at",
        header: "Date",
    },
    {
        accessorKey: "event",
        header: "Action",
        cell: ({ getValue }) => h("div", {}, getValue()),
    },
    {
        accessorKey: "user",
        header: "User",
    },
    {
        accessorKey: "tags",
        header: "Note",
        cell: ({ getValue }) => h("div", {}, getValue() || "-"),
    },
];
</script>

<template>
    <Head title="Audit Log" />
    <SidebarProvider>
        <AppSidebar />
        <SidebarInset>
            <header class="flex h-16 items-center gap-2 px-4">
                <SidebarTrigger />
                <h1 class="text-xl font-semibold">Audit Log</h1>
            </header>
            <main class="p-4">
                <section class="bg-white p-4 rounded-lg border shadow">
                    <h2 class="text-lg font-bold mb-4">Activity History</h2>
                    <DataTable
                        :data="audits"
                        :columns="columns"
                        filter-key="user"
                        filter-placeholder="Filter by user..."
                        :pagination="true"
                    />
                </section>
            </main>
        </SidebarInset>
    </SidebarProvider>
</template>
