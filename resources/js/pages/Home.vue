<script setup lang="ts">
import Logo from '@/components/Logo.vue';
import Navbar from '@/components/Navbar.vue';
import PageLayout from '@/components/PageLayout.vue';
import { Head } from '@inertiajs/vue3';

const paths = [
    {
        url: '/operators',
        className: 'operators',
        title: 'Operators',
        description: "Learn what every operator's gadget/ability does",
    },
    {
        url: '/secondary-gadgets',
        className: 'secondary-gadgets',
        title: 'Secondary gadgets',
        description: 'Check which secondary gadgets are available to operators',
    },
    {
        url: '/squads',
        className: 'squads',
        title: 'Squads',
        description:
            'Check the squads composition according to the latest update of the lore',
    },
    {
        url: '/vocabulary',
        className: 'vocabulary',
        title: 'Vocabulary',
        description:
            'Familiarize yourself with the terms commonly used in the game',
    },
    {
        url: '/about',
        className: 'about',
        title: 'About me',
        description: 'Learn about me and check out my other projects! :)',
    },
];
</script>

<template>
    <div>
        <Head>
            <title>Home</title>
        </Head>
        <Navbar path="home" />
        <PageLayout>
            <Logo text="Home" />

            <div id="home-card-container">
                <a
                    class="card-link"
                    v-for="p in paths"
                    :key="p.url"
                    :href="p.url"
                >
                    <div :class="['home-card', p.className]">
                        <div class="overlay" />
                        <span class="title">{{ p.title }}</span>

                        <div class="card-bottom">
                            <span class="gradient"></span>
                            <span class="description">{{ p.description }}</span>
                        </div>
                    </div>
                </a>
            </div>
        </PageLayout>
    </div>
</template>

<style lang="scss">
@use '../../css/home-card.css';
</style>
