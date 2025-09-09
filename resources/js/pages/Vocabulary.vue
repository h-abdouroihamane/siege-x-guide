<script setup>
import Logo from '@/components/Logo.vue';
import Navbar from '@/components/Navbar.vue';
import { Head, usePage } from '@inertiajs/vue3';

import { normalize } from '../scripts/operator.ts';
const page = usePage();

const publicPath = import.meta.env.BASE_URL;
const getOperatorIcon = (operatorName) => `${publicPath}operatorIcons/${normalize(operatorName)}.png`;
const data = page.props.roles.sort((a, b) => a.name > b.name);
</script>

<template>
    <div>
        <Head>
            <title>Vocabulary</title>
        </Head>
        <div id="background-image" />
        <Navbar path="vocabulary" />
        <div id="container">
            <Logo text="Vocabulary" />
            <div id="section-container" class="vocabulary">
                <div class="section">
                    <span class="title">Roles</span>
                    <div id="role-container">
                        <div class="role" v-for="(role, index) in data">
                            <span class="name">{{ role.name }}</span>
                            <span class="definition">{{ role.definition }}</span>
                            <div class="operator-logos">
                                <div v-for="(operatorName, index) in role.operators" :key="index" class="small-icon">
                                    <img :src="getOperatorIcon(operatorName)" :alt="operatorName" />
                                    <p class="operator-name">{{ operatorName }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
@use '../../css/table.css';
@use '../../css/section.css';
@use '../../css/vocabulary.css';
</style>
