<script setup>
import {
    BadgeCheck,
    Bell,
    ChevronsUpDown,
    CreditCard,
    LogOut,
    Sparkles,
    Settings,
} from "lucide-vue-next";

import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import {
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from "@/components/ui/sidebar";
import DropdownLink from "@/Components/DropdownLink.vue";
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();
const user = computed(() => page.props.auth?.user ?? null);

const initials = computed(() => {
    if (!user.value?.username) return "??";

    const parts = user.value.username.split(/[\s._-]+/);
    return parts
        .map((p) => p[0]?.toUpperCase())
        .join("")
        .slice(0, 2);
});

const { isMobile } = useSidebar();
</script>

<template>
    <SidebarMenu>
        <SidebarMenuItem>
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <SidebarMenuButton
                        size="lg"
                        class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
                    >
                        <Avatar class="h-8 w-8 rounded-lg">
                            <AvatarImage
                                :src="user?.avatar ?? '/default-avatar.jpg'"
                                :alt="user?.username"
                            />
                            <AvatarFallback class="rounded-lg">
                                {{ initials }}
                            </AvatarFallback>
                        </Avatar>
                        <div
                            class="grid flex-1 text-left text-sm leading-tight"
                        >
                            <span class="truncate font-semibold">{{
                                user.username
                            }}</span>
                            <span class="truncate text-xs">{{
                                user.email
                            }}</span>
                        </div>
                        <ChevronsUpDown class="ml-auto size-4" />
                    </SidebarMenuButton>
                </DropdownMenuTrigger>
                <DropdownMenuContent
                    class="w-[--reka-dropdown-menu-trigger-width] min-w-56 rounded-lg"
                    :side="isMobile ? 'bottom' : 'right'"
                    align="end"
                    :side-offset="4"
                >
                    <DropdownMenuLabel class="p-0 font-normal">
                        <div
                            class="flex items-center gap-2 px-1 py-1.5 text-left text-sm"
                        >
                            <Avatar class="h-8 w-8 rounded-lg">
                                <AvatarImage
                                    :src="user?.avatar ?? '/default-avatar.jpg'"
                                    :alt="user?.username"
                                />
                                <AvatarFallback class="rounded-lg">
                                    {{ initials }}
                                </AvatarFallback>
                            </Avatar>
                            <div
                                class="grid flex-1 text-left text-sm leading-tight"
                            >
                                <span class="truncate font-semibold">{{
                                    user.username
                                }}</span>
                                <span class="truncate text-xs">{{
                                    user.email
                                }}</span>
                            </div>
                        </div>
                    </DropdownMenuLabel>
                    <DropdownMenuGroup>
                        <DropdownMenuItem>
                            <Settings />
                            <DropdownLink
                                :href="route('profile.edit')"
                                method="get"
                                as="button"
                            >
                                Account
                            </DropdownLink>
                        </DropdownMenuItem>
                    </DropdownMenuGroup>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem>
                        <LogOut />
                        <DropdownLink
                        :href="route('logout')"
                        method="post"
                        as="button"
                        >
                            Log Out
                        </DropdownLink>
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>
        </SidebarMenuItem>
    </SidebarMenu>
</template>
