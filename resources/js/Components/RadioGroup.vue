<script setup>
import { computed } from "vue";

const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
    modelValue: {
        type: [String, Number, Boolean],
        default: null,
    },
    class: {
        type: String,
        default: "",
    },
});
const emit = defineEmits(["update:modelValue"]);
const handleChange = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit("update:modelValue", value);
    },
});
</script>

<template>
    <div class="" :class="props.class">
        <template v-for="option in props.options" :key="option.value">
            <label
                class="inline-flex items-center space-x-2 cursor-pointer border border-gray-300 rounded-md px-3 py-2"
            >
                <input
                    type="radio"
                    v-model="handleChange"
                    :value="option.value"
                    class="form-radio text-blue-600"
                />
                <span>{{ option.label }}</span>
            </label>
        </template>
    </div>
</template>
