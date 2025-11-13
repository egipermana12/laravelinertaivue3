<script setup>
import InputError from '@/Components/InputError.vue';

// shcdn
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Eye, EyeOff } from "lucide-vue-next"

import { Head, Link, useForm } from '@inertiajs/vue3';

import { ref } from 'vue';

//handle show hide password
const showPassword = ref(true);

const togglePassword = () => {
    showPassword.value = !showPassword.value
}

const form = useForm({
    name: '',
    email: '',
    username: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>

    <Head title="Register" />
    <div class="w-full lg:grid lg:min-h-[600px] lg:grid-cols-2 xl:min-h-[600px]">
    <div class="flex items-center justify-center py-12">
        <div class="mx-auto grid w-[350px] gap-6">
        <div class="grid gap-2 text-center">
          <h1 class="text-3xl font-bold">Register</h1>
          <p class="text-balance text-muted-foreground">
            Fill all form to register your account
          </p>
        </div>
        <form @submit.prevent="submit">
            <div class="grid gap-4">
             <div class="grid gap-2">
                <Label for="email">Full Name</Label>

                <Input
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    autofocus
                    placeholder="Enter Fullname"
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="username">Username</Label>

                <Input
                    id="username"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.username"
                    placeholder="Enter username"
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.username" />
            </div>
             <div class="grid gap-2">
                <Label for="email">Email</Label>

                <Input
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    placeholder="Enter email"
                    autocomplete="email"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="grid gap-2">
                <Label for="password">Password</Label>

                <div class="relative w-full max-w-sm items-center">
                    <Input v-if="showPassword" id="password" type="password" v-model="form.password"  class="pr-10" />
                    <Input v-else id="password" type="text" v-model="form.password"  class="pr-10" />
                    <span @click="togglePassword" class="absolute end-0 inset-y-0 flex items-center justify-center px-2 cursor-pointer">
                      <Eye v-if="showPassword" class="size-6 text-muted-foreground" />
                      <EyeOff v-else class="size-6 text-muted-foreground" />
                    </span>
                </div>

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="grid gap-2">
                <Label for="password">Confirm Password</Label>

                <div class="relative w-full max-w-sm items-center">
                    <Input v-if="showPassword" id="password_confirmation" type="password" v-model="form.password_confirmation"  class="pr-10" />
                    <Input v-else id="password_confirmation" type="text" v-model="form.password_confirmation"  class="pr-10" />
                    <span @click="togglePassword" class="absolute end-0 inset-y-0 flex items-center justify-center px-2 cursor-pointer">
                      <Eye v-if="showPassword" class="size-6 text-muted-foreground" />
                      <EyeOff v-else class="size-6 text-muted-foreground" />
                    </span>
                </div>

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>
            <Button :disabled="form.processing"  type="submit" :class="{ 'opacity-25': form.processing }" class="w-full"> Register </Button>

            <div class="mt-4 flex items-center justify-center">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Already registered?
                </Link>
            </div>
        </div>
        </form>
        </div>
    </div>
    <div class="hidden bg-muted lg:block">
      <img
        src="https://www.shadcn-vue.com/placeholder.svg"
        alt="Image"
        width="1920"
        height="1080"
        class="h-full w-full object-cover dark:brightness-[0.2] dark:grayscale"
      />
    </div>
    </div>
</template>
