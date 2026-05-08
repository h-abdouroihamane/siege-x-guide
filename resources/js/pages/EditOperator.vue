<script setup lang="ts">
import Logo from '@/components/Logo.vue';
import Navbar from '@/components/Navbar.vue';
import OperatorForm from '@/components/OperatorForm.vue';
import { Head, usePage } from '@inertiajs/vue3';
const page = usePage();
</script>

<template>
    <div>
        <Head>
            <title>Edit Operator</title>
        </Head>
        <div id="background-image" />
        <Navbar path="admin" />
        <div id="container">
            <Logo text="Edit operator" />
            <OperatorForm
                :operator="page.props.operator.data"
                :squads="page.props.squads"
                :operations="page.props.operations"
                :queer-identities="page.props.queerIdentities"
                :submit-route="page.props.submitRoute"
                :roles="page.props.roles"
            />
        </div>
    </div>
</template>
