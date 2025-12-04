<script setup>
import Checkbox from "@/Components/Checkbox.vue";
const props = defineProps({
    users: Object,
    selected: Array,
    allSelected: Boolean,
});

const emit = defineEmits(["toggle-select-all", "toggle-select-single"]);
</script>

<template>
    <table class="w-full caption-bottom text-sm">
        <thead class="[&_tr]:border-b bg-muted sticky top-0 z-10">
            <tr
                class="hover:bg-muted/50 data-[state=selected]:bg-muted border-b transition-colors"
            >
                <th
                    class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-[2px]"
                    colspan="1"
                ></th>
                <th
                    class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-[2px]"
                    colspan="1"
                >
                    <Checkbox
                        :checked="props.allSelected"
                        @update:checked="emit('toggle-select-all')"
                    />
                </th>
                <th
                    class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-[2px]"
                    colspan="1"
                >
                    Username
                </th>
                <th
                    class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-[2px]"
                    colspan="1"
                >
                    Name
                </th>
                <th
                    class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-[2px]"
                    colspan="1"
                >
                    Email
                </th>
                <th
                    class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-[2px]"
                    colspan="1"
                >
                    Roles
                </th>
                <th
                    class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-[2px]"
                    colspan="1"
                >
                    Permissions
                </th>
            </tr>
        </thead>
        <tbody
            class="[&_tr:last-child]:border-0 **:data-[slot=table-cell]:first:w-8"
        >
            <tr
                class="hover:bg-muted/50 data-[state=selected]:bg-muted border-b transition-colors relative z-0 data-[dragging=true]:z-10 data-[dragging=true]:opacity-80"
                v-for="(user, index) in props.users.data"
                :key="user.id"
            >
                <td
                    class="p-2 align-middle whitespace-nowrap [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-[2px]"
                >
                    {{ index + 1 }}
                </td>
                <td
                    class="p-2 align-middle whitespace-nowrap [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-[2px]"
                >
                    <Checkbox
                        :checked="props.selected.includes(user.id)"
                        @update:checked="emit('toggle-select-single', user.id)"
                    />
                </td>
                <td
                    class="p-2 align-middle whitespace-nowrap [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-[2px]"
                >
                    {{ user.username }}
                </td>
                <td
                    class="p-2 align-middle whitespace-nowrap [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-[2px]"
                >
                    {{ user.name }}
                </td>
                <td
                    class="p-2 align-middle whitespace-nowrap [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-[2px]"
                >
                    {{ user.email }}
                </td>
                <td
                    class="p-2 align-middle whitespace-nowrap [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-[2px]"
                >
                    <span v-for="role in user.roles" :key="role">
                        <span
                            v-if="role == 'owner'"
                            class="bg-green-500 rounded text-white px-2 py-0.5"
                            >{{ role }}</span
                        >
                        <span
                            v-else-if="role == 'kasir'"
                            class="bg-blue-500 rounded text-white px-2 py-0.5"
                            >{{ role }}</span
                        >
                        <span
                            v-else-if="role == 'staff'"
                            class="bg-indigo-500 rounded text-white px-2 py-0.5"
                            >{{ role }}</span
                        >
                        <span
                            v-else-if="role == 'admin'"
                            class="bg-orange-500 rounded text-white px-2 py-0.5"
                            >{{ role }}</span
                        >
                        <span
                            v-else-if="role == 'developer'"
                            class="bg-red-500 rounded text-white px-2 py-0.5"
                            >{{ role }}</span
                        >
                    </span>
                </td>
                <td
                    class="p-2 align-middle whitespace-nowrap [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-[2px]"
                >
                    <span
                        v-for="permission in user.permissions"
                        :key="permission"
                    >
                        {{ permission }}
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
</template>
