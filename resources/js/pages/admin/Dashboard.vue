<template>
    <Head>
        <title>Admin dashboard</title>
    </Head>
    <Navbar path="admin" />
    <PageLayout>
        <Logo text="Admin dashboard" />

        <span
            v-if="page.props.message"
            class="m-2.5 flex h-[50px] w-[500px] items-center justify-center rounded-[5px] bg-[#2fd072] text-center text-black"
            >{{ page.props.message }}</span
        >
        <div class="flex w-[500px] justify-evenly">
            <a
                v-for="p in paths"
                :key="p.url"
                :href="p.url"
                class="[all:unset]"
            >
                <div
                    class="relative mx-2.5 my-5 flex h-max w-[120px] cursor-pointer flex-col items-center rounded-[2px] transition-all duration-300 [box-shadow:rgba(255,255,255,0.09)_0px_3px_6px,rgba(255,255,255,0.16)_0px_3px_6px] hover:bg-[#ff4b3c] hover:[box-shadow:0_0_10px_1px_#ff4b3c]"
                >
                    <img class="h-full w-full" :src="p.image" :alt="p.label" />
                    <span
                        class="dashboard-card-name my-[5px] flex h-[20px] items-center justify-center bg-[rgba(17,17,17,0.15)] px-0 pt-[7px] pb-[10px] text-[20px] text-white uppercase backdrop-blur-[2px]"
                        >{{ p.label }}</span
                    >
                </div>
            </a>
        </div>
    </PageLayout>
</template>

<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import Logo from '../../components/Logo.vue';
import Navbar from '../../components/Navbar.vue';
import PageLayout from '../../components/PageLayout.vue';
const page = usePage();

const paths = [
    {
        label: 'New operator',
        url: page.props.createRoute,
        image: '/flores.jpg',
    },
    { label: 'Edit operator', url: page.props.editRoute, image: '/sens.jpg' },
];
</script>

<style scoped>
/* Regular 'GT America' (not the display compressed variant).
   Will promote to a --font-gt-america @theme token in batch 3. */
.dashboard-card-name {
    font-family: 'GT America', sans-serif;
}
</style>
