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
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { useForm as useVeeForm } from "vee-validate";
import { toTypedSchema } from "@vee-validate/zod";
import * as z from "zod";
import { Toaster } from "@/components/ui/toast";
import { useToast } from "@/components/ui/toast/use-toast";

const { toast } = useToast();

const props = defineProps({
    roles: Array,
});

const formSchema = toTypedSchema(
    z
        .object({
            username: z
                .string()
                .min(3, "Username minimal 3 karakter")
                .regex(/^\S*$/, "Username tidak boleh mengandung spasi"),
            email: z.string().email("Email tidak valid"),
            password: z.string().min(6, "Password minimal 6 karakter"),
            password_confirmation: z
                .string()
                .min(6, "Konfirmasi password harus diisi"),
            role: z.string().min(1, "Role harus dipilih"),
        })
        .refine((data) => data.password === data.password_confirmation, {
            message: "Password dan konfirmasi password tidak sama",
            path: ["password_confirmation"],
        })
);

const { handleSubmit, values, resetForm } = useVeeForm({
    validationSchema: formSchema,
    initialValues: {
        username: "",
        email: "",
        password: "",
        password_confirmation: "",
        role: "",
    },
});

const onSubmit = handleSubmit((formValues) => {
    router.post(
        "/user-account",
        {
            username: formValues.username,
            email: formValues.email,
            password: formValues.password,
            password_confirmation: formValues.password_confirmation,
            role: formValues.role,
        },
        {
            onSuccess: () => {
                resetForm();
                toast({
                    title: "Berhasil",
                    description: "Akun user berhasil dibuat.",
                });
                router.visit("/user-account");
            },
            onError: (errors) => {
                toast({
                    title: "Gagal membuat akun",
                    description:
                        "Terjadi kesalahan saat menyimpan data. Email telah terdaftar.",
                    variant: "destructive",
                });
            },
        }
    );
});
</script>

<template>
    <Head title="Create User Account" />
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
                            <BreadcrumbPage>Create User Account</BreadcrumbPage>
                        </BreadcrumbItem>
                    </BreadcrumbList>
                </Breadcrumb>
            </header>

            <div class="p-4">
                <div class="bg-white border rounded-xl p-6 max-w-full mx-auto">
                    <div class="flex flex-col items-start mb-4">
                        <h1 class="text-xl font-semibold">
                            Create User Account
                        </h1>
                        <p>
                            Form ini digunakan untuk membuat user account baru
                        </p>
                    </div>

                    <form @submit.prevent="onSubmit" class="space-y-6">
                        <!-- Username -->
                        <FormField name="username" v-slot="{ componentField }">
                            <FormItem>
                                <FormLabel>Username</FormLabel>
                                <FormControl>
                                    <Input
                                        v-bind="componentField"
                                        placeholder="Masukkan username"
                                        autocomplete="name"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <!-- Email -->
                        <FormField name="email" v-slot="{ componentField }">
                            <FormItem>
                                <FormLabel>Email</FormLabel>
                                <FormControl>
                                    <Input
                                        type="email"
                                        v-bind="componentField"
                                        placeholder="Masukkan email"
                                        autocomplete="email"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <!-- Password -->
                        <FormField name="password" v-slot="{ componentField }">
                            <FormItem>
                                <FormLabel>Password</FormLabel>
                                <FormControl>
                                    <Input
                                        type="password"
                                        v-bind="componentField"
                                        placeholder="Masukkan password"
                                        autocomplete="new-password"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <!-- Password Confirmation -->
                        <FormField
                            name="password_confirmation"
                            v-slot="{ componentField }"
                        >
                            <FormItem>
                                <FormLabel>Konfirmasi Password</FormLabel>
                                <FormControl>
                                    <Input
                                        type="password"
                                        v-bind="componentField"
                                        placeholder="Konfirmasi password"
                                        autocomplete="new-password"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <FormField name="role" v-slot="{ componentField }">
                            <FormItem>
                                <FormLabel>Role User</FormLabel>

                                <FormControl>
                                    <Select v-bind="componentField">
                                        <SelectTrigger>
                                            <SelectValue
                                                placeholder="Pilih role user"
                                            />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectGroup>
                                                <SelectItem
                                                    v-for="role in roles"
                                                    :key="
                                                        role.id ??
                                                        role.value ??
                                                        role.name
                                                    "
                                                    :value="
                                                        role.value ??
                                                        role.name ??
                                                        role.id
                                                    "
                                                >
                                                    {{
                                                        role.name ??
                                                        role.label ??
                                                        role.value
                                                    }}
                                                </SelectItem>
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <div class="flex justify-between items-center">
                            <Link
                                href="/user-account"
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
        <Toaster />
    </SidebarProvider>
</template>
