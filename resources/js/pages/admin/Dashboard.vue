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
        <div class="section-grid">
            <a
                v-for="p in paths"
                :key="p.url"
                :href="p.url"
                class="section-card"
                :style="{ backgroundImage: `url(${p.image})` }"
            >
                <span class="title">{{ p.label }}</span>
                <span class="body">{{ p.description }}</span>
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
        description: 'Add a new operator to the roster',
        url: page.props.createRoute,
        image: '/flores.jpg',
    },
    {
        label: 'Edit operator',
        description: 'Update an existing operator',
        url: page.props.editRoute,
        image: '/sens.jpg',
    },
];
</script>

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
    position: relative;
    z-index: 1;
    margin-left: 20px;
    transition: 0.3s;
    text-shadow: 1px 1px 2px #000;
}

.title {
    font-family: var(--font-display);
    text-transform: uppercase;
    font-size: 2.4em;
    line-height: 1;
    margin-top: auto;
    margin-bottom: 18px;
}

.section-card:hover .title {
    margin-bottom: 36px;
}

.body {
    position: absolute;
    bottom: 12px;
    left: 0;
    right: 20px;
    font-size: 0.95em;
    font-weight: 600;
    opacity: 0;
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
