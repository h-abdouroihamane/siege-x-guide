<script setup lang="ts">
import Logo from '@/components/Logo.vue';
import Navbar from '@/components/Navbar.vue';
import PageLayout from '@/components/PageLayout.vue';
import { Head } from '@inertiajs/vue3';

const paths = [
    {
        url: '/operators',
        bg: '/recruits.jpg',
        title: 'Operators',
        description: "Learn what every operator's gadget/ability does",
    },
    {
        url: '/secondary-gadgets',
        bg: '/zoto.jpg',
        title: 'Secondary gadgets',
        description: 'Check which secondary gadgets are available to operators',
    },
    {
        url: '/squads',
        bg: '/board.jpg',
        title: 'Squads',
        description:
            'Check the squads composition according to the latest update of the lore',
    },
    {
        url: '/vocabulary',
        bg: '/highCalibre.jpg',
        title: 'Vocabulary',
        description:
            'Familiarize yourself with the terms commonly used in the game',
    },
    {
        url: '/about',
        bg: '/about-me.jpg',
        title: 'About me',
        description: 'Learn about me and check out my other projects! :)',
    },
];
</script>

<template>
    <Head>
        <title>Home</title>
    </Head>
    <Navbar path="home" />
    <PageLayout>
        <Logo text="Home" />

        <div
            class="mx-0 my-10 flex h-max w-[90vw] max-w-[1400px] flex-row flex-wrap items-center justify-around max-[790px]:my-0 max-[790px]:mb-[30px] max-[790px]:w-screen max-[790px]:flex-col"
        >
            <a
                class="card-link cursor-pointer [all:unset]"
                v-for="p in paths"
                :key="p.url"
                :href="p.url"
            >
                <div
                    class="home-card relative m-[0.3em] grid aspect-[3/5] h-auto w-[300px] max-w-[300px] content-end items-end overflow-hidden border border-[#c0c0c2] bg-[#151a257f] bg-cover bg-center p-[0_0_4px_0] text-white [text-shadow:1px_1px_2px_#000] max-[790px]:w-[200px]"
                    :style="{ backgroundImage: `url(${p.bg})` }"
                >
                    <div class="overlay" />
                    <span class="font-gt-america z-[3] text-[35px] uppercase">{{
                        p.title
                    }}</span>

                    <div class="card-bottom">
                        <span class="gradient"></span>
                        <span class="description">{{ p.description }}</span>
                    </div>
                </div>
            </a>
        </div>
    </PageLayout>
</template>

<style scoped>
/*
 * Hover-driven card reveal. Default state: title shown, description
 * hidden. On hover: card-bottom expands (display none -> grid,
 * scale 0 -> 1), red-to-transparent gradient fades in, the inset
 * shadow on .overlay deepens, and the card border shifts to signal
 * red. The combinator `.card-link:hover .child` reads more clearly
 * than chaining group-hover utilities across four nested elements.
 */
.card-link,
.home-card {
    transition: all 0.15s cubic-bezier(0.07, 0.95, 0, 1);
}

.card-link:hover .home-card {
    border-color: #ff4b3c;
}

.overlay {
    position: absolute;
    inset: 0;
    z-index: 1;
    box-shadow: 0 0 100px rgba(0, 0, 0, 0.9) inset;
}

.card-link:hover .overlay {
    box-shadow: 0 0 400px rgba(0, 0, 0, 0.9) inset;
}

.card-bottom {
    position: relative;
    min-height: 10px;
    transform: scale(0);
    display: none;
    z-index: 1;
}

.card-link:hover .card-bottom {
    min-height: 70px;
    transform: scale(1);
    display: grid;
    justify-items: center;
}

.gradient {
    position: relative;
    top: 5px;
    height: 77px;
    opacity: 0;
    z-index: 2;
    background: linear-gradient(
        180deg,
        rgba(0, 0, 0, 0) 0%,
        rgba(255, 75, 60, 1) 100%
    );
}

.card-link:hover .gradient {
    opacity: 0.6;
}

.description {
    position: absolute;
    top: 0;
    z-index: 3;
    height: 100%;
    width: 95%;
    font-size: 15px;
    display: grid;
    align-items: end;
}
</style>
