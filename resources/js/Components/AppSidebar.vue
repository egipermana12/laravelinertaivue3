<script setup lang="ts">
import { SidebarProps } from "@/components/ui/sidebar";

import NavUser from "@/components/NavUser.vue";
import TeamSwitcher from "@/components/TeamSwitcher.vue";
import NavMainCustom from "@/components/NavMainCustom.vue";

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarRail,
} from "@/components/ui/sidebar";

import { ChevronRight } from "lucide-vue-next";

const props = withDefaults(defineProps<SidebarProps>(), {
    collapsible: "icon",
    variant: "sidebar",
});

import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();

const user = computed(() => page.props.auth.user);
const sidebar = computed(() => page.props.sidebars);

import { sidebarData } from "@/constans/sidebarData.ts";
const data = sidebarData;

// Ini akan otomatis ter-update setiap kali user.value berubah
const useravatar = computed(() => ({
    ...user.value,
    // Akses nilai avatar terbaru
    avatar: user.value.avatar
        ? user.value.avatar
        : "https://www.shadcn-vue.com/avatars/shadcn.jpg",
}));

type SidebarItem = {
    title: string;
    url: string;
    icon?: unknown;
    isActive?: boolean;
    items?: { title: string; url: string }[];
};

const mergedData = computed(() => ({
    ...data,
    sidebars: sidebar.value as SidebarItem[], // menambahkan sidebar dari inertia sebagai sidebars
    user2: useravatar.value, // menambahkan user dari inertia sebagai user2
}));
</script>

<template>
    <Sidebar v-bind="props">
        <SidebarHeader>
            <TeamSwitcher :teams="mergedData.teams" />
        </SidebarHeader>
        <SidebarContent>
            <NavMainCustom :items="mergedData.sidebars" />
        </SidebarContent>
        <SidebarFooter>
            <NavUser :user="mergedData.user2" />
        </SidebarFooter>
        <SidebarRail />
    </Sidebar>
</template>
