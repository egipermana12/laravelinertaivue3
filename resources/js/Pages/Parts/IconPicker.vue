<script setup>
import { iconList, useIcon } from "@/lib/useIcon";
</script>

<template>
    <div class="bg-white p-4 w-full h-96 rounded overflow-y-scroll">
        <h2 class="text-lg font-bold mb-4">Pilih Icon</h2>
        <div class="grid grid-cols-4 gap-4">
            <div
                v-for="icon in iconList"
                :key="icon.name"
                class="flex flex-col items-center cursor-pointer hover:bg-gray-100 p-2 rounded"
                @click="$emit('select', icon.name)"
            >
                <component
                    :is="useIcon(icon.name) || 'span'"
                    class="w-6 h-6 mb-2"
                />
                <span class="text-sm text-center">{{ icon.name }}</span>
            </div>
        </div>
    </div>
</template>
