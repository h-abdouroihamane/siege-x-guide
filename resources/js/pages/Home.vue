<script setup>
import Logo from '@/components/Logo.vue';
import Navbar from '@/components/Navbar.vue';
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
        <div id="background-image" />
        <Navbar path="home" />
        <div id="container">
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
        </div>
    </div>
</template>

<style lang="scss">
@use '../../css/home-card.css';
</style>
