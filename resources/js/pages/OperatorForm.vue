<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
const page = usePage();
const operator = page.props.operator.data;
const squads = page.props.squads;
console.log(operator);
const form = useForm({
    name: operator.name,
    description: operator.description,
    squad: operator.squad,
    side: operator.side,
    icon: null,
    portrait: null,
    year: operator.year,
    season: operator.season,
    operationName: null,
});
</script>

<template>
    <form>
        <div class="form-element">
            <label for="name">Name</label>
            <input id="name" type="text" v-model="form.name" />
        </div>
        <div class="form-element">
            <label for="description">Descriptiopn</label>
            <input id="description" type="text" v-model="form.description" />
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
                <option v-for="squad in squads" :value="squad">{{ squad }}</option>
            </select>
        </div>
    </form>
</template>
