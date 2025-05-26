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
import {
    SidebarProvider,
    SidebarTrigger,
    SidebarInset,
} from "@/components/ui/sidebar";
import { Head, Link, router } from "@inertiajs/vue3";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import {
    FormField,
    FormItem,
    FormLabel,
    FormControl,
    FormMessage,
} from "@/components/ui/form";
import { useForm as useVeeForm } from "vee-validate";
import { toTypedSchema } from "@vee-validate/zod";
import * as z from "zod";
import { ref } from "vue";

const props = defineProps({
    dosen: Object,
});

const formSchema = toTypedSchema(
    z.object({
        nama_lengkap: z.string().min(2, "Minimal 2 karakter").max(100),
        keahlian: z.array(z.string()).min(1, "Minimal 1 bidang keahlian"),
    })
);

const { handleSubmit, values, setValues } = useVeeForm({
    validationSchema: formSchema,
    initialValues: {
        nama_lengkap: props.dosen.nama_lengkap,
        keahlian: props.dosen.keahlian,
    },
});

const newKeahlian = ref("");

const addKeahlian = () => {
    if (newKeahlian.value && !values.keahlian.includes(newKeahlian.value)) {
        setValues({
            ...values,
            keahlian: [...values.keahlian, newKeahlian.value],
        });
        newKeahlian.value = "";
    }
};

const removeKeahlian = (item) => {
    setValues({
        ...values,
        keahlian: values.keahlian.filter((k) => k !== item),
    });
};

const onSubmit = handleSubmit((formValues) => {
    router.put(`/dosen/${props.dosen.id_dosen}`, formValues, {
        onSuccess: () => {
            router.visit("/dosen");
        },
        onError: (err) => {
            console.error("Update error", err);
        },
    });
});
</script>

<template>
    <Head title="Edit Dosen" />
    <SidebarProvider>
        <AppSidebar />
        <SidebarInset>
            <header class="flex h-16 items-center gap-2 px-4">
                <SidebarTrigger />
                <Breadcrumb>
                    <BreadcrumbList>
                        <BreadcrumbItem>
                            <BreadcrumbLink href="/dashboard">{{
                                $page.props.auth.user.username
                            }}</BreadcrumbLink>
                        </BreadcrumbItem>
                        <BreadcrumbSeparator />
                        <BreadcrumbItem>
                            <BreadcrumbPage>Edit Dosen</BreadcrumbPage>
                        </BreadcrumbItem>
                    </BreadcrumbList>
                </Breadcrumb>
            </header>

            <div class="p-4">
                <div class="bg-white border rounded-xl p-6 max-w-full mx-auto">
                    <h1 class="text-xl font-semibold mb-4">Edit Dosen</h1>

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

                        <FormField name="keahlian">
                            <FormItem>
                                <FormLabel>Bidang Keahlian</FormLabel>
                                <div class="flex gap-2 mb-2">
                                    <Input
                                        v-model="newKeahlian"
                                        placeholder="Tambah keahlian..."
                                    />
                                    <Button
                                        type="button"
                                        @click="addKeahlian"
                                        class="bg-blue-600 text-white"
                                        >Tambah</Button
                                    >
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="(item, index) in values.keahlian"
                                        :key="index"
                                        class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm flex items-center"
                                    >
                                        {{ item }}
                                        <button
                                            type="button"
                                            @click="removeKeahlian(item)"
                                            class="ml-2 text-red-500"
                                        >
                                            &times;
                                        </button>
                                    </span>
                                </div>

                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <div class="flex justify-between">
                            <Link
                                href="/dashboard"
                                class="bg-gray-600 text-white px-4 py-2 rounded"
                                >Kembali</Link
                            >
                            <Button
                                type="submit"
                                class="bg-green-600 text-white"
                                >Simpan Perubahan</Button
                            >
                        </div>
                    </form>
                </div>
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
