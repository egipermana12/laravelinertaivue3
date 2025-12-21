<script setup>
const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: "",
    },
    options: {
        type: Array,
        required: true, // ["Admin","User"] atau [{label, value}]
    },
    placeholder: {
        type: String,
        default: "Select option",
    },
    optionValue: {
        type: String,
        default: "value", // kalau pakai array of object
    },
    optionLabel: {
        type: String,
        default: "label",
    },
    class: {
        type: String,
        default: "",
    },
});

const emit = defineEmits(["update:modelValue", "change"]);

const handleChange = (e) => {
    emit("update:modelValue", e.target.value);
    emit("change", e.target.value);
};
</script>
<template>
    <select
        :value="modelValue"
        @change="handleChange"
        class="flex items-center justify-between gap-2 rounded-md border bg-transparent px-3 py-2 text-sm whitespace-nowrap shadow-xs transition-[color,box-shadow] outline-none focus-visible:ring-[1px] disabled:cursor-not-allowed disabled:opacity-50 data-[size=default]:h-9 data-[size=sm]:h-8 *:data-[slot=select-value]:line-clamp-1 *:data-[slot=select-value]:flex *:data-[slot=select-value]:items-center *:data-[slot=select-value]:gap-2 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4 w-[180px]"
        :class="class"
    >
        <option value="">{{ placeholder }}</option>
        <!-- Jika options array of string -->
        <option
            v-for="(opt, i) in options"
            :key="i"
            :value="typeof opt === 'string' ? opt : opt[optionValue]"
        >
            {{ typeof opt === "string" ? opt : opt[optionLabel] }}
        </option>
    </select>
</template>
