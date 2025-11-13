<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
//shcdn vue
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

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <div class="w-full lg:grid lg:min-h-[600px] lg:grid-cols-2 xl:min-h-[600px]">
    <div class="flex items-center justify-center py-12">
      <div class="mx-auto grid w-[350px] gap-6">
        <div class="grid gap-2 text-center">
          <h1 class="text-3xl font-bold">Login</h1>
          <p class="text-balance text-muted-foreground">
            Enter your email below to login to your account
          </p>
          <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>
        </div>
        <form @submit.prevent="submit">
            <div class="grid gap-4">
              <div class="grid gap-2">
                <Label for="email">Email</Label>
                <Input
                  id="email"
                  type="email"
                  v-model="form.email"
                  placeholder="m@example.com"
                  required
                />
                <InputError class="mt-2" :message="form.errors.email" />
              </div>
              <div class="grid gap-2">
                <div class="flex items-center">
                  <Label for="password">Password</Label>
                  <a
                    href="/forgot-password"
                    class="ml-auto inline-block text-sm underline"
                  >
                    Forgot your password?
                  </a>
                </div>
                <div class="relative w-full max-w-sm items-center">
                    <Input v-if="showPassword" id="password" type="password" v-model="form.password" required class="pr-10" />
                    <Input v-else id="password" type="text" v-model="form.password" required class="pr-10" />
                    <span @click="togglePassword" class="absolute end-0 inset-y-0 flex items-center justify-center px-2 cursor-pointer">
                      <Eye v-if="showPassword" class="size-6 text-muted-foreground" />
                      <EyeOff v-else class="size-6 text-muted-foreground" />
                    </span>
                </div>
                <InputError class="mt-2" :message="form.errors.password" />
              </div>
              <Button :disabled="form.processing" type="submit" class="w-full"> Login </Button>
            </div>
        </form>
        <div class="mt-4 text-center text-sm">
          Don't have an account?
          <Link :href="route('register')" class="underline"> Sign up </Link>
        </div>
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
