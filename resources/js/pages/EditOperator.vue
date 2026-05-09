<script setup lang="ts">
import Logo from '@/components/Logo.vue';
import Navbar from '@/components/Navbar.vue';
import OperatorForm from '@/components/OperatorForm/index.vue';
import PageLayout from '@/components/PageLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <Head>
        <title>Edit Operator</title>
    </Head>
    <Navbar path="admin" />
    <PageLayout>
        <Logo text="Edit operator" />
        <OperatorForm
            mode="edit"
            :operator="page.props.operator.data"
            :squads="page.props.squads"
            :operations="page.props.operations"
            :queer-identities="page.props.queerIdentities"
            :submit-route="page.props.submitRoute"
            :roles="page.props.roles"
        />
    </PageLayout>
</template>
