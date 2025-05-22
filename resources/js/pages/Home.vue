<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import Navbar from '../components/Navbar.vue';
import { Operator } from '../scripts/operator.ts';
const page = usePage();

const operators: Operator[] = page.props.operators.data
    .map((op) => new Operator(op.name, op.description, op.side, op.year, op.season, op.roles, op.squad))
    .sort((a, b) => a.compareRelease(b));
</script>

<template>
    <div>
        <div id="background-image" />
        <Navbar />
        <div id="container">
            <img id="logo" src="Siege_X_Guide_Logo.png" alt="Rainbow Six Siege X Operator Guide" />
            <div id="card-container">
                <div
                    v-for="op in operators"
                    :key="op.name"
                    :class="{ card: true, attacker: op.isAttacker(), defender: op.isDefender() }"
                    :id="op.cleanName"
                >
                    <img class="operator-portrait" :src="op.portrait" :alt="op.name" />
                    <img class="operator-icon" :src="op.icon" :alt="`${op.name} icon`" />
                    <span class="operator-name">{{ op.name }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="css"></style>
