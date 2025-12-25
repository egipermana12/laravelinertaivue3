<script setup>
import { ref, watch } from "vue";
import { Button } from "@/components/ui/button";
import InputError from "@/Components/InputError.vue";
import { useIcon } from "@/lib/useIcon";
import Modal from "@/Components/Modal.vue";
import iconPicker from "../Parts/iconPicker.vue";

const props = defineProps({
    useFormSidebar: Object,
});

const emit = defineEmits(["childSubmitNew", "close-modal-new"]);
const submitForm = () => {
    // Anda bisa mengeluarkan data form di sini jika diperlukan
    // emit('childSubmitNew', dataForm);
    emit("childSubmitNew");
};

const updateIcons = (iconName) => {
    props.useFormSidebar.icon = iconName;
    modalIcons.value = false;
};

const modalIcons = ref(false);

const openIconPicker = () => {
    modalIcons.value = true;
};

const closeIconPicker = () => {
    modalIcons.value = false;
};
</script>

<template>
    <form @submit.prevent="submitForm">
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
                <InputError class="mt-2" :message="useFormSidebar.errors.url" />
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
            <div class="pt-4 flex justify-end items-center gap-2">
                <button
                    type="button"
                    @click="$emit('close-modal-new')"
                    class="px-4 py-2 border text-white bg-red-500 rounded-sm text-sm hover:bg-red-600 font-bold"
                >
                    Cancel
                </button>
                <Button
                    type="submit"
                    :disabled="useFormSidebar.processing"
                    class=""
                >
                    Save Changes
                </Button>
            </div>
        </div>
    </form>

    <Modal :show="modalIcons" @close="closeIconPicker" maxWidth="xl">
        <icon-picker @select="updateIcons" />
    </Modal>
</template>
