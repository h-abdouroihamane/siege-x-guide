<script setup lang="ts">
import Logo from '@/components/Logo.vue';
import Navbar from '@/components/Navbar.vue';
import PageLayout from '@/components/PageLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

import { normalize } from '../scripts/operator.ts';
const page = usePage();

const publicPath = import.meta.env.BASE_URL;
const getOperatorIcon = (operatorName) =>
    `${publicPath}operatorIcons/${normalize(operatorName)}.png`;
const data = page.props.roles.sort((a, b) => a.name.localeCompare(b.name));
</script>

<template>
    <Head>
        <title>Vocabulary</title>
    </Head>
    <Navbar path="vocabulary" />
    <PageLayout>
        <Logo text="Vocabulary" />
        <div class="mb-[30px] flex flex-col items-center justify-center">
            <div
                class="mt-5 flex h-max flex-col items-center justify-center rounded-[10px] bg-[rgba(1,1,1,0.1)] px-5 text-[#fefefe] [box-shadow:0_3px_6px_rgba(0,0,0,0.16),0_3px_6px_rgba(0,0,0,0.23)]"
            >
                <span
                    class="mb-4 text-[1.5rem] tracking-[0.2ch] text-[#ff4b3c] uppercase"
                    >Roles</span
                >
                <div
                    class="grid grid-cols-2 gap-[10px] max-[790px]:flex max-[790px]:flex-col [&>:nth-child(2n-1):nth-last-of-type(1)]:col-span-2"
                >
                    <div
                        class="flex max-w-[600px] flex-col items-center justify-center justify-self-center self-start"
                        v-for="role in data"
                        :key="role.name"
                    >
                        <span
                            class="font-gt-america mb-[10px] text-[23px] font-bold text-[#ff4b3c] uppercase"
                            >{{ role.name }}</span
                        >
                        <span class="mb-[10px]">{{ role.definition }}</span>
                        <div
                            class="flex flex-row flex-wrap items-center justify-center"
                        >
                            <div
                                v-for="(operatorName, index) in role.operators"
                                :key="index"
                                class="flex flex-col items-center justify-center"
                            >
                                <img
                                    :src="getOperatorIcon(operatorName)"
                                    :alt="operatorName"
                                    class="h-auto w-[50px]"
                                />
                                <p
                                    class="font-gt-america m-[5px] text-[16px] uppercase"
                                >
                                    {{ operatorName }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PageLayout>
</template>
