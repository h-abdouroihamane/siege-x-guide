<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
const props = defineProps(['operator', 'squads', 'submitRoute', 'operations', 'queerIdentities']);
const page = usePage();
const operator = props.operator;
const squads = props.squads;
const operations = props.operations;

const form = useForm({
    id: operator.id,
    name: operator.name,
    description: operator.description,
    squad: operator.squad ?? '',
    side: operator.side,
    icon: null,
    portrait: null,
    operation_id: operator.operation.id ?? operations[0].id,
    queerIdentities: operator.queerIdentities ?? [],
});

function submit() {
    form.post(props.submitRoute);
    console.log('submitted');
    if (form.errors) {
        console.log(Object.entries(form.errors));
    }
}

console.log(`Submit Route = "${props.submitRoute}"`);
</script>

<template>
    <form id="operator-form" @submit.prevent="submit">
        <input type="hidden" name="id" :value="operator.id" />
        <div class="form-element">
            <label for="name">Name</label>
            <input id="name" type="text" v-model="form.name" />
            <div v-if="form.errors.name">{{ form.errors.name }}</div>
        </div>
        <div class="form-element">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="5" cols="44" v-model="form.description" />
            <div v-if="form.errors.description">{{ form.errors.description }}</div>
        </div>

        <div class="form-element">
            <label for="side">Side</label>
            <select id="side" v-model="form.side">
                <option v-for="side in ['Attack', 'Defense']" :value="side">{{ side }}</option>
            </select>
            <div v-if="form.errors.side">{{ form.errors.side }}</div>
        </div>

        <div class="form-element">
            <label for="squad">Squad</label>
            <select id="squad" v-model="form.squad">
                <option value="">None</option>
                <option v-for="squad in squads" :value="squad">{{ squad }}</option>
            </select>
            <div v-if="form.errors.squad">{{ form.errors.squad }}</div>
        </div>

        <div class="form-element">
            <label for="operation_id">Operation</label>
            <select id="operation_id" v-model="form.operation_id">
                <option v-for="op in operations" :value="op.id">{{ op.name }}</option>
            </select>
            <div v-if="form.errors.operation_id">{{ form.errors.operation_id }}</div>
        </div>

        <div class="form-element">
            <label for="queer-identities">Queer identities</label>
            <select id="queer-identities" multiple v-model="form.queerIdentities">
                <option v-for="q in props.queerIdentities" :value="q">{{ q }}</option>
            </select>
            <div v-if="form.errors.queerIdentities">{{ form.errors.queerIdentities }}</div>
        </div>

        <div class="form-element">
            <label for="portrait">Portrait</label>
            <input type="file" @input="form.portrait = $event.target.files[0]" id="portrait" name="portrait" accept="image/png" />
        </div>

        <div class="form-element">
            <label for="icon">Icon</label>
            <input type="file" @input="form.icon = $event.target.files[0]" id="icon" name="icon" accept="image/png" />
        </div>

        <button class="button-1" type="submit">Submit</button>

        <div id="errors" v-if="form.errors">
            <ul>
                <li v-for="message in Object.values(form.errors)">{{ message }}</li>
            </ul>
        </div>
    </form>
</template>
