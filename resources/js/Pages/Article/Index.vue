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
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import DataTable from "@/components/DataTable.vue";
import { h, ref } from "vue";
import { Button } from "@/components/ui/button";
import { ArrowUpDown } from "lucide-vue-next";
import axios from "axios";

const allFields = [
    "id_article",
    "judul",
    "tipe",
    "id_mahasiswa",
    "id_dosen",
    "file",
    "created_at",
];
const selectedFields = ref([]);
const selectedFieldsImport = ref([]);
const importFile = ref(null);

const props = defineProps({
    article: Array,
});

const articleColumns = [
    {
        accessorKey: "nama_lengkap",
        header: ({ column }) =>
            h(
                Button,
                {
                    variant: "ghost",
                    onClick: () =>
                        column.toggleSorting(column.getIsSorted() === "asc"),
                },
                () => ["Mahasiswa", h(ArrowUpDown, { class: "ml-2 h-4 w-4" })]
            ),
        cell: ({ row }) =>
            h(
                "div",
                { class: "text-center" },
                row.original.nama_lengkap || "-"
            ),
    },
    {
        accessorKey: "dosen",
        header: () => h("div", { class: "text-center" }, "Dosen"),
        cell: ({ row }) =>
            h("div", { class: "text-center" }, row.original.dosen || "-"),
    },
    {
        accessorKey: "judul",
        header: () => h("div", { class: "text-center" }, "Judul"),
        cell: ({ row }) =>
            h("div", { class: "text-center" }, row.original.judul || "-"),
    },
    {
        accessorKey: "tipe",
        header: () => h("div", { class: "text-center" }, "Tipe"),
        cell: ({ row }) =>
            h(
                "div",
                { class: "text-center capitalize" },
                row.original.tipe || "-"
            ),
    },
    {
        accessorKey: "disetujui",
        header: () => h("div", { class: "text-center" }, "Disetujui"),
        cell: ({ row }) => {
            const disetujui = row.original.disetujui;
            return h(
                "div",
                { class: "text-center" },
                disetujui === true || disetujui === 1
                    ? "Ya"
                    : disetujui === false || disetujui === 0
                    ? "Tidak"
                    : "-"
            );
        },
    },
    {
        id: "status",
        header: () => h("div", { class: "text-center" }, "Status"),
        cell: ({ row }) =>
            h(
                "div",
                { class: "text-center" },
                h(
                    "span",
                    {
                        class: row.original.deleted_at
                            ? "text-red-600 font-semibold"
                            : "text-green-600 font-semibold",
                    },
                    row.original.deleted_at ? "Deleted" : "Active"
                )
            ),
    },
    {
        id: "actions",
        header: () => h("div", { class: "text-center" }, "Actions"),
        cell: ({ row }) => {
            const article = row.original;
            const buttons = [];

            if (!article.deleted_at) {
                buttons.push(
                    h(
                        Button,
                        {
                            variant: "outline",
                            size: "sm",
                            class: "text-yellow-600",
                            onClick: () =>
                                router.visit(
                                    `/article/${article.id_article}/edit`
                                ),
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
                                if (confirm(`Yakin hapus ${article.judul}?`)) {
                                    router.delete(
                                        `/article/${article.id_article}`,
                                        { preserveScroll: true }
                                    );
                                }
                            },
                        },
                        () => "Delete"
                    )
                );
            } else {
                buttons.push(
                    h(
                        Button,
                        {
                            variant: "outline",
                            size: "sm",
                            class: "text-blue-600",
                            onClick: () => {
                                if (confirm(`Restore ${article.judul}?`)) {
                                    router.post(
                                        `/article/${article.id_article}/restore`,
                                        { preserveScroll: true }
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
                                    confirm(`Hapus permanen ${article.judul}?`)
                                ) {
                                    router.post(
                                        `/article/${article.id_article}/force-delete`,
                                        { preserveScroll: true }
                                    );
                                }
                            },
                        },
                        () => "Force Delete"
                    )
                );
            }

            return h("div", { class: "flex gap-2 justify-center" }, buttons);
        },
    },
];

function submitExport() {
    axios
        .post("/article/export", { fields: selectedFields.value }, { responseType: "blob" })
        .then((res) => {
            const blob = new Blob([res.data], { type: res.headers['content-type'] });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "articles.xlsx";
            link.click();
            URL.revokeObjectURL(link.href);
        })
        .catch((err) => {
            if (err.response && err.response.data) {
                const reader = new FileReader();
                reader.onload = () => {
                    try {
                        const error = JSON.parse(reader.result);
                        alert("Export gagal: " + error.message);
                    } catch {
                        alert("Export gagal (tidak dapat membaca pesan).");
                    }
                };
                reader.readAsText(err.response.data);
            } else {
                alert("Export gagal: " + err.message);
            }
        });
}

function handleFile(e) {
    importFile.value = e.target.files[0];
}

function submitImport() {
    const formData = new FormData();
    formData.append("file", importFile.value);

    selectedFieldsImport.value.forEach((field) => {
        formData.append("fields[]", field);
    });

    axios
        .post("/article/import", formData, {
            headers: { "Content-Type": "multipart/form-data" },
        })
        .then((res) => alert(res.data.message))
        .catch((err) => {
            if (err.response && err.response.data && err.response.data.message) {
                alert("Error import: " + err.response.data.message);
            } else {
                alert("Error import: " + err.message);
            }
        });
}

</script>

<template>
    <Head title="Article" />
    <SidebarProvider>
        <AppSidebar />
        <SidebarInset>
            <header class="flex h-16 items-center gap-2 px-4">
                <SidebarTrigger />
                <Separator orientation="vertical" class="h-4 mx-2" />
                <Breadcrumb>
                    <BreadcrumbList>
                        <BreadcrumbItem class="hidden md:block">
                            <BreadcrumbLink href="/dashboard">
                                {{ $page.props.auth.user.username }}
                            </BreadcrumbLink>
                        </BreadcrumbItem>
                        <BreadcrumbSeparator class="hidden md:block" />
                        <BreadcrumbItem>
                            <BreadcrumbPage>Article</BreadcrumbPage>
                        </BreadcrumbItem>
                    </BreadcrumbList>
                </Breadcrumb>
            </header>

            <main class="flex-1 p-6 bg-gray-50">
                <div class="bg-white rounded-xl border p-6 shadow-md">
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-6"
                    >
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-800">
                                Article
                            </h1>
                            <p class="text-gray-500 mt-1">
                                Manage your articles here
                            </p>
                        </div>

                        <Link
                            href="/article/create"
                            class="inline-block bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-md text-sm font-medium transition"
                        >
                            + Create New Article
                        </Link>
                    </div>

                    <!-- Export Form -->
                    <section class="mb-8">
                        <h2 class="text-lg font-semibold mb-3 text-gray-700">
                            Export Articles
                        </h2>
                        <form
                            @submit.prevent="submitExport"
                            class="flex flex-col md:flex-row items-center gap-4"
                        >
                            <div class="flex-1 min-w-[250px]">
                                <label
                                    for="export-fields"
                                    class="block mb-1 font-medium text-gray-600"
                                    >Select columns to export:</label
                                >
                                <select
                                    id="export-fields"
                                    multiple
                                    v-model="selectedFields"
                                    class="w-full rounded-md border border-gray-300 shadow-sm focus:ring-2 focus:ring-green-500 focus:outline-none h-32 p-2"
                                >
                                    <option
                                        v-for="field in allFields"
                                        :key="field"
                                        :value="field"
                                    >
                                        {{ field }}
                                    </option>
                                </select>
                            </div>
                            <button
                                type="submit"
                                class="mt-3 md:mt-0 bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-md transition"
                            >
                                Export Excel
                            </button>
                        </form>
                    </section>

                    <!-- Import Form -->
                    <section>
                        <h2 class="text-lg font-semibold mb-3 text-gray-700">
                            Import Articles
                        </h2>
                        <form
                            @submit.prevent="submitImport"
                            enctype="multipart/form-data"
                            class="flex flex-col md:flex-row items-center gap-4"
                        >
                            <div class="flex-1 min-w-[250px]">
                                <label
                                    for="import-fields"
                                    class="block mb-1 font-medium text-gray-600"
                                    >Select columns to import:</label
                                >
                                <select
                                    id="import-fields"
                                    multiple
                                    v-model="selectedFieldsImport"
                                    class="w-full rounded-md border border-gray-300 shadow-sm focus:ring-2 focus:ring-green-500 focus:outline-none h-32 p-2"
                                >
                                    <option
                                        v-for="field in allFields"
                                        :key="field"
                                        :value="field"
                                    >
                                        {{ field }}
                                    </option>
                                </select>
                            </div>

                            <div class="flex flex-col flex-1 min-w-[250px]">
                                <label
                                    for="import-file"
                                    class="block mb-1 font-medium text-gray-600"
                                    >Select Excel file:</label
                                >
                                <input
                                    id="import-file"
                                    type="file"
                                    accept=".xlsx,.xls"
                                    @change="handleFile"
                                    class="border border-gray-300 rounded-md p-2 cursor-pointer focus:outline-none focus:ring-2 focus:ring-green-500"
                                />
                            </div>

                            <button
                                type="submit"
                                class="mt-3 md:mt-0 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-md transition"
                                :disabled="
                                    !importFile ||
                                    selectedFieldsImport.length === 0
                                "
                            >
                                Import Excel
                            </button>
                        </form>
                    </section>

                    <!-- Data Table -->
                    <div class="mt-8">
                        <DataTable
                            :data="article"
                            :columns="articleColumns"
                            filter-key="nama_lengkap"
                            filter-placeholder="Filter berdasarkan nama..."
                            :show-expand="false"
                            :pagination="true"
                        />
                    </div>
                </div>
            </main>
        </SidebarInset>
    </SidebarProvider>
</template>
