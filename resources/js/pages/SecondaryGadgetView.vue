<script setup lang="ts">
import GadgetTable from '@/components/GadgetTable.vue';
import Logo from '@/components/Logo.vue';
import Navbar from '@/components/Navbar.vue';
import PageLayout from '@/components/PageLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import AttackerLogo from '../components/AttackerLogo.vue';
import DefenderLogo from '../components/DefenderLogo.vue';

const page = usePage();

const headerText = `Secondary gadgets (as of the latest patch of Year ${page.props.year}, Season ${page.props.season} - ${page.props.operationName})`;

const selectedSide = ref<'Attack' | 'Defense'>('Attack');
const setSide = (side: 'Attack' | 'Defense') => (selectedSide.value = side);
</script>
<template>
    <Head>
        <title>Secondary Gadgets</title>
        <meta
            name="description"
            content="Page listing every operator's secondary gadget options from Rainbow Six Siege X as of the latest patch of the game"
        />
    </Head>
    <Navbar path="secondaryGadgets" />
    <PageLayout>
        <Logo :text="headerText" />
        <div class="flex max-w-[80vw] flex-col items-center">
            <div id="gadget-side-btn">
                <button
                    id="attackers"
                    class="radio-button left attackers"
                    :class="{ active: selectedSide === 'Attack' }"
                    @click="setSide('Attack')"
                >
                    <AttackerLogo /> ATTACKERS
                </button>
                <button
                    id="defenders"
                    class="radio-button right defenders"
                    :class="{ active: selectedSide === 'Defense' }"
                    @click="setSide('Defense')"
                >
                    <DefenderLogo /> DEFENDERS
                </button>
            </div>

            <div id="table-container">
                <GadgetTable
                    v-if="selectedSide === 'Attack'"
                    side="attack"
                    :operators="page.props.attackers"
                    :gadgets="page.props.attackGadgets.data"
                    :operation-name="page.props.operationName"
                    :year="page.props.year"
                    :season="page.props.season"
                />
                <GadgetTable
                    v-if="selectedSide === 'Defense'"
                    side="defense"
                    :operators="page.props.defenders"
                    :gadgets="page.props.defenseGadgets.data"
                    :operation-name="page.props.operationName"
                    :year="page.props.year"
                    :season="page.props.season"
                />
            </div>
        </div>
    </PageLayout>
</template>

<style lang="scss">
@use '../../css/table.css';
@use '../../css/button.css';
</style>
