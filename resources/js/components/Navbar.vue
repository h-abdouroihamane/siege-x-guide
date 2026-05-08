<!--
  Aesthetic: tactical dossier (R6 Siege).
  Navigation bar — near-black semi-transparent bg, signal-red border/accent.
  Body: FK Grotesk (font-sans). Dark only — no light variant.
  Note: hamburger nth-child transition rules live in <style scoped> below
  because CSS selectors on nth-child cannot be expressed as Tailwind utilities.
-->
<script setup lang="ts">
import { ref } from 'vue';

const props = defineProps<{
    path: string;
}>();
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

const hamburgerActive = ref(false);
const menubarActive = ref(false);

const toggleNav = () => {
    hamburgerActive.value = !hamburgerActive.value;
    menubarActive.value = !menubarActive.value;
};

const publicPath = import.meta.env.BASE_URL;
</script>

<template>
    <nav
        class="py-[5px] px-[5%] flex justify-between items-center z-[1] border-b border-signal bg-[rgba(17,17,17,0.7)] [box-shadow:rgba(50,50,93,0.25)_0px_2px_5px_-1px,rgba(0,0,0,0.3)_0px_1px_3px_-1px]"
    >
        <div class="logo flex items-center">
            <img
                :src="`${publicPath}siege-x-icon.png`"
                alt="logo"
                class="h-[25px] w-auto mr-[10px]"
            />
        </div>
        <ul class="list-none flex m-0 p-0 max-[790px]:hidden">
            <li class="ml-6">
                <a
                    :href="route('home.index')"
                    :class="[
                        'no-underline text-[#b0bac6] text-[95%]',
                        'font-normal px-2 py-1 rounded-[5px]',
                        'transition-all duration-300 ease-in-out',
                        activeRoute.home
                            ? 'text-signal'
                            : 'hover:bg-signal hover:text-[#0c0f16]',
                    ]"
                    >Home</a
                >
            </li>

            <li class="ml-6">
                <a
                    :href="route('operator.show')"
                    :class="[
                        'no-underline text-[#b0bac6] text-[95%]',
                        'font-normal px-2 py-1 rounded-[5px]',
                        'transition-all duration-300 ease-in-out',
                        activeRoute.operators
                            ? 'text-signal'
                            : 'hover:bg-signal hover:text-[#0c0f16]',
                    ]"
                    >Operators</a
                >
            </li>
            <li class="ml-6">
                <!-- secondary-gadgets route has no name; flagged for backend-dev -->
                <a
                    href="/secondary-gadgets"
                    :class="[
                        'no-underline text-[#b0bac6] text-[95%]',
                        'font-normal px-2 py-1 rounded-[5px]',
                        'transition-all duration-300 ease-in-out',
                        activeRoute.secondaryGadgets
                            ? 'text-signal'
                            : 'hover:bg-signal hover:text-[#0c0f16]',
                    ]"
                    >Secondary gadgets</a
                >
            </li>

            <li class="ml-6">
                <!-- squads route has no name; flagged for backend-dev -->
                <a
                    href="/squads"
                    :class="[
                        'no-underline text-[#b0bac6] text-[95%]',
                        'font-normal px-2 py-1 rounded-[5px]',
                        'transition-all duration-300 ease-in-out',
                        activeRoute.squads
                            ? 'text-signal'
                            : 'hover:bg-signal hover:text-[#0c0f16]',
                    ]"
                    >Squads</a
                >
            </li>

            <li class="ml-6">
                <a
                    :href="route('vocabulary.index')"
                    :class="[
                        'no-underline text-[#b0bac6] text-[95%]',
                        'font-normal px-2 py-1 rounded-[5px]',
                        'transition-all duration-300 ease-in-out',
                        activeRoute.vocabulary
                            ? 'text-signal'
                            : 'hover:bg-signal hover:text-[#0c0f16]',
                    ]"
                    >Vocabulary</a
                >
            </li>
            <li class="ml-6">
                <a
                    :href="route('about.index')"
                    :class="[
                        'no-underline text-[#b0bac6] text-[95%]',
                        'font-normal px-2 py-1 rounded-[5px]',
                        'transition-all duration-300 ease-in-out',
                        activeRoute.about
                            ? 'text-signal'
                            : 'hover:bg-signal hover:text-[#0c0f16]',
                    ]"
                    >About me</a
                >
            </li>
            <li v-if="inLocalEnv" class="ml-6">
                <a
                    :href="route('admin.dashboard')"
                    :class="[
                        'no-underline text-[#b0bac6] text-[95%]',
                        'font-normal px-2 py-1 rounded-[5px]',
                        'transition-all duration-300 ease-in-out',
                        activeRoute.admin
                            ? 'text-signal'
                            : 'hover:bg-signal hover:text-[#0c0f16]',
                    ]"
                    >Admin panel</a
                >
            </li>
        </ul>
        <!-- Hamburger — visible on narrow viewports (≤790px) -->
        <div
            class="hamburger hidden cursor-pointer max-[790px]:block"
            :class="{ 'hamburger-active': hamburgerActive }"
            @click="toggleNav"
            role="button"
            aria-label="Toggle navigation menu"
            :aria-expanded="hamburgerActive"
        >
            <span
                class="line block w-[25px] h-px bg-signal my-[7px] mx-auto transition-all duration-300 ease-in-out"
            ></span>
            <span
                class="line block w-[25px] h-px bg-signal my-[7px] mx-auto transition-all duration-300 ease-in-out"
            ></span>
            <span
                class="line block w-[25px] h-px bg-signal my-[7px] mx-auto transition-all duration-300 ease-in-out"
            ></span>
        </div>
    </nav>

    <!-- Mobile slide-in menu -->
    <div
        class="menubar absolute top-0 -left-[60%] hidden justify-center items-start w-[60%] h-screen py-[20%] bg-[rgba(17,17,17,1)] transition-all duration-500 ease-in z-[2] max-[790px]:flex"
        :class="{
            'menubar-active': menubarActive,
        }"
    >
        <ul class="p-0 list-none">
            <li class="mb-8">
                <a
                    :href="route('home.index')"
                    :class="[
                        'no-underline text-[#b0bac6] text-[95%]',
                        'font-normal px-[10px] py-[5px] rounded-[5px]',
                        'transition-all duration-300 ease-in-out',
                        activeRoute.home
                            ? 'text-signal'
                            : 'hover:bg-signal hover:text-[#0c0f16]',
                    ]"
                    >Home</a
                >
            </li>

            <li class="mb-8">
                <a
                    :href="route('operator.show')"
                    :class="[
                        'no-underline text-[#b0bac6] text-[95%]',
                        'font-normal px-[10px] py-[5px] rounded-[5px]',
                        'transition-all duration-300 ease-in-out',
                        activeRoute.operators
                            ? 'text-signal'
                            : 'hover:bg-signal hover:text-[#0c0f16]',
                    ]"
                    >Operators</a
                >
            </li>
            <li class="mb-8">
                <!-- secondary-gadgets route has no name; flagged for backend-dev -->
                <a
                    href="/secondary-gadgets"
                    :class="[
                        'no-underline text-[#b0bac6] text-[95%]',
                        'font-normal px-[10px] py-[5px] rounded-[5px]',
                        'transition-all duration-300 ease-in-out',
                        activeRoute.secondaryGadgets
                            ? 'text-signal'
                            : 'hover:bg-signal hover:text-[#0c0f16]',
                    ]"
                    >Secondary gadgets</a
                >
            </li>

            <li class="mb-8">
                <!-- squads route has no name; flagged for backend-dev -->
                <a
                    href="/squads"
                    :class="[
                        'no-underline text-[#b0bac6] text-[95%]',
                        'font-normal px-[10px] py-[5px] rounded-[5px]',
                        'transition-all duration-300 ease-in-out',
                        activeRoute.squads
                            ? 'text-signal'
                            : 'hover:bg-signal hover:text-[#0c0f16]',
                    ]"
                    >Squads</a
                >
            </li>
            <li class="mb-8">
                <a
                    :href="route('vocabulary.index')"
                    :class="[
                        'no-underline text-[#b0bac6] text-[95%]',
                        'font-normal px-[10px] py-[5px] rounded-[5px]',
                        'transition-all duration-300 ease-in-out',
                        activeRoute.vocabulary
                            ? 'text-signal'
                            : 'hover:bg-signal hover:text-[#0c0f16]',
                    ]"
                    >Vocabulary</a
                >
            </li>
            <li class="mb-8">
                <a
                    :href="route('about.index')"
                    :class="[
                        'no-underline text-[#b0bac6] text-[95%]',
                        'font-normal px-[10px] py-[5px] rounded-[5px]',
                        'transition-all duration-300 ease-in-out',
                        activeRoute.about
                            ? 'text-signal'
                            : 'hover:bg-signal hover:text-[#0c0f16]',
                    ]"
                    >About me</a
                >
            </li>
            <li v-if="inLocalEnv" class="mb-8">
                <a
                    :href="route('admin.dashboard')"
                    :class="[
                        'no-underline text-[#b0bac6] text-[95%]',
                        'font-normal px-[10px] py-[5px] rounded-[5px]',
                        'transition-all duration-300 ease-in-out',
                        activeRoute.admin
                            ? 'text-signal'
                            : 'hover:bg-signal hover:text-[#0c0f16]',
                    ]"
                    >Admin panel</a
                >
            </li>
        </ul>
    </div>
</template>

<style scoped>
/*
 * Hamburger animation — nth-child selectors cannot be expressed as Tailwind
 * utilities, so this minimal block is intentionally kept here.
 * All other Navbar rules live as inline utilities on the template above.
 */
.hamburger-active {
    transition: all 0.3s ease-in-out;
    transition-delay: 0.6s;
    transform: rotate(45deg);
}

.hamburger-active .line:nth-child(2) {
    width: 0;
}

.hamburger-active .line:nth-child(1),
.hamburger-active .line:nth-child(3) {
    transition-delay: 0.3s;
}

.hamburger-active .line:nth-child(1) {
    transform: translateY(12px);
}

.hamburger-active .line:nth-child(3) {
    transform: translateY(-5px) rotate(90deg);
}

.menubar-active {
    left: 0;
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
    z-index: 10;
}
</style>
