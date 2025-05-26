<script setup>
import {
    AudioWaveform,
    BookOpen,
    Bot,
    Command,
    Frame,
    GalleryVerticalEnd,
    Map,
    PieChart,
    Settings2,
    SquareTerminal,
} from "lucide-vue-next";
import NavMain from "@/components/NavMain.vue";
import NavProjects from "@/components/NavProjects.vue";
import NavUser from "@/components/NavUser.vue";
import TeamSwitcher from "@/components/TeamSwitcher.vue";

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarRail,
} from "@/components/ui/sidebar";

const props = defineProps({
    side: { type: String, required: false },
    variant: { type: String, required: false },
    collapsible: { type: String, required: false, default: "icon" },
    class: { type: null, required: false },
});

// This is sample data.
const data = {
    // user: {
    //     name: "shadcn",
    //     email: "m@example.com",
    //     avatar: "/avatars/shadcn.jpg",
    // },
    teams: [
        {
            name: "CodingTest",
            logo: GalleryVerticalEnd,
            plan: "Web Developer",
        },
        {
            name: "Acme Corp.",
            logo: AudioWaveform,
            plan: "Startup",
        },
        {
            name: "Evil Corp.",
            logo: Command,
            plan: "Free",
        },
    ],
    navMain: [
        {
            title: "Dashborad",
            url: "#",
            icon: PieChart,
            isActive: false,
            items: [
                {
                    title: "Role management",
                    url: "dashboard",
                }
            ],
        },
        {
            title: "Master",
            url: "#",
            icon: Settings2,
            isActive: false,
            items: [
                {
                    title: "Dosen",
                    url: "dosen",
                },
                {
                    title: "Article",
                    url: "article",
                },
                {
                    title: "History",
                    url: "history",
                },
                {
                    title: "User Account",
                    url: "user-account",
                },
            ],
        },
    ],
};
</script>

<template>
    <Sidebar v-bind="props">
        <SidebarHeader>
            <TeamSwitcher :teams="data.teams" />
        </SidebarHeader>
        <SidebarContent>
            <NavMain :items="data.navMain" />
            <!-- <NavProjects :projects="data.projects" /> -->
        </SidebarContent>
        <SidebarFooter>
            <NavUser :user="data.user" />
        </SidebarFooter>
        <SidebarRail />
    </Sidebar>
</template>
