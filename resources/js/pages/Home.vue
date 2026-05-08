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

        <div class="section-grid">
            <a
                class="section-card"
                v-for="p in paths"
                :key="p.url"
                :href="p.url"
                :style="{ backgroundImage: `url(${p.bg})` }"
            >
                <span class="title">{{ p.title }}</span>
                <span class="body">{{ p.description }}</span>
            </a>
        </div>
    </PageLayout>
</template>

<style scoped>
.section-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, 400px);
    grid-auto-rows: 280px;
    gap: 0.6em;
    justify-content: center;
    width: 100%;
    max-width: 1400px;
    margin: 2.5em 0;
    padding: 0 1em;
}

.section-card {
    position: relative;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    text-decoration: none;
    color: #fff;
    background-color: #151a257f;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    border: 1px solid #c0c0c2;
    padding: 0 0 4px 0;
    transition: 0.3s;
}

.section-card::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(0deg, #151a25, transparent 50%);
    transition: background 0.3s;
    pointer-events: none;
}

.section-card:hover {
    border-bottom: 5px solid #ff4b3c;
}

.section-card:hover::after {
    background: linear-gradient(0deg, #ff4b3c, transparent 50%);
}

.title,
.body {
    position: absolute;
    left: 20px;
    right: 20px;
    z-index: 1;
    transition: 0.3s;
    text-shadow: 1px 1px 2px #000;
}

.title {
    bottom: 16px;
    font-family: var(--font-display);
    text-transform: uppercase;
    font-size: 2em;
    line-height: 1;
}

.section-card:hover .title {
    bottom: 64px;
}

.body {
    bottom: 16px;
    font-size: 0.9em;
    font-weight: 600;
    line-height: 1.25;
    opacity: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.section-card:hover .body {
    opacity: 1;
}

@media (max-width: 480px) {
    .section-grid {
        grid-template-columns: minmax(0, 1fr);
        grid-auto-rows: 220px;
    }
}
</style>
