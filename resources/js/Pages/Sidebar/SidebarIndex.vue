<script setup>
import LayoutDashboard from "@/Layouts/LayoutDashboard.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import { ref, watch } from "vue";
import SidebarSortItem from "./SidebarSortItem.vue";
import { Button } from "@/components/ui/button";
import InputError from "@/Components/InputError.vue";
import { useIcon } from "@/lib/useIcon";
import iconPicker from "../Parts/iconPicker.vue";

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

//modal
const showModal = ref(false);
const disableUrl = ref(false);
const modalIcons = ref(false);

const openEditModal = (item) => {
    // Opsi 1: Lebih eksplisit
    useFormSidebar.reset();

    // 2. Isi semua properti form, termasuk ID
    useFormSidebar.id = item.id;
    useFormSidebar.title = item.title;
    useFormSidebar.url = item.url;
    useFormSidebar.icon = item.icon;

    item.items.length > 0
        ? (disableUrl.value = true)
        : (disableUrl.value = false);

    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const openIconPicker = () => {
    modalIcons.value = true;
};

const closeIconPicker = () => {
    modalIcons.value = false;
};

const updateIcons = (iconName) => {
    useFormSidebar.icon = iconName;
    modalIcons.value = false;
};

// simpan item sidebar
const useFormSidebar = useForm({
    id: null,
    title: "",
    url: "",
    icon: "",
});

const saveItemSidebar = () => {
    useFormSidebar.put(`/sidebar/${useFormSidebar.id}`, {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};

const onDragEnd = () => {
    // console.log("Reordered Sidebars:", localSidebars.value);
    router.post(
        "/sidebar/reorder",
        {
            sidebars: localSidebars.value,
        },
        {
            preserveScroll: true,
        }
    );
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
                    <SidebarSortItem
                        v-model="localSidebars"
                        @end="onDragEnd"
                        @edit="openEditModal"
                    />
                </div>
            </div>
        </div>

        <!-- modal form -->
        <Modal :show="showModal" @close="closeModal" maxWidth="lg">
            <form @submit.prevent="saveItemSidebar">
                <div class="p-6">
                    <h2 class="text-lg font-bold mb-4">Edit Sidebar Item</h2>

                    <div class="mb-4">
                        <label class="block text-sm text-gray-600">Nama</label>
                        <input
                            v-model="useFormSidebar.title"
                            type="text"
                            class="w-full border rounded p-2"
                        />
                        <InputError
                            class="mt-2"
                            :message="useFormSidebar.errors.title"
                        />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm text-gray-600">URL</label>
                        <input
                            v-model="useFormSidebar.url"
                            :disabled="disableUrl"
                            type="text"
                            class="w-full border rounded p-2"
                            :class="{
                                'bg-gray-300 cursor-not-allowed': disableUrl,
                            }"
                        />
                        <InputError
                            class="mt-2"
                            :message="useFormSidebar.errors.url"
                        />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm text-gray-600">Icon</label>
                        <div
                            class="w-full bg-gray-50 border border-gray-400 p-2 rounded flex items-center justify-between"
                        >
                            <component
                                :is="useIcon(useFormSidebar.icon)"
                                class="w-6 h-6 mb-2"
                            />
                            <input
                                v-model="useFormSidebar.icon"
                                type="text"
                                class="hidden"
                            />
                            <Button type="button" @click="openIconPicker"
                                >Change</Button
                            >
                        </div>
                        <InputError
                            class="mt-2"
                            :message="useFormSidebar.errors.icon"
                        />
                    </div>
                    <div class="pt-4">
                        <Button
                            type="submit"
                            :disabled="useFormSidebar.processing"
                            class="w-full"
                        >
                            Save Changes
                        </Button>
                    </div>
                </div>
            </form>
        </Modal>

        <!-- modal icons picker -->
        <Modal :show="modalIcons" @close="closeIconPicker" maxWidth="xl">
            <icon-picker @select="updateIcons" />
        </Modal>
    </LayoutDashboard>
</template>
