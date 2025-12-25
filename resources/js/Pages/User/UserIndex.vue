<script setup>
import LayoutDashboard from "@/Layouts/LayoutDashboard.vue";
import Input from "@/Components/ui/input/Input.vue";
import Select from "@/Components/Select.vue";
import Modal from "@/Components/Modal.vue";
import { Head, router, useForm, usePage } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";
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
        only: ["users", "filters"],
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
const users = computed(() => usePage().props.users);
const pageUserIds = computed(() => users.value.data.map((user) => user.id));

const allSelected = computed(() =>
    pageUserIds.value.every((id) => selected.value.includes(id))
);

//select all checkbox di klik
function toggleSelectAll() {
    if (allSelected.value) {
        selected.value = [];
    } else {
        selected.value = [...pageUserIds.value];
    }
}

//hapus select jika filter berubah
watch(
    () => users.value.data,
    () => {
        selected.value = [];
    }
);

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
const editForm = useForm({
    id: null,
    name: "",
    username: "",
    email: "",
    role: "",
    permissions: [],
});

// edit user
const modalEditUser = ref(false);

const openEditModal = (user) => {
    editForm.reset();
    editForm.id = user.id;
    editForm.name = user.name;
    editForm.username = user.username;
    editForm.email = user.email;
    editForm.role = user.roles[0] ?? "";
    editForm.permissions = user.permissions;
    modalEditUser.value = true;
};

const closeEditModal = () => {
    editForm.reset();
    editForm.clearErrors();
    modalEditUser.value = false;
};

//new users
const createForm = useForm({
    name: "",
    username: "",
    email: "",
    password: "",
    role: "staff",
    permissions: [],
});
const modalNewUser = ref(false);

const openNewUserModal = () => {
    createForm.reset();
    modalNewUser.value = true;
};

const closeNewUserModal = () => {
    createForm.reset();
    createForm.clearErrors();
    modalNewUser.value = false;
};

const saveEdituser = () => {
    editForm.put(route("users.update", editForm.id), {
        preserveScroll: true,
        onSuccess: () => {
            modalEditUser.value = false;
            editForm.reset();
            success("User berhasil diupdate.");
        },
    });
};

//save new users
const saveNewuser = () => {
    createForm.post(route("users.store"), {
        preserveScroll: true,
        onSuccess: () => {
            modalNewUser.value = false;
            createForm.reset();
            success("User berhasil ditambahkan.");
        },
    });
};

// delete user
const confirmDelete = async (userId) => {
    const confirmed = await confirm("Hapus user ini?", {
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus",
        cancelButtonText: "Batal",
        confirmButtonColor: "#dc2626", // merah
        cancelButtonColor: "#6b7280", // abu-abu
    });
    if (!confirmed) return;

    router.post(
        route("users.bulk-delete"),
        {
            user_ids: [userId],
        },
        {
            onSuccess: () => success("User berhasil dihapus."),
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
                                :users="users"
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
                        <Pagination :links="users.links" />
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="modalEditUser" @close="closeEditModal" maxWidth="2xl">
            <UserEditModal
                @childSubmitUser="saveEdituser"
                :roles="props.roles"
                :permissions="props.permissions"
                v-model:useFormUser="editForm"
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
                v-model:useFormUser="createForm"
                @close-modal="closeNewUserModal"
            />
        </Modal>
    </LayoutDashboard>
</template>
