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
import { h } from "vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Checkbox } from "@/components/ui/checkbox";
import {
    FormField,
    FormControl,
    FormLabel,
    FormItem,
    FormMessage,
    FormDescription,
} from "@/components/ui/form";
import { useForm as useVeeForm } from "vee-validate";
import { toTypedSchema } from "@vee-validate/zod";
import * as z from "zod";

const props = defineProps({
    permissions: Array,
    role: Object,
});

const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(2, "Minimal 2 karakter").max(50),
        permissions: z.array(z.number()).min(1, "Pilih minimal 1 permission."),
    })
);

const { handleSubmit, values, resetForm } = useVeeForm({
    validationSchema: formSchema,
    initialValues: {
        name: props.role?.name || "",
        permissions: props.role?.permissions
            ? props.role.permissions.map((p) => p.id)
            : [],
    },
});

const onSubmit = handleSubmit((formValues) => {
    router.put(`/dashboard/${props.role.id}`, formValues, {
        onSuccess: () => {
            resetForm();
            router.visit("/dashboard");
        },
        onError: (errors) => {
            console.error("Submit error:", errors);
        },
    });
});
</script>

<template>
    <Head title="Create Role" />
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
                            <BreadcrumbPage>Edit Role</BreadcrumbPage>
                        </BreadcrumbItem>
                    </BreadcrumbList>
                </Breadcrumb>
            </header>

            <div class="p-4">
                <div class="bg-white border rounded-xl p-6 max-w-full mx-auto">
                    <div class="flex flex-col items-start mb-4">
                        <h1 class="text-xl font-semibold">Edit Role</h1>
                        <p>Form ini digunakan untuk mengedit role</p>
                    </div>

                    <form @submit.prevent="onSubmit" class="space-y-6">
                        <FormField name="name" v-slot="{ componentField }">
                            <FormItem>
                                <FormLabel>Role Name</FormLabel>
                                <FormControl>
                                    <Input
                                        v-bind="componentField"
                                        placeholder="e.g. Admin, Editor"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <FormField name="permissions">
                            <FormItem>
                                <div class="mb-4">
                                    <FormLabel class="text-base">
                                        Permissions
                                    </FormLabel>
                                </div>

                                <FormField
                                    v-for="permissions in props.permissions"
                                    v-slot="{ value, handleChange }"
                                    :key="permissions.id"
                                    type="checkbox"
                                    :value="permissions.id"
                                    :unchecked-value="false"
                                    name="permissions"
                                >
                                    <FormItem
                                        class="flex flex-row items-start space-x-3 space-y-0"
                                    >
                                        <FormControl>
                                            <Checkbox
                                                :model-value="
                                                    value.includes(
                                                        permissions.id
                                                    )
                                                "
                                                @update:model-value="
                                                    handleChange
                                                "
                                            />
                                        </FormControl>
                                        <FormLabel class="font-normal">
                                            {{ permissions.name }}
                                        </FormLabel>
                                    </FormItem>
                                </FormField>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <div class="flex justify-between items-center">
                            <Link
                                href="/dashboard"
                                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm"
                            >
                                Back to Dashboard
                            </Link>
                            <Button
                                type="submit"
                                class="bg-yellow-600 hover:bg-yellow-700 text-white"
                            >
                                Update
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
