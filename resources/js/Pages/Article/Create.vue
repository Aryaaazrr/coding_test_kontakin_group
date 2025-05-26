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
import {
    Select,
    SelectTrigger,
    SelectValue,
    SelectContent,
    SelectGroup,
    SelectItem,
} from "@/components/ui/select";
import { useForm as useVeeForm } from "vee-validate";
import { toTypedSchema } from "@vee-validate/zod";
import * as z from "zod";
import { ref } from "vue";

const page = usePage();
const mahasiswaOptions = page.props.mahasiswa || [];
const dosenOptions = page.props.dosen || [];

const fileError = ref("");

const formSchema = toTypedSchema(
    z.object({
        id_mahasiswa: z.string().uuid("Pilih mahasiswa yang valid"),
        id_dosen: z.string().uuid("Pilih dosen yang valid"),
        judul: z.string().min(3, "Judul minimal 3 karakter"),
        tipe: z.enum(["Laporan PKL", "Skripsi"]),
        file: z
            .any()
            .refine((files) => files?.length === 1, "File wajib diupload")
            .refine(
                (files) => files && files[0]?.type === "application/pdf",
                "File harus berformat PDF"
            )
            .refine((files) => {
                if (!files || files.length === 0) return false;
                const sizeKB = files[0].size / 1024;
                return sizeKB >= 100 && sizeKB <= 500;
            }, "Ukuran file harus antara 100 KB sampai 500 KB"),
    })
);

const { handleSubmit, values, resetForm, setFieldValue, errors } = useVeeForm({
    validationSchema: formSchema,
    initialValues: {
        id_mahasiswa: "",
        id_dosen: "",
        judul: "",
        tipe: "Laporan PKL",
        file: null,
    },
});

function onFileChange(event) {
    const selectedFile = event.target.files[0];
    if (!selectedFile) {
        fileError.value = "";
        setFieldValue("file", null);
        return;
    }

    if (selectedFile.type !== "application/pdf") {
        fileError.value = "File harus berformat PDF";
        setFieldValue("file", null);
        event.target.value = null;
        return;
    }

    const fileSizeKB = selectedFile.size / 1024;
    if (fileSizeKB < 100 || fileSizeKB > 500) {
        fileError.value = "Ukuran file harus antara 100 KB sampai 500 KB";
        setFieldValue("file", null);
        event.target.value = null;
        return;
    }

    fileError.value = "";
    setFieldValue("file", event.target.files);
}

const onSubmit = handleSubmit((formValues) => {
    if (fileError.value) {
        return;
    }

    const formData = new FormData();
    formData.append("id_mahasiswa", formValues.id_mahasiswa);
    formData.append("id_dosen", formValues.id_dosen);
    formData.append("judul", formValues.judul);
    formData.append("tipe", formValues.tipe);
    formData.append("file", formValues.file[0]);

    router.post("/article", formData, {
        headers: { "Content-Type": "multipart/form-data" },
        onSuccess: () => {
            resetForm();
            fileError.value = "";
            router.visit("/article");
        },
    });
});
</script>

<template>
    <Head title="Create Article" />
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
                            <BreadcrumbPage>Create Article</BreadcrumbPage>
                        </BreadcrumbItem>
                    </BreadcrumbList>
                </Breadcrumb>
            </header>

            <div class="p-4">
                <div class="bg-white border rounded-xl p-6 max-w-full mx-auto">
                    <div class="flex flex-col items-start mb-4">
                        <h1 class="text-xl font-semibold">Create Article</h1>
                        <p>
                            Form ini digunakan untuk menambahkan data artikel
                            baru
                        </p>
                    </div>

                    <form
                        @submit.prevent="onSubmit"
                        class="space-y-6"
                        enctype="multipart/form-data"
                    >
                        <FormField
                            name="id_mahasiswa"
                            v-slot="{ componentField }"
                        >
                            <FormItem>
                                <FormLabel>Mahasiswa</FormLabel>
                                <FormControl>
                                    <Select
                                        v-bind="componentField"
                                        defaultValue=""
                                    >
                                        <SelectTrigger>
                                            <SelectValue
                                                placeholder="Pilih Mahasiswa"
                                            />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectGroup>
                                                <SelectItem
                                                    v-for="m in mahasiswaOptions"
                                                    :key="m.id_mahasiswa"
                                                    :value="m.id_mahasiswa"
                                                >
                                                    {{ m.nama_lengkap }}
                                                </SelectItem>
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <FormField name="id_dosen" v-slot="{ componentField }">
                            <FormItem>
                                <FormLabel>Dosen</FormLabel>
                                <FormControl>
                                    <Select
                                        v-bind="componentField"
                                        defaultValue=""
                                    >
                                        <SelectTrigger>
                                            <SelectValue
                                                placeholder="Pilih Dosen"
                                            />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectGroup>
                                                <SelectItem
                                                    v-for="d in dosenOptions"
                                                    :key="d.id_dosen"
                                                    :value="d.id_dosen"
                                                >
                                                    {{ d.nama_lengkap }}
                                                </SelectItem>
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <FormField name="judul" v-slot="{ componentField }">
                            <FormItem>
                                <FormLabel>Judul</FormLabel>
                                <FormControl>
                                    <Input
                                        v-bind="componentField"
                                        type="text"
                                        placeholder="Masukkan judul artikel"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <FormField name="tipe" v-slot="{ componentField }">
                            <FormItem>
                                <FormLabel>Tipe Artikel</FormLabel>
                                <FormControl>
                                    <Select
                                        v-bind="componentField"
                                        defaultValue="Laporan PKL"
                                    >
                                        <SelectTrigger>
                                            <SelectValue
                                                placeholder="Pilih tipe artikel"
                                            />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectGroup>
                                                <SelectItem value="Laporan PKL"
                                                    >Laporan PKL</SelectItem
                                                >
                                                <SelectItem value="Skripsi"
                                                    >Skripsi</SelectItem
                                                >
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <FormField name="file">
                            <FormItem>
                                <FormLabel
                                    >Upload File (PDF, 100-500 KB)</FormLabel
                                >
                                <FormControl>
                                    <Input
                                        id="file"
                                        type="file"
                                        accept="application/pdf"
                                        @change="onFileChange"
                                    />
                                </FormControl>
                                
                            </FormItem>
                        </FormField>

                        <div class="flex justify-between items-center">
                            <Link
                                href="/article"
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
