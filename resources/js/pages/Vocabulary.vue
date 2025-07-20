<script setup>
import Logo from '@/components/Logo.vue';
import Navbar from '@/components/Navbar.vue';
import { Head, usePage } from '@inertiajs/vue3';
const page = usePage();
</script>

<template>
    <div>
        <Head>
            <title>Vocabulary</title>
        </Head>
        <div id="background-image" />
        <Navbar path="vocabulary" />
        <div id="container">
            <Logo text="Vocabulary" />
        </div>
    </div>
</template>
