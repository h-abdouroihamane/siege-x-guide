<script setup>
import GadgetTable from '@/components/GadgetTable.vue';
import Logo from '@/components/Logo.vue';
import Navbar from '@/components/Navbar.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import AttackerLogo from '../components/AttackerLogo.vue';
import DefenderLogo from '../components/DefenderLogo.vue';
import { normalize } from '../scripts/operator.ts';
const page = usePage();

const headerText = `Secondary gadgets (as of the latest patch of Year ${page.props.year}, Season ${page.props.season} - ${page.props.operationName})`;
const publicPath = import.meta.env.BASE_URL;

const getGadgetLogo = (gadgetName) => {
    const name = gadgetName.toLowerCase().replace(/ +/g, '-');
    return `${publicPath}secondaryGadgets/${name}.png`;
};

const getOperatorIcon = (operatorName) => `${publicPath}operatorIcons/${normalize(operatorName)}.png`;

let selectedSide = ref('Attack');
const setSide = (side) => (selectedSide.value = side);
</script>
<template>
    <div>
        <Head>
            <title>Secondary Gadgets</title>
            <meta
                name="description"
                content="Page listing every operator's secondary gadget options from Rainbow Six Siege X as of the latest patch of the game"
            />
        </Head>
        <div id="background-image" />
        <Navbar path="secondaryGadgets" />

        <div id="container">
            <Logo :text="headerText" />
            <div id="main-content">
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
        </div>
    </div>
</template>

<style lang="scss">
@use '../../css/table.css';
@use '../../css/button.css';
#main-content {
    max-width: 80vw;
    flex-direction: column;
}
</style>
