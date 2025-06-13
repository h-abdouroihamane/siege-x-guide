<script setup>
import { ref } from 'vue';
const props = defineProps(['path']);

const activeRoute = {
    operators: props.path === 'operators',
    squads: props.path === 'squads',
    admin: props.path === 'admin',
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
                <a href="/operators" :class="{ active: activeRoute.operators }">Operators</a>
            </li>
            <li>
                <a href="/squads" :class="{ active: activeRoute.squads }">Squads</a>
            </li>
            <li><a href="/admin" :class="{ active: activeRoute.admin }">Admin panel</a></li>
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
                <a href="/operators" :class="{ active: activeRoute.operators }">Operators</a>
            </li>
            <li>
                <a href="/squads" :class="{ active: activeRoute.squads }">Squads</a>
            </li>
            <li><a href="/admin" :class="{ active: activeRoute.admin }">Admin panel</a></li>
        </ul>
    </div>
</template>

<style lang="scss">
@use '../../css/navbar.css';
</style>
