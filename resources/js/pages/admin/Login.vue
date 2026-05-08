<script setup lang="ts">
import Logo from '@/components/Logo.vue';
import Navbar from '@/components/Navbar.vue';
import PageLayout from '@/components/PageLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const publicPath = import.meta.env.BASE_URL;

const form = useForm({
    email: null,
    password: null,
});
</script>

<template>
    <Head>
        <title>Admin login</title>
    </Head>
    <Navbar path="admin" />
    <PageLayout>
        <Logo :text="'Admin login'" />
        <div
            class="flex h-[500px] w-[300px] flex-col items-center justify-center border border-[#ff4b3c] bg-[rgba(17,17,17,0.7)] text-[#fefefe]"
        >
            <form
                class="grid justify-items-center gap-y-5"
                @submit.prevent="form.post(route('admin.authenticate'))"
            >
                <img
                    :src="`${publicPath}osa_login.jpg`"
                    alt="Osa"
                    class="h-[200px] w-auto"
                />

                <div class="grid max-w-[90%] justify-items-center gap-y-[2px]">
                    <label for="email">Email</label>
                    <input id="email" type="text" v-model="form.email" />
                </div>
                <div class="grid max-w-[90%] justify-items-center gap-y-[2px]">
                    <label for="pwd">Password</label>
                    <input id="pwd" type="password" v-model="form.password" />
                </div>

                <div v-if="form.errors.password">
                    {{ form.errors.password }}
                </div>

                <button
                    class="button-1"
                    type="submit"
                    :disabled="form.processing"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 448 512"
                    >
                        <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                        <path
                            d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z"
                        />
                    </svg>
                    Login
                </button>
            </form>
        </div>
    </PageLayout>
</template>
