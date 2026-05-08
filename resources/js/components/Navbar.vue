<!-- Aesthetic: tactical dossier / industrial. -->
<!-- Display: GT America Compressed Bold. Body: FK Grotesk. -->
<!-- Dominant: near-black ground. Accent: signal-red #ff4b3c. -->
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

const isOpen = ref(false);

const toggleNav = () => {
    isOpen.value = !isOpen.value;
};

const publicPath = import.meta.env.BASE_URL;
</script>

<template>
    <!--
        Nav uses named breakpoint md (768px) to match the original
        790px intent — preferred over arbitrary max-[790px] per step
        1.3 migration approach.
    -->
    <nav
        class="flex items-center justify-between px-[5%] py-[5px] border-b border-[#ff4b3c] bg-[rgba(17,17,17,0.7)] z-[1] shadow-[rgba(50,50,93,0.25)_0px_2px_5px_-1px,rgba(0,0,0,0.3)_0px_1px_3px_-1px]"
    >
        <div class="flex items-center">
            <img
                :src="`${publicPath}siege-x-icon.png`"
                alt="Siege X logo"
                class="h-[25px] w-auto mr-[10px]"
            />
        </div>

        <ul class="list-none flex max-md:hidden">
            <li class="ml-6">
                <a
                    :href="route('home.index')"
                    :class="[
                        'no-underline text-[95%] font-normal px-2 py-1',
                        'rounded-[5px] transition-all duration-300 ease-in-out',
                        activeRoute.home
                            ? 'text-[#ff4b3c]'
                            : 'text-[#b0bac6] hover:bg-[#ff4b3c] hover:text-[#0c0f16]',
                    ]"
                    >Home</a
                >
            </li>
            <li class="ml-6">
                <a
                    :href="route('operator.show')"
                    :class="[
                        'no-underline text-[95%] font-normal px-2 py-1',
                        'rounded-[5px] transition-all duration-300 ease-in-out',
                        activeRoute.operators
                            ? 'text-[#ff4b3c]'
                            : 'text-[#b0bac6] hover:bg-[#ff4b3c] hover:text-[#0c0f16]',
                    ]"
                    >Operators</a
                >
            </li>
            <li class="ml-6">
                <!-- TODO: backend-dev to name route -->
                <a
                    href="/secondary-gadgets"
                    :class="[
                        'no-underline text-[95%] font-normal px-2 py-1',
                        'rounded-[5px] transition-all duration-300 ease-in-out',
                        activeRoute.secondaryGadgets
                            ? 'text-[#ff4b3c]'
                            : 'text-[#b0bac6] hover:bg-[#ff4b3c] hover:text-[#0c0f16]',
                    ]"
                    >Secondary gadgets</a
                >
            </li>
            <li class="ml-6">
                <!-- TODO: backend-dev to name route -->
                <a
                    href="/squads"
                    :class="[
                        'no-underline text-[95%] font-normal px-2 py-1',
                        'rounded-[5px] transition-all duration-300 ease-in-out',
                        activeRoute.squads
                            ? 'text-[#ff4b3c]'
                            : 'text-[#b0bac6] hover:bg-[#ff4b3c] hover:text-[#0c0f16]',
                    ]"
                    >Squads</a
                >
            </li>
            <li class="ml-6">
                <a
                    :href="route('vocabulary.index')"
                    :class="[
                        'no-underline text-[95%] font-normal px-2 py-1',
                        'rounded-[5px] transition-all duration-300 ease-in-out',
                        activeRoute.vocabulary
                            ? 'text-[#ff4b3c]'
                            : 'text-[#b0bac6] hover:bg-[#ff4b3c] hover:text-[#0c0f16]',
                    ]"
                    >Vocabulary</a
                >
            </li>
            <li class="ml-6">
                <a
                    :href="route('about.index')"
                    :class="[
                        'no-underline text-[95%] font-normal px-2 py-1',
                        'rounded-[5px] transition-all duration-300 ease-in-out',
                        activeRoute.about
                            ? 'text-[#ff4b3c]'
                            : 'text-[#b0bac6] hover:bg-[#ff4b3c] hover:text-[#0c0f16]',
                    ]"
                    >About me</a
                >
            </li>
            <li v-if="inLocalEnv" class="ml-6">
                <a
                    :href="route('admin.dashboard')"
                    :class="[
                        'no-underline text-[95%] font-normal px-2 py-1',
                        'rounded-[5px] transition-all duration-300 ease-in-out',
                        activeRoute.admin
                            ? 'text-[#ff4b3c]'
                            : 'text-[#b0bac6] hover:bg-[#ff4b3c] hover:text-[#0c0f16]',
                    ]"
                    >Admin panel</a
                >
            </li>
        </ul>

        <!-- Hamburger toggle: converted to <button> for keyboard + a11y -->
        <button
            type="button"
            aria-label="Toggle navigation menu"
            :aria-expanded="isOpen"
            :class="[
                'hidden max-md:block cursor-pointer bg-transparent border-none p-0',
                'focus-visible:outline-2 focus-visible:outline-[#ff4b3c]',
                'focus-visible:outline-offset-2',
                isOpen ? 'hamburger-active' : '',
            ]"
            @click="toggleNav"
        >
            <span
                class="line block w-[25px] h-px bg-[#ff4b3c] my-[7px] transition-all duration-300 ease-in-out"
            ></span>
            <span
                class="line block w-[25px] h-px bg-[#ff4b3c] my-[7px] transition-all duration-300 ease-in-out"
            ></span>
            <span
                class="line block w-[25px] h-px bg-[#ff4b3c] my-[7px] transition-all duration-300 ease-in-out"
            ></span>
        </button>
    </nav>

    <!-- Mobile slide-in menubar -->
    <div
        :class="[
            'absolute top-0 hidden max-md:flex flex-col justify-center',
            'items-start w-[60%] h-screen pt-[20%] pb-0',
            'bg-[rgba(17,17,17,1)] transition-all duration-500 ease-in z-[2]',
            isOpen
                ? 'left-0 shadow-[rgba(149,157,165,0.2)_0px_8px_24px] z-[10]'
                : '-left-[60%]',
        ]"
    >
        <ul class="list-none p-0">
            <li class="mb-8">
                <a
                    :href="route('home.index')"
                    :class="[
                        'no-underline text-[95%] font-normal px-[10px] py-[5px]',
                        'rounded-[5px] transition-all duration-300 ease-in-out',
                        activeRoute.home
                            ? 'text-[#ff4b3c]'
                            : 'text-[#b0bac6] hover:bg-[#ff4b3c] hover:text-[#0c0f16]',
                    ]"
                    >Home</a
                >
            </li>
            <li class="mb-8">
                <a
                    :href="route('operator.show')"
                    :class="[
                        'no-underline text-[95%] font-normal px-[10px] py-[5px]',
                        'rounded-[5px] transition-all duration-300 ease-in-out',
                        activeRoute.operators
                            ? 'text-[#ff4b3c]'
                            : 'text-[#b0bac6] hover:bg-[#ff4b3c] hover:text-[#0c0f16]',
                    ]"
                    >Operators</a
                >
            </li>
            <li class="mb-8">
                <!-- TODO: backend-dev to name route -->
                <a
                    href="/secondary-gadgets"
                    :class="[
                        'no-underline text-[95%] font-normal px-[10px] py-[5px]',
                        'rounded-[5px] transition-all duration-300 ease-in-out',
                        activeRoute.secondaryGadgets
                            ? 'text-[#ff4b3c]'
                            : 'text-[#b0bac6] hover:bg-[#ff4b3c] hover:text-[#0c0f16]',
                    ]"
                    >Secondary gadgets</a
                >
            </li>
            <li class="mb-8">
                <!-- TODO: backend-dev to name route -->
                <a
                    href="/squads"
                    :class="[
                        'no-underline text-[95%] font-normal px-[10px] py-[5px]',
                        'rounded-[5px] transition-all duration-300 ease-in-out',
                        activeRoute.squads
                            ? 'text-[#ff4b3c]'
                            : 'text-[#b0bac6] hover:bg-[#ff4b3c] hover:text-[#0c0f16]',
                    ]"
                    >Squads</a
                >
            </li>
            <li class="mb-8">
                <a
                    :href="route('vocabulary.index')"
                    :class="[
                        'no-underline text-[95%] font-normal px-[10px] py-[5px]',
                        'rounded-[5px] transition-all duration-300 ease-in-out',
                        activeRoute.vocabulary
                            ? 'text-[#ff4b3c]'
                            : 'text-[#b0bac6] hover:bg-[#ff4b3c] hover:text-[#0c0f16]',
                    ]"
                    >Vocabulary</a
                >
            </li>
            <li class="mb-8">
                <a
                    :href="route('about.index')"
                    :class="[
                        'no-underline text-[95%] font-normal px-[10px] py-[5px]',
                        'rounded-[5px] transition-all duration-300 ease-in-out',
                        activeRoute.about
                            ? 'text-[#ff4b3c]'
                            : 'text-[#b0bac6] hover:bg-[#ff4b3c] hover:text-[#0c0f16]',
                    ]"
                    >About me</a
                >
            </li>
            <li v-if="inLocalEnv" class="mb-8">
                <a
                    :href="route('admin.dashboard')"
                    :class="[
                        'no-underline text-[95%] font-normal px-[10px] py-[5px]',
                        'rounded-[5px] transition-all duration-300 ease-in-out',
                        activeRoute.admin
                            ? 'text-[#ff4b3c]'
                            : 'text-[#b0bac6] hover:bg-[#ff4b3c] hover:text-[#0c0f16]',
                    ]"
                    >Admin panel</a
                >
            </li>
        </ul>
    </div>
</template>

<!--
    Only the hamburger nth-child animation remains here.
    These rules target sibling positions on a class toggle and
    cannot be expressed as Tailwind utilities.
-->
<style scoped>
.hamburger-active {
    transition: all 0.3s ease-in-out;
    transition-delay: 0.6s;
    transform: rotate(45deg);
}

.hamburger-active .line:nth-child(1) {
    transform: translateY(12px);
    transition-delay: 0.3s;
}

.hamburger-active .line:nth-child(2) {
    width: 0px;
}

.hamburger-active .line:nth-child(3) {
    transform: translateY(-5px) rotate(90deg);
    transition-delay: 0.3s;
}
</style>
