<template>
    <VueDraggable
        tag="ul"
        v-model="list"
        :group="{ name: 'sidebar', pull: true, put: true }"
        item-key="id"
        class="drag-area"
        :class="wrapperClass"
        @end="onEnd"
        @move="onMove"
        :data-is-child="isChild"
    >
        <li v-for="el in list" :key="el.id" class="my-2">
            <div
                class="p-2 bg-gray-900 text-white flex justify-between rounded"
            >
                <div class="flex items-center gap-2">
                    <Move size="18" />
                    {{ el.title }}
                </div>
            </div>

            <!-- nested -->
            <SidebarSortItem v-model="el.items" :isChild="true" @end="onEnd" />
        </li>
    </VueDraggable>
</template>

<script setup>
import { VueDraggable } from "vue-draggable-plus";
import SidebarSortItem from "./SidebarSortItem.vue";
import { Move } from "lucide-vue-next";
import { computed } from "vue";
import { useSwal } from "@/lib/useSwal";

const { fire } = useSwal();

const props = defineProps({
    modelValue: Array,
    isChild: Boolean,
});

const emit = defineEmits(["update:modelValue", "end"]);

const list = computed({
    get: () => props.modelValue,
    set: (val) => emit("update:modelValue", val),
});

const onEnd = (evt) => {
    emit("end", evt); // forward ke parent
};

const wrapperClass = computed(() => (props.isChild ? "ml-4" : ""));

// Definisikan fungsi onMove secara terpisah
const onMove = (evt) => {
    const dragged = evt.data; // item asli
    const toIsChild = evt.to.dataset.isChild === "true";
    const fromIsChild = evt.from.dataset.isChild === "true";

    // ===============================
    // RULE 1: Parent (punya child) tidak boleh masuk child
    // ===============================
    if (dragged.items && dragged.items.length > 0 && toIsChild) {
        console.warn("Parent tidak boleh menjadi child");
        return false;
    }

    // ===============================
    // RULE 2: Child tidak boleh punya child
    // ===============================
    if (fromIsChild && toIsChild) {
        // masih sama-sama child → boleh
        fire("Item child tidak boleh memiliki child lagi.");
        return false;
    }

    if (fromIsChild && !toIsChild) {
        // child pindah ke parent → boleh
        return true;
    }

    if (!fromIsChild && toIsChild) {
        // parent tanpa child masuk child → boleh
        return true;
    }

    return true;
};
</script>

<style scoped>
.drag-area {
    min-height: 50px;
    outline: 1px dashed;
}
</style>
