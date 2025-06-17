<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

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
    operationName: operator.operation_name,
});

const newSeason = ref(false);
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

        <div class="form-element">
            <label for="year">Year</label>
            <input type="number" id="year" min="0" max="99" v-model="form.year" />
        </div>

        <div class="form-element">
            <label for="season">Season</label>
            <input type="number" id="season" min="0" max="4" v-model="form.season" />
        </div>

        <div class="form-element">
            <label for="operation-name">Operation name</label>
            <input id="operation-name" type="text" v-model="form.operationName" />
        </div>
    </form>
</template>
