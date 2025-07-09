<script setup>
import Logo from '@/components/Logo.vue';
import Navbar from '@/components/Navbar.vue';
import NewOperatorForm from '@/components/NewOperatorForm.vue';
import { usePage } from '@inertiajs/vue3';
const page = usePage();
</script>

<template>
    <div>
        <div id="background-image" />
        <Navbar path="admin" />
        <div id="container">
            <Logo text="Add operator" />
            <NewOperatorForm
                :squads="page.props.squads"
                :queer-identities="page.props.queerIdentities"
                :submit-route="page.props.submitRoute"
                :roles="page.props.roles"
            />
        </div>
    </div>
</template>
