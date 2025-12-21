<script setup>
import { ref, computed } from "vue";
import { Button } from "@/components/ui/button";
import InputError from "@/Components/InputError.vue";
import RadioGroup from "@/Components/RadioGroup.vue";
import SelectMulti from "@/Components/SelectMulti.vue";
const props = defineProps({
    useFormUser: Object,
    roles: Array,
    permissions: Array,
});

const emit = defineEmits(["childSubmitUser", "close-modal"]);
const submitForm = () => {
    // Anda bisa mengeluarkan data form di sini jika diperlukan
    // emit('childSubmitUser', dataForm);
    emit("childSubmitUser");
};
</script>
<template>
    <form @submit.prevent="submitForm">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-4">New Users</h2>
            <div class="mb-4">
                <label class="block text-sm text-gray-800 mb-1"
                    >Full Name</label
                >
                <input
                    v-model="useFormUser.name"
                    type="text"
                    class="w-full border rounded p-2"
                    placeholder="Jhon Doe"
                />
                <InputError class="mt-2" :message="useFormUser.errors.name" />
            </div>
            <div class="mb-4">
                <label class="block text-sm text-gray-800 mb-1">Username</label>
                <input
                    v-model="useFormUser.username"
                    type="text"
                    class="w-full border rounded p-2"
                    placeholder="jhondoe"
                />
                <InputError
                    class="mt-2"
                    :message="useFormUser.errors.username"
                />
            </div>
            <div class="mb-4">
                <label class="block text-sm text-gray-800 mb-1">Email</label>
                <input
                    v-model="useFormUser.email"
                    type="email"
                    class="w-full border rounded p-2"
                    placeholder="jhondoe@email.com"
                />
                <InputError class="mt-2" :message="useFormUser.errors.email" />
            </div>
            <div class="mb-4">
                <label class="block text-sm text-gray-800 mb-1">Password</label>
                <input
                    disabled="true"
                    v-model="useFormUser.password"
                    type="text"
                    class="w-full border rounded p-2 bg-gray-200"
                    placeholder="********"
                />
            </div>
            <div class="mb-4 border border-gray-500 rounded-md p-4">
                <label class="block text-sm text-gray-800 mb-1">Roles</label>
                <RadioGroup
                    class="flex flex-row space-x-2 items-center justify-between"
                    v-model="useFormUser.role"
                    :options="
                        roles.map((role) => ({
                            label: role,
                            value: role,
                        }))
                    "
                />
                <InputError class="mt-2" :message="useFormUser.errors.roles" />
            </div>
            <div class="mb-4">
                <label class="block text-sm text-gray-800 mb-1"
                    >Permisson</label
                >
                <SelectMulti
                    :all-options="
                        permissions.map((p) => ({
                            id: p.id,
                            name: p.name,
                        }))
                    "
                    v-model="useFormUser.permissions"
                />
                <InputError
                    class="mt-2"
                    :message="useFormUser.errors.permissions"
                />
            </div>
            <div class="pt-4 flex justify-end items-center gap-2">
                <button
                    type="button"
                    @click="$emit('close-modal')"
                    class="px-4 py-2 border text-white bg-red-500 rounded-sm text-sm hover:bg-red-600 font-bold"
                >
                    Cancel
                </button>
                <Button
                    type="submit"
                    :disabled="useFormUser.processing"
                    class=""
                >
                    Save Changes
                </Button>
            </div>
        </div>
    </form>
</template>
