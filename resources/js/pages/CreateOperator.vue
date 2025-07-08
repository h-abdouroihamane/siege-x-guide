<script setup>
import Logo from '@/components/Logo.vue';
import Navbar from '@/components/Navbar.vue';
import NewOperatorForm from '@/components/NewOperatorForm.vue';
import { usePage } from '@inertiajs/vue3';
const page = usePage();
const squads = page.props.squads;
const queerIdentities = page.props.queerIdentities;
const submitRoute = page.props.submitRoute;
</script>

<template>
    <div>
        <div id="background-image" />
        <Navbar path="admin" />
        <div id="container">
            <Logo text="Add operator" />
            <NewOperatorForm :squads="squads" :queer-identities="queerIdentities" :submit-route="submitRoute" />
        </div>
    </div>
</template>
