<script setup>
import { ref } from 'vue';
import { publicAsset } from '../scripts/operator';
const props = defineProps(['path']);

const activeRoute = {
    home: props.path === 'home',
    operators: props.path === 'operators',
    squads: props.path === 'squads',
    about: props.path === 'about',
    vocabulary: props.path === 'vocabulary',
    secondaryGadgets: props.path === 'secondaryGadgets',
};

const hamburgerActive = ref(false);
const menubarActive = ref(false);

const toggleNav = () => {
    hamburgerActive.value = !hamburgerActive.value;
    menubarActive.value = !menubarActive.value;
};
</script>

<template>
    <nav>
        <div class="logo">
            <img :src="publicAsset('siege-x-icon.png')" alt="logo" />
        </div>
        <ul>
            <li>
                <a href="/" :class="{ active: activeRoute.home }">Home</a>
            </li>

            <li>
                <a href="/operators" :class="{ active: activeRoute.operators }"
                    >Operators</a
                >
            </li>
            <li>
                <a
                    href="/secondary-gadgets"
                    :class="{ active: activeRoute.secondaryGadgets }"
                    >Secondary gadgets</a
                >
            </li>

            <li>
                <a href="/squads" :class="{ active: activeRoute.squads }"
                    >Squads</a
                >
            </li>

            <li>
                <a
                    href="/vocabulary"
                    :class="{ active: activeRoute.vocabulary }"
                    >Vocabulary</a
                >
            </li>
            <li>
                <a href="/about" :class="{ active: activeRoute.about }"
                    >About me</a
                >
            </li>
        </ul>
        <div
            class="hamburger"
            :class="{ 'hamburger-active': hamburgerActive }"
            @click="toggleNav"
        >
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
                <a href="/operators" :class="{ active: activeRoute.operators }"
                    >Operators</a
                >
            </li>
            <li>
                <a
                    href="/secondary-gadgets"
                    :class="{ active: activeRoute.secondaryGadgets }"
                    >Secondary gadgets</a
                >
            </li>

            <li>
                <a href="/squads" :class="{ active: activeRoute.squads }"
                    >Squads</a
                >
            </li>
            <li>
                <a
                    href="/vocabulary"
                    :class="{ active: activeRoute.vocabulary }"
                    >Vocabulary</a
                >
            </li>
            <li>
                <a href="/about" :class="{ active: activeRoute.about }"
                    >About me</a
                >
            </li>
        </ul>
    </div>
</template>

<style lang="scss">
@use '../../css/navbar.css';
</style>
