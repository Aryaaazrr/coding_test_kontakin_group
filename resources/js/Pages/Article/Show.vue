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
import { Badge } from "@/components/ui/badge";

const props = defineProps({
    role: Object,
});
</script>

<template>
    <Head title="Detail Role" />
    <SidebarProvider>
        <AppSidebar />
        <SidebarInset>
            <header class="flex h-16 shrink-0 items-center gap-2 px-4">
                <SidebarTrigger />
                <Separator orientation="vertical" class="h-4 mx-2" />
                <Breadcrumb>
                    <BreadcrumbList>
                        <BreadcrumbItem>
                            <BreadcrumbLink href="#">
                                {{ $page.props.auth.user.username }}
                            </BreadcrumbLink>
                        </BreadcrumbItem>
                        <BreadcrumbSeparator />
                        <BreadcrumbItem>
                            <BreadcrumbPage>Detail Role</BreadcrumbPage>
                        </BreadcrumbItem>
                    </BreadcrumbList>
                </Breadcrumb>
            </header>

            <div class="p-4">
                <div class="bg-white border rounded-xl p-6 max-w-full mx-auto">
                    <div class="flex flex-col items-start mb-4">
                        <h1 class="text-xl font-semibold">Detail Role</h1>
                        <p>
                            Informasi lengkap mengenai role dan permission-nya
                        </p>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500 mb-1">Role Name</p>
                        <p class="text-lg font-medium text-gray-800">
                            {{ props.role.name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-1">Permissions</p>
                        <div class="flex flex-wrap gap-2">
                            <Badge
                                v-for="perm in props.role.permissions"
                                :key="perm.id"
                                variant="outline"
                            >
                                {{ perm.name }}
                            </Badge>
                        </div>
                    </div>

                    <div class="mt-6">
                        <Link
                            href="/dashboard"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm"
                        >
                            Back to Dashboard
                        </Link>
                    </div>
                </div>
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
