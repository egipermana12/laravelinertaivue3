<script setup lang="ts">
    import { Button } from '@/components/ui/button';
    import InputError from '@/Components/InputError.vue';

    import { Link, useForm, usePage } from '@inertiajs/vue3';
    import { ref, computed } from 'vue';

    import {
      ImagePlus,
      X
    } from "lucide-vue-next"

    const userProps = computed(() => usePage().props.auth.user);

    const form = useForm({
        avatar: null,
    });


    const avatar = computed(() => userProps.value.avatar ? userProps.value.avatar : null);
    const previewImage = ref(null);
    const fileInput = ref(null);

    const handleSelectImage = (e) => {
        const input = e.target as HTMLInputElement;

        if (input.files && input.files[0]) {
            const file = input.files[0];

            //set ke form
            form.avatar = file;

            const reader = new FileReader();
            reader.onload = (event) => {
                previewImage.value = event.target?.result; // Data URL
            };
            reader.readAsDataURL(file);
        }
    };

    const handleClearPreview = () => {
        previewImage.value = null;
        if (fileInput.value) {
            fileInput.value.value = "";
        }
        form.avatar = null;
    }

    const deleteAvatar = () => {
        form.patch(route('profile.deleteavatar'), {
            preserveScroll: true
        });
    }

    const updateavatar = () => {
        form.post(route('profile.updateavatar'), {
            preserveScroll: true,
        });
    }

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Avatar Picture
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's avatar image.
            </p>
        </header>
        <div class="mt-4">
            <form @submit.prevent="updateavatar">
                <div class="bg-gray-50 shadow w-36 h-36 rounded p-1 relative">
                    <div v-if="avatar == null" class="relative bg-blue-50 rounded w-full h-full flex justify-center items-center">
                        <div v-if="previewImage == null">
                            <div class="w-14 h-14 bg-gray-500 rounded-full absolute top-0 left-0 transform translate-x-[80%] translate-y-[40%]"></div>
                            <div class="w-24 h-12 bg-gray-500 rounded-t-full absolute bottom-0 left-0 transform translate-x-[24%]"></div>
                        </div>
                        <div v-else class="w-36 h-36">
                            <img class="object-cover rounded" :src="previewImage" />
                        </div>
                    </div>
                    <div v-else>
                        <div class="relative bg-blue-50 rounded w-full h-full flex justify-center items-center">
                            <div class="">
                                <img class="object-cover rounded" :src="avatar" />
                            </div>
                        </div>
                    </div>
                    <div v-if="avatar == null">
                        <div class="absolute top-0 right-0 transform translate-x-10">
                            <label for="upload-image">
                                <span class="cursor-pointer text-sm h-8 w-8 text-blue-900 bg-blue-100 shadow p-2 rounded-full flex items-center justify-center">
                                    <ImagePlus size="16" />
                                </span>
                                <input type="file" ref="fileInput" id="upload-image" class="hidden" @change="handleSelectImage" />
                            </label>
                        </div>
                        <div class="absolute bottom-0 right-0 transform translate-x-10">
                            <button type="button" class="text-sm h-8 w-8 text-red-900 bg-red-100 shadow p-2 rounded-full flex items-center justify-center"
                            @click="handleClearPreview">
                                <X size="16" />
                            </button>
                        </div>
                    </div>
                    <div v-else>
                        <div class="absolute bottom-0 right-0 transform translate-x-10">
                            <button type="button" class="text-sm h-8 w-8 text-red-900 bg-red-100 shadow p-2 rounded-full flex items-center justify-center"
                            @click="deleteAvatar">
                                <X size="16" />
                            </button>
                        </div>
                    </div>
                </div>

                <InputError class="mt-2" :message="form.errors.avatar" />

                <div class="flex items-center gap-4 mt-2" v-if="avatar == null">
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
        </div>
    </section>
</template>
