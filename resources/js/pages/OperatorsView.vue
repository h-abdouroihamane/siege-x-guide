<script setup lang="ts">
import Description from '@/components/Description.vue';
import Logo from '@/components/Logo.vue';
import OperatorCard from '@/components/OperatorCard.vue';
import Sidebar from '@/components/Sidebar.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import Navbar from '../components/Navbar.vue';
import { Operator } from '../scripts/operator.ts';

const page = usePage();

const allOperators: Operator[] = page.props.operators.data.map(
    (op) =>
        new Operator(
            op.name,
            op.description,
            op.side,
            op.year,
            op.season,
            op.operation.name,
            op.operation.release_date,
            op.roles,
            op.squad,
            op.queerIdentities,
            op.reworked,
        ),
);

let operators = ref([...allOperators]);
let sortingMethod = ref('date');
let activeSides = ref('attackersdefenders');
let showQueerIdentities = ref(false);

const placeholderOperator = new Operator(
    'placeholder',
    'Select an operator to see its description',
    'NoSide',
    -1,
    -1,
    'opPlaceHolder',
    [],
    'placeholderSquad',
);
const selectedOperator = ref(placeholderOperator);

const setSelectedOperator = (op: Operator) => {
    selectedOperator.value = op;
};

const sortOperators = (method) => {
    sortingMethod.value = method;
    filterAndSort();
};

const filterOperators = (sides) => {
    activeSides.value = sides;
    filterAndSort();
};

const toggleQueer = (b) => {
    showQueerIdentities.value = b;
    console.log('Queer from main:' + showQueerIdentities.value);
};

const filterAndSort = () => {
    const attackers = activeSides.value.includes('attackers');
    const defenders = activeSides.value.includes('defenders');
    let result;

    if (attackers && defenders) {
        result = [...allOperators];
    } else if (attackers) {
        result = allOperators.filter((op) => op.isAttacker());
    } else if (defenders) {
        result = allOperators.filter((op) => op.isDefender());
    } else {
        console.error("Can't filter out both attackers AND defenders");
        return;
    }

    switch (sortingMethod.value) {
        case 'name': {
            result = result.sort((a, b) => a.compareName(b));
            break;
        }

        default: {
            result = result.sort((a, b) => a.compareRelease(b));
            break;
        }
    }

    operators.value = [...result];
};

filterAndSort();
</script>
<template>
    <div>
        <Head>
            <title>Operators</title>
            <meta
                name="description"
                content="Page listing every operator from Rainbow Six Siege X and describing what their ability and/or gadget does"
            />
        </Head>
        <div id="background-image" />
        <Navbar path="operators" />
        <div id="container">
            <Logo :text="'Operator Guide'" />
            <div id="main-content">
                <Sidebar
                    @sort-by="sortOperators"
                    @filter-side="filterOperators"
                    @toggle-queer="toggleQueer"
                />

                <div id="card-container">
                    <OperatorCard
                        v-for="(op, index) in operators"
                        :key="index"
                        :operator="op"
                        :selected="selectedOperator.name === op.name"
                        :show-queer="showQueerIdentities"
                        @click="setSelectedOperator(op)"
                    >
                    </OperatorCard>
                </div>
            </div>
            <Description
                v-if="selectedOperator.year >= 0"
                v-bind="selectedOperator"
            />
            <div id="description" v-else>
                <span id="invisible-span"></span>
                <div id="description-text">
                    <span id="ability"
                        >Select an operator to see its description</span
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<style>
body {
    overflow: hidden;
}

#main-content {
    display: flex;
    flex-direction: row;
    width: 100%;
    justify-content: center;
}

#card-container {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    max-width: 70vw;
    width: 70vw;
    margin: 10px 0px 0px 0px;
    justify-content: center;
    align-items: center;
    height: 70vh;
    overflow-y: scroll;
    --sb-track-color: #232e33;
    --sb-thumb-color: #ff4b3c;
    --sb-size: 14px;
}

#card-container::-webkit-scrollbar {
    width: var(--sb-size);
}

#card-container::-webkit-scrollbar-track {
    background: var(--sb-track-color);
    border-radius: 3px;
}

#card-container::-webkit-scrollbar-thumb {
    background: var(--sb-thumb-color);
    border-radius: 3px;
}

@supports not selector(::-webkit-scrollbar) {
    #card-container {
        scrollbar-color: var(--sb-thumb-color) var(--sb-track-color);
    }
}
</style>
