<script setup lang="ts">
import type { LucideIcon } from "lucide-vue-next";
import { ChevronRight } from "lucide-vue-next";

import { Head, Link, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

import { useIcon } from "@/lib/useIcon";

import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from "@/components/ui/collapsible";

import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuAction,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from "@/components/ui/sidebar";

defineProps<{
    items: {
        title: string;
        url: string;
        icon?: LucideIcon;
        isActive?: boolean;
        items?: {
            title: string;
            url: string;
        }[];
    }[];
}>();

const page = usePage();

function isItemOpen(item: any) {
    if (!item || !item.items) return false;
    try {
        return item.items.some(
            (sub: any) => String(page.url).indexOf("/" + sub.url) === 0
        );
    } catch (e) {
        return false;
    }
}
</script>

<template>
    <SidebarGroup>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <div v-if="item.items && item.items.length">
                    <Collapsible :default-open="isItemOpen(item)">
                        <SidebarMenuItem>
                            <CollapsibleTrigger as-child>
                                <SidebarMenuButton :tooltip="item.title">
                                    <component
                                        :is="useIcon(item.icon)"
                                        v-if="item.icon"
                                    />
                                    <span>{{ item.title }}</span>
                                    <ChevronRight
                                        class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                                    />
                                </SidebarMenuButton>
                            </CollapsibleTrigger>
                            <CollapsibleContent>
                                <SidebarMenuSub>
                                    <SidebarMenuSubItem
                                        v-for="subItem in item.items"
                                        :key="subItem.title"
                                    >
                                        <SidebarMenuSubButton as-child>
                                            <Link
                                                :href="subItem.url"
                                                :class="{
                                                    active:
                                                        $page.url.indexOf(
                                                            `${
                                                                '/' +
                                                                subItem.url
                                                            }`
                                                        ) === 0,
                                                }"
                                            >
                                                <component
                                                    :is="useIcon(subItem.icon)"
                                                />
                                                <span>{{ subItem.title }}</span>
                                            </Link>
                                        </SidebarMenuSubButton>
                                    </SidebarMenuSubItem>
                                </SidebarMenuSub>
                            </CollapsibleContent>
                        </SidebarMenuItem>
                    </Collapsible>
                </div>
                <div v-else>
                    <SidebarMenuButton as-child>
                        <Link
                            :href="item.url"
                            :class="{
                                active:
                                    $page.url.indexOf(`${'/' + item.url}`) ===
                                    0,
                            }"
                        >
                            <component :is="useIcon(item.icon)" />
                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>
                </div>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>

<style scoped>
.active {
    background-color: oklch(21% 0.034 264.665);
    color: oklch(98.5% 0.002 247.839);
}
</style>
