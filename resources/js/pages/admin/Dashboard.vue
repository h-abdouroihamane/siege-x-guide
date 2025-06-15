<template>
    <div>
        <Navbar path="admin" />
        <ul>
            <li><a href="/admin/new">New operator</a></li>
            <li><a :href="editRoute">Edit operator</a></li>
        </ul>
    </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import Navbar from '../../components/Navbar.vue';

const page = usePage();
const editRoute = page.props.editRoute;
</script>

<style lang="scss" scoped></style>
