<template>
    <div>
        <Head>
            <title>Admin dashboard</title>
        </Head>
        <Navbar path="admin" />
        <PageLayout>
            <Logo text="Admin dashboard" />

            <span id="message" v-if="page.props.message">{{
                page.props.message
            }}</span>
            <div id="options">
                <a v-for="p in paths" :key="p.url" :href="p.url">
                    <div class="card option">
                        <img
                            class="operator-portrait"
                            :src="p.image"
                            :alt="p.label"
                        />
                        <span class="operator-name">{{ p.label }}</span>
                    </div>
                </a>
            </div>
        </PageLayout>
    </div>
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

<style lang="scss" scoped>
@use '../../../css/operator-card.css';
#message {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #2fd072;
    color: #000;
    height: 50px;
    width: 500px;
    text-align: center;
    border-radius: 5px;
    margin: 10px;
}

#options a {
    all: unset;
}

#options {
    display: flex;
    justify-content: space-evenly;
    width: 500px;
}
</style>
