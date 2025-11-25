<script setup>
import LayoutDashboard from "@/Layouts/LayoutDashboard.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import SidebarSortItem from "./SidebarSortItem.vue";

const props = defineProps({
    sidebars: Array,
});

// Buat local state
const localSidebars = ref([...props.sidebars]);

// Sync ketika props berubah
watch(
    () => props.sidebars,
    (val) => {
        localSidebars.value = [...val];
    }
);

const onDragEnd = () => {
    // console.log("Reordered Sidebars:", localSidebars.value);
    // router.post(
    //     "/sidebar/reorder",
    //     {
    //         sidebars: localSidebars.value,
    //     },
    //     {
    //         preserveScroll: true,
    //     }
    // );
};
</script>

<template>
    <Head title="Sidebar" />

    <LayoutDashboard>
        <div class="py-1 font-normal text-foreground">
            <div
                class="mx-auto max-w-7xl sm:px-6 lg:px-8 border-2 border-dashed border-gray-200 rounded-lg"
            >
                <div class="overflow-hidden bg-white">
                    <div class="py-4">Sidebar Page</div>

                    <!-- Gunakan state lokal, bukan props.sidebars -->
                    <SidebarSortItem v-model="localSidebars" @end="onDragEnd" />
                </div>
            </div>
        </div>
    </LayoutDashboard>
</template>
