<script setup>
import Logo from '@/components/Logo.vue';
import Navbar from '@/components/Navbar.vue';
import OperatorForm from '@/components/OperatorForm.vue';
import { usePage } from '@inertiajs/vue3';
const page = usePage();
const operator = page.props.operator.data;
const squads = page.props.squads;
const operations = page.props.operations.data;
</script>

<template>
    <div>
        <div id="background-image" />
        <Navbar path="admin" />
        <div id="container">
            <Logo text="Edit operator" />
            <OperatorForm :operator="operator" :squads="squads" :operations="operations" />
        </div>
    </div>
</template>
