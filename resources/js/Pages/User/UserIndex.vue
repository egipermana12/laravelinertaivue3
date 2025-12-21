<script setup>
import LayoutDashboard from "@/Layouts/LayoutDashboard.vue";
import Input from "@/Components/ui/input/Input.vue";
import Select from "@/Components/Select.vue";
import Modal from "@/Components/Modal.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import Pagination from "@/Components/Pagination.vue";
import UserTable from "./UserTable.vue";
import { useSwal } from "@/lib/useSwal";
import UserNewModal from "./UserNewModal.vue";
import UserEditModal from "./UserEditModal.vue";

const { confirm, success } = useSwal();

const props = defineProps({
    users: Object,
    filters: Object,
    roles: Array,
    permissions: Array,
});

//filter
const filtersAll = ref({
    search: props.filters.search ?? "",
    role: props.filters.role ?? "",
});

//aplykan filter
function applyFilters() {
    router.get(route("users.index"), filtersAll.value, {
        preserveState: true,
        replace: true,
    });
}

//reset filter
function resetFilters() {
    filtersAll.value = {
        search: "",
        role: "",
    };
    applyFilters();
}

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

    router.post(
        route("users.bulk-delete"),
        {
            user_ids: selected.value,
        },
        {
            onSuccess: () => {
                selected.value = []; // reset selected setelah sukses
                success("User berhasil dihapus.");
            },
        }
    );
};

//form users
const formUser = useForm({
    id: null,
    name: "",
    username: "",
    email: "",
    password: "",
    role: "staff",
    permissions: [],
});

// edit user
const modalEditUser = ref(false);

const openEditModal = (user) => {
    formUser.reset();
    formUser.id = user.id;
    formUser.name = user.name;
    formUser.username = user.username;
    formUser.email = user.email;
    formUser.role = user.roles[0];
    formUser.permissions = user.permissions;
    modalEditUser.value = true;
};

const closeEditModal = () => {
    formUser.reset();
    modalEditUser.value = false;
};

//new users
const modalNewUser = ref(false);

const openNewUserModal = () => {
    formUser.reset();
    modalNewUser.value = true;
};

const closeNewUserModal = () => {
    formUser.reset();
    modalNewUser.value = false;
};

const saveEdituser = () => {
    formUser.put(route("users.update", formUser.id), {
        preserveScroll: true,
        onSuccess: () => {
            modalEditUser.value = false;
            formUser.reset();
            success("User berhasil diupdate.");
        },
    });
};

//save new users
const saveNewuser = () => {
    formUser.post(route("users.store"), {
        preserveScroll: true,
        onSuccess: () => {
            modalNewUser.value = false;
            formUser.reset();
            success("User berhasil ditambahkan.");
        },
    });
};

// delete user
const confirmDelete = async (userId) => {
    const selectSingleDelete = ref([]);
    selectSingleDelete.value.push(userId);
    if (selectSingleDelete.value.length === 0) {
        return;
    }
    const confirmed = await confirm(`Hapus user ini?`, {
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus",
        cancelButtonText: "Batal",
        confirmButtonColor: "#dc2626", // merah
        cancelButtonColor: "#6b7280", // abu-abu
    });
    if (!confirmed) {
        return; // user klik batal
    }
    router.post(
        route("users.bulk-delete"),
        {
            user_ids: selectSingleDelete.value,
        },
        {
            onSuccess: () => {
                success("User berhasil dihapus.");
            },
        }
    );
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
                            <button
                                v-show="selected.length === 0"
                                class="px-4 py-2 border border-gray-500 rounded-sm text-sm hover:bg-gray-100 font-bold"
                                @click="openNewUserModal"
                            >
                                Add New User
                            </button>

                            <button
                                v-show="selected.length > 0"
                                class="px-4 py-2 border border-red-500 bg-red-500 text-white rounded-sm text-sm hover:bg-red-800 font-bold"
                                @click="bulkDelete"
                            >
                                Delete User
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 mt-4">
                        <Input
                            v-model="filtersAll.search"
                            placeholder="Enter to Search users..."
                            @keyup.enter="applyFilters"
                            class="border-gray-900"
                        />

                        <Select
                            v-model="filtersAll.role"
                            :options="roles"
                            placeholder="All Roles"
                            @change="applyFilters"
                            class="border-gray-900"
                        />

                        <button
                            @click="resetFilters"
                            class="px-3 py-2 text-sm bg-gray-50 hover:bg-gray-100 border border-gray-900 rounded"
                        >
                            Reset
                        </button>
                    </div>

                    <div class="overflow-hidden rounded-lg border mt-6">
                        <div class="relative w-full overflow-auto">
                            <UserTable
                                :users="props.users"
                                v-model:selected="selected"
                                :allSelected="allSelected"
                                @toggle-select-all="toggleSelectAll"
                                @toggle-select-single="toggleSelectSingle"
                                @edit-user="openEditModal"
                                @delete-user="confirmDelete"
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

        <Modal :show="modalEditUser" @close="closeEditModal" maxWidth="2xl">
            <UserEditModal
                @childSubmitUser="saveEdituser"
                :roles="props.roles"
                :permissions="props.permissions"
                v-model:useFormUser="formUser"
                @close-modal="closeEditModal"
            >
            </UserEditModal>
        </Modal>

        <Modal
            :show="modalNewUser"
            @close="modalNewUser = false"
            maxWidth="2xl"
        >
            <UserNewModal
                @childSubmitUser="saveNewuser"
                :roles="props.roles"
                :permissions="props.permissions"
                v-model:useFormUser="formUser"
                @close-modal="closeNewUserModal"
            />
        </Modal>
    </LayoutDashboard>
</template>
