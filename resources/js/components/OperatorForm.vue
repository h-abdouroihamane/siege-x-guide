<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
const props = defineProps(['operator', 'squads', 'operations']);
const page = usePage();
const operator = props.operator;
const squads = props.squads;
const operations = props.operations;

const form = useForm({
    name: operator.name,
    description: operator.description,
    squad: operator.squad ?? '',
    side: operator.side,
    icon: null,
    portrait: null,
    operation_id: operator.operation.id ?? operations[0].id,
});
</script>

<template>
    <form id="operator-form">
        <div class="form-element">
            <label for="name">Name</label>
            <input id="name" type="text" v-model="form.name" />
        </div>
        <div class="form-element">
            <label for="description">Description</label>
            <textarea id="description" name="story" rows="5" cols="44" v-model="form.description" />
        </div>

        <div class="form-element">
            <label for="side">Side</label>
            <select id="side" v-model="form.side">
                <option v-for="side in ['Attack', 'Defense']" :value="side">{{ side }}</option>
            </select>
        </div>

        <div class="form-element">
            <label for="squad">Squad</label>
            <select id="squad" v-model="form.squad">
                <option value="">None</option>
                <option v-for="squad in squads" :value="squad">{{ squad }}</option>
            </select>
        </div>

        <div class="form-element">
            <label for="operation">Operation</label>
            <select id="operation" v-model="form.operation_id">
                <option v-for="op in operations" :value="op.id">{{ op.name }}</option>
            </select>
        </div>
    </form>
</template>
