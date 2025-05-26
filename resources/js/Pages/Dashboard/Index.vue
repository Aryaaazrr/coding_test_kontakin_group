<script setup>
import AppSidebar from "@/components/AppSidebar.vue";
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/components/ui/breadcrumb";
import { Separator } from "@/components/ui/separator";
import {
    SidebarInset,
    SidebarProvider,
    SidebarTrigger,
} from "@/components/ui/sidebar";
import { Head, usePage, Link } from "@inertiajs/vue3";
import DataTable from "@/components/DataTable.vue";
import { h, ref } from "vue";
import { Button } from "@/components/ui/button";
import { ArrowUpDown } from "lucide-vue-next";
import { router } from '@inertiajs/vue3';

const props = defineProps({
    roles: Array,
});

const roles = ref([...props.roles])

const roleColumns = [
    {
        accessorKey: "name",
        header: ({ column }) =>
            h(
                Button,
                {
                    variant: "ghost",
                    onClick: () =>
                        column.toggleSorting(column.getIsSorted() === "asc"),
                    class: "inline-flex items-center justify-center",
                },
                () => ["Role", h(ArrowUpDown, { class: "ml-2 h-4 w-4" })]
            ),
    },
    {
        accessorKey: "permissions",
        header: () => h("div", { class: "text-center" }, "Permissions"),
        cell: ({ row }) => {
            const permissions = row.original.permissions || [];
            return h(
                "div",
                { class: "flex flex-wrap gap-1 justify-center" },
                permissions.map((p) =>
                    h(
                        "span",
                        {
                            class: "inline-block bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded",
                        },
                        p.name
                    )
                )
            );
        },
    },
    {
        id: "actions",
        header: () => h("div", { class: "text-center" }, "Action"),
        cell: ({ row }) => {
            const role = row.original;
            return h("div", { class: "flex gap-2 justify-center" }, [
                h(
                    Button,
                    {
                        variant: "outline",
                        size: "sm",
                        onClick: () =>
                            router.visit(`/dashboard/${role.id}`),
                        class: "text-blue-600",
                    },
                    () => "View"
                ),
                h(
                    Button,
                    {
                        variant: "outline",
                        size: "sm",
                        onClick: () =>
                            router.visit(`/dashboard/${role.id}/edit`),
                        class: "text-yellow-600",
                    },
                    () => "Edit"
                ),
                h(
                    Button,
                    {
                        variant: "outline",
                        size: "sm",
                        class: "text-red-600",
                        onClick: () => {
                            if (
                                confirm(`Are you sure to delete ${role.name}?`)
                            ) {
                                router.delete(`/dashboard/${role.id}`, {
                                    preserveScroll: true,
                                    onSuccess: () => {
                                        alert(`Deleted role: ${role.name}`);
                                         router.visit(`/dashboard`);
                                    },
                                    onError: () => {
                                        alert("Failed to delete role");
                                    },
                                });
                            }
                        },
                    },
                    () => "Delete"
                ),
            ]);
        },
    },
];
</script>

<template>
    <Head title="Dashboard" />
    <SidebarProvider>
        <AppSidebar />
        <SidebarInset>
            <header
                class="flex h-16 shrink-0 items-center gap-2 transition-[width,height] ease-linear group-has-[[data-collapsible=icon]]/sidebar-wrapper:h-12"
            >
                <div class="flex items-center gap-2 px-4">
                    <SidebarTrigger class="-ml-1" />
                    <Separator orientation="vertical" class="mr-2 h-4" />
                    <Breadcrumb>
                        <BreadcrumbList>
                            <BreadcrumbItem class="hidden md:block">
                                <BreadcrumbLink href="/dashboard">
                                    {{ $page.props.auth.user.username }}
                                </BreadcrumbLink>
                            </BreadcrumbItem>
                            <BreadcrumbSeparator class="hidden md:block" />
                            <BreadcrumbItem>
                                <BreadcrumbPage>Dashboard</BreadcrumbPage>
                            </BreadcrumbItem>
                        </BreadcrumbList>
                    </Breadcrumb>
                </div>
            </header>

            <div class="flex flex-1 flex-col gap-4 p-4 pt-0">
                <div class="grid auto-rows-min gap-4 md:grid-cols-1">
                    <div
                        class="min-h-fit p-4 flex-1 rounded-xl bg-white border"
                    >
                        <div class="flex justify-between items-center">
                            <div class="flex flex-col items-start">
                                <h1 class="text-xl font-semibold">
                                    Role Management
                                </h1>
                                <p>Form this for create role</p>
                            </div>
                            <Button
                                variant="solid"
                                class="bg-green-600 hover:bg-green-700 text-white"
                            >
                                <Link
                                    href="/dashboard/create"
                                    class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded"
                                >
                                    Create
                                </Link>
                            </Button>
                        </div>
                        <DataTable
                            :data="roles"
                            :columns="roleColumns"
                            filter-key="name"
                            filter-placeholder="Filter role name..."
                            :show-expand="false"
                            :pagination="true"
                        />
                    </div>

                    
                </div>
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
