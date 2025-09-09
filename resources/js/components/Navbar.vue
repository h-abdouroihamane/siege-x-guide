<script setup>
import { ref } from 'vue';
const props = defineProps(['path']);
const inLocalEnv = process.env.NODE_ENV === 'development';

const activeRoute = {
    home: props.path === 'home',
    operators: props.path === 'operators',
    squads: props.path === 'squads',
    admin: props.path === 'admin',
    about: props.path === 'about',
    vocabulary: props.path === 'vocabulary',
    secondaryGadgets: props.path === 'secondaryGadgets',
};

let hamburgerActive = ref(false);
let menubarActive = ref(false);

const toggleNav = () => {
    hamburgerActive.value = !hamburgerActive.value;
    menubarActive.value = !menubarActive.value;
};

const publicPath = import.meta.env.BASE_URL;
</script>

<template>
    <nav>
        <div class="logo">
            <img :src="`${publicPath}siege-x-icon.png`" alt="logo" />
        </div>
        <ul>
            <li>
                <a href="/" :class="{ active: activeRoute.home }">Home</a>
            </li>

            <li>
                <a href="/operators" :class="{ active: activeRoute.operators }">Operators</a>
            </li>
            <li>
                <a href="/secondary-gadgets" :class="{ active: activeRoute.secondaryGadgets }">Secondary gadgets</a>
            </li>

            <li>
                <a href="/squads" :class="{ active: activeRoute.squads }">Squads</a>
            </li>

            <li><a href="/vocabulary" :class="{ active: activeRoute.vocabulary }">Vocabulary</a></li>
            <li><a href="/about" :class="{ active: activeRoute.about }">About me</a></li>
            <li v-if="inLocalEnv"><a href="/admin/dashboard" :class="{ active: activeRoute.admin }">Admin panel</a></li>
        </ul>
        <div class="hamburger" :class="{ 'hamburger-active': hamburgerActive }" @click="toggleNav">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </div>
    </nav>
    <div class="menubar" :class="{ 'menubar-active': menubarActive }">
        <ul>
            <li>
                <a href="/" :class="{ active: activeRoute.home }">Home</a>
            </li>

            <li>
                <a href="/operators" :class="{ active: activeRoute.operators }">Operators</a>
            </li>
            <li>
                <a href="/secondary-gadgets" :class="{ active: activeRoute.secondaryGadgets }">Secondary gadgets</a>
            </li>

            <li>
                <a href="/squads" :class="{ active: activeRoute.squads }">Squads</a>
            </li>
            <li><a href="/vocabulary" :class="{ active: activeRoute.vocabulary }">Vocabulary</a></li>
            <li><a href="/about" :class="{ active: activeRoute.about }">About me</a></li>
            <li v-if="inLocalEnv"><a href="/admin/dashboard" :class="{ active: activeRoute.admin }">Admin panel</a></li>
        </ul>
    </div>
</template>

<style lang="scss">
@use '../../css/navbar.css';
</style>
