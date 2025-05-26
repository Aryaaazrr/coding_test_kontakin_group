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

const props = defineProps({
    dosen: Array,
});

const dosenColumns = [
    {
        accessorKey: "nama_lengkap",
        header: ({ column }) =>
            h(
                Button,
                {
                    variant: "ghost",
                    onClick: () =>
                        column.toggleSorting(column.getIsSorted() === "asc"),
                    class: "inline-flex items-center justify-center",
                },
                () => ["Nama", h(ArrowUpDown, { class: "ml-2 h-4 w-4" })]
            ),
    },
    {
        accessorKey: "keahlian",
        header: () => h("div", { class: "text-center" }, "Keahlian"),
        cell: ({ row }) => {
            let keahlian = row.original.keahlian;

            if (typeof keahlian === "string") {
                try {
                    keahlian = JSON.parse(keahlian);
                } catch {
                    keahlian = [];
                }
            }

            if (!Array.isArray(keahlian)) {
                keahlian = [];
            }

            return h(
                "div",
                { class: "flex flex-wrap gap-1 justify-center" },
                keahlian.map((p) =>
                    h(
                        "span",
                        {
                            class: "inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded",
                        },
                        p
                    )
                )
            );
        },
    },
    {
        id: "status",
        header: () => h("div", { class: "text-S" }, "Status"),
        cell: ({ row }) => {
            return h(
                "span",
                {
                    class: row.original.deleted_at
                        ? "text-red-600 text-sm text-center font-semibold"
                        : "text-green-600 text-sm text-center font-semibold",
                },
                row.original.deleted_at ? "Deleted" : "Active"
            );
        },
    },
    {
        id: "actions",
        header: () => h("div", { class: "text-center" }, "Action"),
        cell: ({ row }) => {
            const dosen = row.original;
            const actions = [];

            if (!dosen.deleted_at) {
                actions.push(
                    h(
                        Button,
                        {
                            variant: "outline",
                            size: "sm",
                            onClick: () =>
                                router.visit(`/dosen/${dosen.id_dosen}/edit`),
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
                                        `Are you sure to delete ${dosen.nama_lengkap}?`
                                    )
                                ) {
                                    router.delete(`/dosen/${dosen.id_dosen}`, {
                                        preserveScroll: true,
                                        onSuccess: () => {
                                            alert(
                                                `Deleted dosen: ${dosen.nama_lengkap}`
                                            );
                                            router.visit(`/dosen`);
                                        },
                                        onError: () => {
                                            alert("Failed to delete dosen");
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
                                if (confirm(`Restore ${dosen.nama_lengkap}?`)) {
                                    router.post(
                                        `/dosen/${dosen.id_dosen}/restore`,
                                        {
                                            preserveScroll: true,
                                            onSuccess: () => {
                                                alert(
                                                    `Resrtored dosen: ${dosen.nama_lengkap}`
                                                );
                                                router.visit(`/dosen`);
                                            },
                                            onError: () => {
                                                alert("Failed to delete dosen");
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
                                        `Permanently delete ${dosen.nama_lengkap}?`
                                    )
                                ) {
                                    router.post(
                                        `/dosen/${dosen.id_dosen}/force-delete`,
                                        {
                                            preserveScroll: true,
                                            onSuccess: () => {
                                                alert(
                                                    `Resrtored dosen: ${dosen.nama_lengkap}`
                                                );
                                                router.visit(`/dosen`);
                                            },
                                            onError: () => {
                                                alert("Failed to delete dosen");
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
    <Head title="Dosen" />
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
                                <BreadcrumbPage>Dosen</BreadcrumbPage>
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
                                <h1 class="text-xl font-semibold">Dosen</h1>
                                <p>Form ini untuk create dosen baru</p>
                            </div>
                            <Link
                                href="/dosen/create"
                                class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded"
                            >
                                Create
                            </Link>
                        </div>

                        <DataTable
                            :data="dosen"
                            :columns="dosenColumns"
                            filter-key="nama_lengkap"
                            filter-placeholder="Filter nama dosen..."
                            :show-expand="false"
                            :pagination="true"
                        />
                    </div>
                </div>
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
