<script setup lang="ts">
import Description from '@/components/Description.vue';
import OperatorCard from '@/components/OperatorCard.vue';
import Sidebar from '@/components/Sidebar.vue';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import Navbar from '../components/Navbar.vue';
import { Operator } from '../scripts/operator.ts';

const page = usePage();

const allOperators: Operator[] = page.props.operators.data.map(
    (op) => new Operator(op.name, op.description, op.side, op.year, op.season, op.operation_name, op.roles, op.squad)
);

let operators = ref([...allOperators]);
let sortingMethod = ref('date');
let activeSides = ref('attackersdefenders');

const placeholderOperator = new Operator(
    'placeholder',
    'Select an operator to see its description',
    'NoSide',
    -1,
    -1,
    'opPlaceHolder',
    [],
    'placeholderSquad'
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
        <div id="background-image" />
        <Navbar />
        <div id="container">
            <img id="logo" src="Siege_X_Guide_Logo.png" alt="Rainbow Six Siege X Operator Guide" />
            <div id="main-content">
                <Sidebar @sort-by="sortOperators" @filter-side="filterOperators" />

                <div id="card-container">
                    <OperatorCard
                        v-for="(op, index) in operators"
                        :key="index"
                        :operator="op"
                        :selected="selectedOperator.name === op.name"
                        @click="setSelectedOperator(op)"
                    >
                    </OperatorCard>
                </div>
            </div>
            <Description v-if="selectedOperator.year >= 0" v-bind="selectedOperator" />
            <div id="description" v-else>
                <span id="invisible-span"></span>
                <div id="description-text">
                    <span id="ability">Select an operator to see its description</span>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="css">
body {
    overflow: hidden;
}

#main-content {
    display: flex;
    flex-direction: row;
    width: 100%;
    justify-content: center;
}
</style>
