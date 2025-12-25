<script setup>
import LayoutDashboard from "@/Layouts/LayoutDashboard.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import { ref, watch } from "vue";
import SidebarSortItem from "./SidebarSortItem.vue";
import { Button } from "@/components/ui/button";
import ModalEditMenu from "./ModalEditMenu.vue";
import ModalNewMenu from "./ModalNewMenu.vue";

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
        onSuccess: () => {
            closeModal(), useFormSidebar.reset();
        },
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

//modal new menu
const showModalNewMenu = ref(false);

const openModalNewMenu = () => {
    useFormSidebar.reset();
    showModalNewMenu.value = true;
};
const closeModalNewMenu = () => {
    useFormSidebar.reset();
    showModalNewMenu.value = false;
};

const saveNewMenu = () => {
    useFormSidebar.post(`/sidebar/new`, {
        preserveScroll: true,
        onSuccess: () => {
            closeModalNewMenu(), useFormSidebar.reset();
        },
    });
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
                    <div class="py-4 flex items-center justify-between">
                        <span>Sidebar Page</span>
                        <Button type="button" @click="openModalNewMenu"
                            >Add New Menu</Button
                        >
                    </div>

                    <!-- Gunakan state lokal, bukan props.sidebars -->
                    <SidebarSortItem
                        v-model="localSidebars"
                        @end="onDragEnd"
                        @edit="openEditModal"
                    />
                </div>
            </div>
        </div>

        <!-- modal form edits -->
        <Modal :show="showModal" @close="closeModal" maxWidth="lg">
            <ModalEditMenu
                @childSubmit="saveItemSidebar"
                :disableUrl="disableUrl"
                @close-modal="closeModal"
                :useFormSidebar="useFormSidebar"
            />
        </Modal>

        <!-- modal form new menu -->
        <Modal
            :show="showModalNewMenu"
            @close="closeModalNewMenu"
            maxWidth="lg"
        >
            <ModalNewMenu
                @childSubmitNew="saveNewMenu"
                @close-modal-new="closeModalNewMenu"
                :useFormSidebar="useFormSidebar"
            />
        </Modal>
    </LayoutDashboard>
</template>
