<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Operator } from '../scripts/operator.ts';
const page = usePage();

const operators: Operator[] = page.props.operators.data
    .map((op) => new Operator(op.name, op.description, op.side, op.year, op.season, op.roles, op.squad))
    .sort((a, b) => a.compareRelease(b));
</script>

<template>
    <div id="container">
        <div id="background-image" />
        <div id="card-container">
            <div v-for="op in operators" :key="op.name" class="op-card" :id="op.cleanName">
                <img class="op-portrait" :src="op.portrait" :alt="op.name" />
                <img class="op-icon" :src="op.icon" :alt="`${op.name} icon`" />
            </div>
        </div>
    </div>
</template>
