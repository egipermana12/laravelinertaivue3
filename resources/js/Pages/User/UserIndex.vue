<script setup>
import LayoutDashboard from "@/Layouts/LayoutDashboard.vue";
import { Head } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import Pagination from "@/Components/Pagination.vue";
import UserTable from "./UserTable.vue";
import { useSwal } from "@/lib/useSwal";

const { confirm } = useSwal();

const props = defineProps({
    users: Object,
});

// select all checkbox logic
const selected = ref([]);

// semua id pada halaman sekarang
const pageUserIds = computed(() => props.users.data.map((user) => user.id));
const allSelected = computed(
    () =>
        selected.value.length === pageUserIds.value.length &&
        pageUserIds.value.length > 0
);

//select all checkbox di klik
function toggleSelectAll() {
    if (allSelected.value) {
        selected.value = [];
    } else {
        selected.value = [...pageUserIds.value];
    }
}

//selecr single row
function toggleSelectSingle(id) {
    if (selected.value.includes(id)) {
        selected.value = selected.value.filter(
            (selectedId) => selectedId !== id
        );
    } else {
        selected.value.push(id);
    }
}

// bulk action logic bisa ditambahkan disini
const bulkDelete = async () => {
    if (selected.value.length === 0) {
        return;
    }
    const confirmed = await confirm(
        `Hapus ${selected.value.length} user terpilih?`,
        {
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus",
            cancelButtonText: "Batal",
            confirmButtonColor: "#dc2626", // merah
            cancelButtonColor: "#6b7280", // abu-abu
        }
    );

    if (!confirmed) {
        return; // user klik batal
    }
    // lakukan aksi penghapusan disini
    console.log("Menghapus user dengan ID:", confirmed);
};
</script>

<template>
    <Head title="Users" />
    <LayoutDashboard>
        <div class="py-1 font-normal text-foreground">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-4">
                <div class="overflow-hidden bg-white">
                    <div class="py-4 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-xl">List Of Users</h3>
                            <p class="text-sm text-gray-600">
                                Manage users account here
                            </p>
                        </div>
                        <div>
                            <span v-if="selected.length === 0">
                                <button
                                    class="px-4 py-2 border border-gray-500 rounded-sm text-sm hover:bg-gray-100 font-bold"
                                >
                                    Add New User
                                </button>
                            </span>
                            <span v-else>
                                <button
                                    class="px-4 py-2 border border-red-500 bg-red-500 text-white rounded-sm text-sm hover:bg-red-800 font-bold"
                                    @click="bulkDelete"
                                >
                                    Delete User
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="overflow-hidden rounded-lg border mt-6">
                        <div class="relative w-full overflow-auto">
                            <UserTable
                                :users="props.users"
                                v-model:selected="selected"
                                :allSelected="allSelected"
                                @toggle-select-all="toggleSelectAll"
                                @toggle-select-single="toggleSelectSingle"
                            />
                        </div>
                    </div>
                    <div class="mt-1 flex justify-between items-center">
                        <div></div>
                        <Pagination :links="props.users.links" />
                    </div>
                </div>
            </div>
        </div>
    </LayoutDashboard>
</template>
