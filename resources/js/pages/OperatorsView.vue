<script setup lang="ts">
import Description from '@/components/Description.vue';
import OperatorCard from '@/components/OperatorCard.vue';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import Navbar from '../components/Navbar.vue';
import { Operator } from '../scripts/operator.ts';

const page = usePage();

const operators: Operator[] = page.props.operators.data
    .map((op) => new Operator(op.name, op.description, op.side, op.year, op.season, op.operation_name, op.roles, op.squad))
    .sort((a, b) => a.compareRelease(b));

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
</script>
<template>
    <div>
        <div id="background-image" />
        <Navbar />
        <div id="container">
            <img id="logo" src="Siege_X_Guide_Logo.png" alt="Rainbow Six Siege X Operator Guide" />
            <div id="grid-container">
                <div id="card-container">
                    <OperatorCard
                        v-for="op in operators"
                        :key="op.name"
                        :operator="op"
                        :selected="selectedOperator.name === op.name"
                        @click="setSelectedOperator(op)"
                    >
                    </OperatorCard>
                </div>
                <Description v-if="selectedOperator.year >= 0" v-bind="selectedOperator" />
                <div id="description" v-else>
                    <p id="description-text">Select an operator to see its description</p>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="css">
body {
    overflow: hidden;
}

#card-container {
    overflow: scroll;
    height: 80vh;
}
</style>
