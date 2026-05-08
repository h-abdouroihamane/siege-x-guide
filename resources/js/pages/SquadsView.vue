<script setup lang="ts">
import Logo from '@/components/Logo.vue';
import Navbar from '@/components/Navbar.vue';
import PageLayout from '@/components/PageLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { normalize } from '../scripts/operator.ts';
import { SITE_URL } from '../scripts/site.ts';

const page = usePage();
const squads = Object.entries(page.props.squads as Record<string, string[]>);
const squadHeader = `Squads (up to Year ${page.props.year}, Season ${page.props.season} - ${page.props.operationName})`;
const publicPath = import.meta.env.BASE_URL;

const getSquadLogo = (squadName: string) =>
    `${publicPath}squadLogos/${normalize(squadName)}.png`;
const getOperatorIcon = (operatorName: string) =>
    `${publicPath}operatorIcons/${normalize(operatorName)}.png`;

const SQUAD_BG: Record<string, string> = {
    wolfguard: 'bg-[rgba(6,140,178,0.3)]',
    ghosteyes: 'bg-[rgba(4,88,34,0.3)]',
    redhammer: 'bg-[rgba(129,18,9,0.3)]',
    viperstrike: 'bg-[rgba(223,187,2,0.3)]',
    nighthaven: 'bg-[rgba(231,235,238,0.3)]',
    unaffiliated: 'bg-[rgba(57,43,88,0.3)]',
};
const getSquadBg = (squad: string) => SQUAD_BG[squad.toLowerCase()] ?? '';

const getAltText = () => {
    let text = `Table showing every squad composition according to Rainbow Six Siege X's lore (up to Year ${page.props.year}, Season ${page.props.season} - ${page.props.operationName})\n\n`;

    for (const [squadName, operators] of squads) {
        text +=
            squadName !== 'Unaffiliated'
                ? `Squad ${squadName}\n`
                : 'Unaffiliated operators\n';
        text += operators.join(', ') + '\n\n';
    }

    text += `Source: ${SITE_URL}/squads`;

    return text;
};

const copyAltText = () => {
    navigator.clipboard.writeText(getAltText()).then(
        () => {
            alert('Alt text copied!\nThanks for caring about alt text! :)');
        },
        () => {
            alert('Failed to copy :(');
        },
    );
};

const screenshotMode = ref(false);
const toggleScreenshotMode = () => {
    screenshotMode.value = !screenshotMode.value;
};
</script>
<template>
    <Head>
        <title>Squads</title>
        <meta
            name="description"
            content="Page listing every squad from Rainbow Six Siege X according to the latest version of the lore"
        />
    </Head>
    <Navbar v-show="!screenshotMode" path="squads" />
    <PageLayout>
        <Logo :text="squadHeader" />
        <div class="flex max-w-[80vw] flex-col items-center text-[#fefefe]">
            <table class="w-full">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="[squad, operators] in squads" :key="squad">
                        <td :class="getSquadBg(squad)">
                            <div
                                class="flex flex-col items-center justify-center"
                            >
                                <img
                                    v-if="squad !== 'Unaffiliated'"
                                    class="m-2.5 h-[100px] w-auto"
                                    :src="getSquadLogo(squad)"
                                    :alt="`Squad ${squad}`"
                                />
                                <p
                                    class="font-gt-america m-[5px] text-[24px] uppercase"
                                >
                                    {{ squad }}
                                </p>
                            </div>
                        </td>
                        <td :class="getSquadBg(squad)">
                            <div
                                class="flex flex-wrap items-center justify-center"
                            >
                                <div
                                    v-for="(operatorName, index) in operators"
                                    :key="index"
                                    :id="normalize(operatorName)"
                                    class="flex flex-col items-center justify-center"
                                >
                                    <img
                                        class="h-auto w-[80px]"
                                        :src="getOperatorIcon(operatorName)"
                                        :alt="operatorName"
                                    />
                                    <p
                                        class="font-gt-america m-[5px] text-[20px] uppercase"
                                    >
                                        {{ operatorName }}
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div v-show="screenshotMode" class="mt-2.5">
                <a href="#" title="Siege X Guide - Squads section"
                    >{{ SITE_URL }}/squads</a
                >
            </div>

            <button
                v-if="screenshotMode"
                class="button-1"
                role="button"
                @click="toggleScreenshotMode()"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path
                        d="M48.5 224L40 224c-13.3 0-24-10.7-24-24L16 72c0-9.7 5.8-18.5 14.8-22.2s19.3-1.7 26.2 5.2L98.6 96.6c87.6-86.5 228.7-86.2 315.8 1c87.5 87.5 87.5 229.3 0 316.8s-229.3 87.5-316.8 0c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0c62.5 62.5 163.8 62.5 226.3 0s62.5-163.8 0-226.3c-62.2-62.2-162.7-62.5-225.3-1L185 183c6.9 6.9 8.9 17.2 5.2 26.2s-12.5 14.8-22.2 14.8L48.5 224z"
                    />
                </svg>
                Return to normal view
            </button>

            <button
                v-else
                class="button-1"
                role="button"
                @click="toggleScreenshotMode()"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path
                        d="M149.1 64.8L138.7 96 64 96C28.7 96 0 124.7 0 160L0 416c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-256c0-35.3-28.7-64-64-64l-74.7 0L362.9 64.8C356.4 45.2 338.1 32 317.4 32L194.6 32c-20.7 0-39 13.2-45.5 32.8zM256 192a96 96 0 1 1 0 192 96 96 0 1 1 0-192z"
                    />
                </svg>
                Toggle screenshot mode
            </button>

            <button
                v-if="screenshotMode"
                class="button-1 grey"
                role="button"
                @click="copyAltText()"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                    <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path
                        d="M192 0c-41.8 0-77.4 26.7-90.5 64L64 64C28.7 64 0 92.7 0 128L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64l-37.5 0C269.4 26.7 233.8 0 192 0zm0 64a32 32 0 1 1 0 64 32 32 0 1 1 0-64zM112 192l160 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-160 0c-8.8 0-16-7.2-16-16s7.2-16 16-16z"
                    />
                </svg>

                Copy alt text
            </button>
        </div>
    </PageLayout>
</template>
