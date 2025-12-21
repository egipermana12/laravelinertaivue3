<script setup>
import { computed, ref } from "vue";
import { Trash } from "lucide-vue-next";
const props = defineProps({
    allOptions: {
        type: Array,
        required: true,
    },
    modelValue: {
        type: Array,
        required: true,
    },
});

// Definisikan ref untuk elemen select box
const selectBoxRef = ref(null);

const emit = defineEmits(["update:modelValue"]);

// Mengubah modelValue (ID) menjadi objek izin lengkap untuk ditampilkan
const selectedOptions = computed(() => {
    return props.allOptions.filter((p) => props.modelValue.includes(p.id));
});

// Menghitung izin mana yang masih 'tersedia' untuk dipilih
const availableOptions = computed(() => {
    return props.allOptions.filter((p) => !props.modelValue.includes(p.id));
});

//add event select click
const addOptions = (optionId) => {
    const id = parseInt(optionId);

    //cek apakah sudah ada pada modelValue agar tidak duplikasi
    if (!props.modelValue.includes(id)) {
        //buat array baru dg data array ditambahkan
        const newOptions = [...props.modelValue, id];

        // Emit event untuk memperbarui v-model di komponen induk
        emit("update:modelValue", newOptions);

        // 2. Logika Reset Select Box (Modifikasi di sini)
        // Pastikan ref ada sebelum mencoba mereset
        if (selectBoxRef.value) {
            // Set nilai select box kembali ke nilai opsi default (yaitu string kosong "")
            selectBoxRef.value.value = "";
        }
    }
};

//remove options
const removeOptions = (optionId) => {
    // Filter array, hanya simpan ID yang TIDAK sama dengan ID izin yang ingin dihapus
    const newOptions = props.modelValue.filter((id) => id !== optionId);

    // Emit event untuk memperbarui v-model di komponen induk
    emit("update:modelValue", newOptions);
};
</script>

<template>
    <!-- select -->
    <div>
        <select
            class="flex items-center justify-between gap-2 rounded-md border bg-transparent px-3 py-2 text-sm whitespace-nowrap shadow-xs transition-[color,box-shadow] outline-none focus-visible:ring-[1px] disabled:cursor-not-allowed disabled:opacity-50 data-[size=default]:h-9 data-[size=sm]:h-8 *:data-[slot=select-value]:line-clamp-1 *:data-[slot=select-value]:flex *:data-[slot=select-value]:items-center *:data-[slot=select-value]:gap-2 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4 w-[180px]"
            @change="addOptions($event.target.value)"
            ref="selectBoxRef"
        >
            <option value="" disabled selected>Pilih Izin ...</option>

            <option
                v-for="option in availableOptions"
                :key="option.id"
                :value="option.id"
            >
                {{ option.name }}
            </option>
        </select>
    </div>

    <!-- penampung -->
    <div class="mt-4 border p-4 overflow-auto flex flex-wrap gap-2">
        <span
            v-for="option in selectedOptions"
            :key="option.id"
            class="inline-flex rounded-base shadow-xs -space-x-px"
        >
            <span
                class="inline-flex items-center text-body bg-neutral-primary-soft border border-default hover:bg-neutral-secondary-medium hover:text-heading focus:ring-3 focus:ring-neutral-tertiary-soft font-medium leading-5 rounded-s-lg text-sm px-3 py-2 focus:outline-none"
                >{{ option.name }}</span
            >
            <span
                class="inline-flex items-center justify-center text-body bg-neutral-primary-soft border border-default hover:bg-neutral-secondary-medium hover:text-heading focus:ring-3 focus:ring-neutral-tertiary-soft font-medium leading-5 rounded-e-lg text-sm text-red-500 w-9 h-full focus:outline-none cursor-pointer"
                @click="removeOptions(option.id)"
                ><Trash size="16"
            /></span>
        </span>
        <p v-if="selectedOptions.length === 0">Belum ada izin</p>
    </div>
</template>
