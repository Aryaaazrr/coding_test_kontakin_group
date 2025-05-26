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
import { Head, Link } from "@inertiajs/vue3";
import DataTable from "@/components/DataTable.vue";
import { h } from "vue";
import { Button } from "@/components/ui/button";
import { ArrowUpDown } from "lucide-vue-next";
import { router } from "@inertiajs/vue3";
import { Toaster } from "@/components/ui/toast";
import { useToast } from "@/components/ui/toast/use-toast";

const { toast } = useToast();

const props = defineProps({
    users: Array,
});

const usersColumns = [
    {
        accessorKey: "username",
        header: ({ column }) =>
            h(
                Button,
                {
                    variant: "ghost",
                    onClick: () =>
                        column.toggleSorting(column.getIsSorted() === "asc"),
                    class: "inline-flex items-center justify-center w-full",
                },
                () => [
                    "Username",
                    h(ArrowUpDown, {
                        class: "ml-2 h-4 w-4",
                        "aria-hidden": "true",
                    }),
                ]
            ),
        cell: ({ getValue }) => h("div", { class: "text-center" }, getValue()),
    },
    {
        accessorKey: "email",
        header: () => h("div", { class: "text-center" }, "Email"),
        cell: ({ getValue }) => h("div", { class: "text-center" }, getValue()),
    },
    {
        id: "status",
        header: () =>
            h(
                "div",
                { class: "flex text-center text-sm font-semibold" },
                "Status"
            ),
        cell: ({ row }) => {
            const isDeleted = !!row.original.deleted_at;
            return h(
                "span",
                {
                    class:
                        "text-sm font-semibold text-center " +
                        (isDeleted ? "text-red-600" : "text-green-600"),
                },
                isDeleted ? "Deleted" : "Active"
            );
        },
    },
    {
        id: "actions",
        header: () => h("div", { class: "text-center" }, "Action"),
        cell: ({ row }) => {
            const user = row.original;
            const actions = [];

            if (!user.deleted_at) {
                actions.push(
                    h(
                        Button,
                        {
                            variant: "outline",
                            size: "sm",
                            onClick: () =>
                                router.visit(`/user-account/${user.id}/edit`),
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
                                    confirm(
                                        `Are you sure to delete ${user.username}?`
                                    )
                                ) {
                                    router.delete(`/user-account/${user.id}`, {
                                        preserveScroll: true,
                                        onSuccess: () => {
                                            alert(
                                                `Deleted user account: ${user.username}`
                                            );
                                            router.visit(`/user-account`);
                                        },
                                        onError: () => {
                                            alert(
                                                "Failed to delete user account"
                                            );
                                        },
                                    });
                                }
                            },
                        },
                        () => "Delete"
                    )
                );
            } else {
                actions.push(
                    h(
                        Button,
                        {
                            variant: "outline",
                            size: "sm",
                            class: "text-blue-600",
                            onClick: () => {
                                if (confirm(`Restore ${user.nama_lengkap}?`)) {
                                    router.post(
                                        `/article/${user.id_article}/restore`,
                                        null,
                                        {
                                            preserveScroll: true,
                                            onSuccess: () => {
                                                alert(
                                                    `Restored article: ${user.nama_lengkap}`
                                                );
                                                router.visit(`/article`);
                                            },
                                            onError: () => {
                                                alert(
                                                    "Failed to restore article"
                                                );
                                            },
                                        }
                                    );
                                }
                            },
                        },
                        () => "Restore"
                    ),
                    h(
                        Button,
                        {
                            variant: "outline",
                            size: "sm",
                            class: "text-red-700",
                            onClick: () => {
                                if (
                                    confirm(
                                        `Permanently delete ${user.nama_lengkap}? This action cannot be undone.`
                                    )
                                ) {
                                    router.post(
                                        `/article/${user.id_article}/force-delete`,
                                        null,
                                        {
                                            preserveScroll: true,
                                            onSuccess: () => {
                                                alert(
                                                    `Permanently deleted article: ${user.nama_lengkap}`
                                                );
                                                router.visit(`/article`);
                                            },
                                            onError: () => {
                                                alert(
                                                    "Failed to delete article permanently"
                                                );
                                            },
                                        }
                                    );
                                }
                            },
                        },
                        () => "Force Delete"
                    )
                );
            }

            return h("div", { class: "flex gap-2 justify-center" }, actions);
        },
    },
];
</script>

<template>
    <Head title="User Account" />

    <SidebarProvider>
        <AppSidebar />

        <SidebarInset>
            <header
                class="flex h-16 shrink-0 items-center gap-2 px-4 transition-[width,height] ease-linear group-has-[[data-collapsible=icon]]/sidebar-wrapper:h-12"
            >
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
                            <BreadcrumbPage>User Account</BreadcrumbPage>
                        </BreadcrumbItem>
                    </BreadcrumbList>
                </Breadcrumb>
            </header>

            <main class="flex flex-1 flex-col gap-4 p-4 pt-0">
                <section
                    class="min-h-fit p-4 rounded-xl bg-white border shadow-sm"
                >
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h1 class="text-xl font-semibold">User Account</h1>
                            <p class="text-sm text-gray-600">
                                Form ini untuk create article baru
                            </p>
                        </div>
                        <Link
                            href="/user-account/create"
                            class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded"
                        >
                            Create
                        </Link>
                    </div>

                    <DataTable
                        :data="users"
                        :columns="usersColumns"
                        filter-key="username"
                        filter-placeholder="Filter username..."
                        :show-expand="false"
                        :pagination="true"
                    />
                </section>
            </main>
        </SidebarInset>
        <Toaster />
    </SidebarProvider>
</template>
