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
import { Head, usePage, Link, router } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import {
    FormField,
    FormControl,
    FormLabel,
    FormItem,
    FormMessage,
} from "@/components/ui/form";
import { useForm as useVeeForm } from "vee-validate";
import { toTypedSchema } from "@vee-validate/zod";
import * as z from "zod";
import { ref } from "vue";

const formSchema = toTypedSchema(
    z.object({
        nama_lengkap: z.string().min(3, "Minimal 3 karakter"),
        keahlianInput: z.string().optional(),
        keahlian: z.array(z.string()).min(1, "Minimal 1 keahlian wajib diisi"),
    })
);

const { handleSubmit, values, resetForm, setFieldValue } = useVeeForm({
    validationSchema: formSchema,
    initialValues: {
        nama_lengkap: "",
        keahlianInput: "",
        keahlian: [],
    },
});

function addKeahlian() {
    const input = values.keahlianInput?.trim();
    if (input && !values.keahlian.includes(input)) {
        setFieldValue("keahlian", [...values.keahlian, input]);
        setFieldValue("keahlianInput", "");
    }
}

function removeKeahlian(index) {
    const updated = [...values.keahlian];
    updated.splice(index, 1);
    setFieldValue("keahlian", updated);
}

const onSubmit = handleSubmit((formValues) => {
    router.post(
        "/dosen",
        {
            nama_lengkap: formValues.nama_lengkap,
            keahlian: formValues.keahlian,
        },
        {
            onSuccess: () => {
                resetForm();
                router.visit("/dosen");
            },
        }
    );
});
</script>

<template>
    <Head title="Create Dosen" />
    <SidebarProvider>
        <AppSidebar />
        <SidebarInset>
            <header class="flex h-16 shrink-0 items-center gap-2 px-4">
                <SidebarTrigger />
                <Separator orientation="vertical" class="h-4 mx-2" />
                <Breadcrumb>
                    <BreadcrumbList>
                        <BreadcrumbItem>
                            <BreadcrumbLink href="/dashboard">
                                {{ $page.props.auth.user.username }}
                            </BreadcrumbLink>
                        </BreadcrumbItem>
                        <BreadcrumbSeparator />
                        <BreadcrumbItem>
                            <BreadcrumbPage>Create Dosen</BreadcrumbPage>
                        </BreadcrumbItem>
                    </BreadcrumbList>
                </Breadcrumb>
            </header>

            <div class="p-4">
                <div class="bg-white border rounded-xl p-6 max-w-full mx-auto">
                    <div class="flex flex-col items-start mb-4">
                        <h1 class="text-xl font-semibold">Create Dosen</h1>
                        <p>Form ini digunakan untuk membuat data dosen baru</p>
                    </div>

                    <form @submit.prevent="onSubmit" class="space-y-6">
                        <FormField
                            name="nama_lengkap"
                            v-slot="{ componentField }"
                        >
                            <FormItem>
                                <FormLabel>Nama Lengkap</FormLabel>
                                <FormControl>
                                    <Input
                                        v-bind="componentField"
                                        placeholder="Masukkan nama lengkap"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <!-- Input Tambah Keahlian -->
                        <FormField
                            name="keahlianInput"
                            v-slot="{ componentField }"
                        >
                            <FormItem>
                                <FormLabel>Tambah Keahlian</FormLabel>
                                <div class="flex gap-2">
                                    <FormControl>
                                        <Input
                                            v-bind="componentField"
                                            placeholder="Contoh: Web Development"
                                            @keyup.enter.prevent="addKeahlian"
                                        />
                                    </FormControl>
                                    <Button type="button" @click="addKeahlian"
                                        >Tambah</Button
                                    >
                                </div>
                            </FormItem>
                        </FormField>

                        <!-- Daftar Keahlian -->
                        <FormField name="keahlian" v-slot="{ value }">
                            <FormItem>
                                <FormLabel>Daftar Keahlian</FormLabel>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="(k, index) in value"
                                        :key="index"
                                        class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm flex items-center gap-2"
                                    >
                                        {{ k }}
                                        <button
                                            type="button"
                                            @click="removeKeahlian(index)"
                                            class="text-red-600 hover:text-red-800"
                                        >
                                            &times;
                                        </button>
                                    </span>
                                </div>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <div class="flex justify-between items-center">
                            <Link
                                href="/dosen"
                                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm"
                            >
                                Back
                            </Link>
                            <Button
                                type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white"
                            >
                                Submit
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
