<script setup lang="ts">
import type { SidebarProps } from '@/components/ui/sidebar'


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
} from "lucide-vue-next"

import NavUser from '@/components/NavUser.vue'
import TeamSwitcher from '@/components/TeamSwitcher.vue'
import NavMainCustom from '@/components/NavMainCustom.vue'

import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarHeader,
  SidebarRail,
} from '@/components/ui/sidebar'


import { ChevronRight } from "lucide-vue-next"


const props = withDefaults(defineProps<SidebarProps>(), {
  collapsible: "icon",
  variant: "sidebar"
})

import { computed,ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const user = computed(() => page.props.auth.user)


{/*sidebara data*/}
import {sidebarData} from '@/constans/sidebarData.ts'
const data = sidebarData;

const useravatar = {...user.value, avatar: "https://www.shadcn-vue.com/avatars/shadcn.jpg",}

const mergedData = computed(() => ({
  ...data,
  user2: useravatar, // menambahkan user dari inertia sebagai user2
}))


</script>

<template>
  <Sidebar v-bind="props">
    <SidebarHeader>
      <TeamSwitcher :teams="mergedData.teams" />
    </SidebarHeader>
    <SidebarContent>
        <NavMainCustom :items="mergedData.navMain"/>
    </SidebarContent>
    <SidebarFooter>
      <NavUser :user="mergedData.user2" />
    </SidebarFooter>
    <SidebarRail />
  </Sidebar>
</template>
