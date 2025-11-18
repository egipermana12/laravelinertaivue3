<script setup>
import InputError from '@/Components/InputError.vue';

//shcdn vue
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Eye, EyeOff } from "lucide-vue-next";


import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

//handle show hide password
const showPassword = ref(true);

const togglePassword = () => {
    showPassword.value = !showPassword.value
}


const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Update Password
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Ensure your account is using a long, random password to stay
                secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div>
                <Label for="current_password">Current Password</Label>
                <div class="relative w-full items-center">
                    <Input
                        v-if="showPassword"
                        id="current_password"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        type="password"
                        autocomplete="current-password"
                        class="pr-10 w-full"
                    />
                    <Input
                        v-else
                        id="current_password"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        type="text"
                        autocomplete="current-password"
                        class="pr-10 w-full"
                    />
                    <span @click="togglePassword" class="absolute end-0 inset-y-0 flex items-center justify-center px-2 cursor-pointer">
                      <Eye v-if="showPassword" class="size-6 text-muted-foreground" />
                      <EyeOff v-else class="size-6 text-muted-foreground" />
                    </span>
                </div>

                <InputError
                    :message="form.errors.current_password"
                    class="mt-2"
                />
            </div>

            <div>
                <Label for="password">New Password</Label>
                <div class="relative w-full items-center">
                    <Input
                        v-if="showPassword"
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="pr-10 w-full"
                        autocomplete="new-password"
                    />
                    <Input
                        v-else
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="text"
                        class="pr-10 w-full"
                        autocomplete="new-password"
                    />
                    <span @click="togglePassword" class="absolute end-0 inset-y-0 flex items-center justify-center px-2 cursor-pointer">
                      <Eye v-if="showPassword" class="size-6 text-muted-foreground" />
                      <EyeOff v-else class="size-6 text-muted-foreground" />
                    </span>
                </div>

                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div>
                <Label for="password">Confirm Password</Label>
                <div class="relative w-full items-center">
                    <Input
                        v-if="showPassword"
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        autocomplete="new-password"
                    />
                    <Input
                        v-else
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="text"
                        autocomplete="new-password"
                    />
                    <span @click="togglePassword" class="absolute end-0 inset-y-0 flex items-center justify-center px-2 cursor-pointer">
                      <Eye v-if="showPassword" class="size-6 text-muted-foreground" />
                      <EyeOff v-else class="size-6 text-muted-foreground" />
                    </span>
                </div>

                <InputError
                    :message="form.errors.password_confirmation"
                    class="mt-2"
                />
            </div>

            <div class="flex items-center gap-4">
                <Button :disabled="form.processing">Save</Button>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
