<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
const props = defineProps([
    'operator',
    'squads',
    'submitRoute',
    'queerIdentities',
    'roles',
]);
const page = usePage();
const operator = props.operator;

const form = useForm({
    operationName: undefined,
    year: undefined,
    season: undefined,
    releaseDate: undefined,
    name: undefined,
    description: undefined,
    squad: 'Unaffiliated',
    side: 'Attack',
    icon: undefined,
    portrait: undefined,
    queerIdentities: [],
    roles: [],
});

function submit() {
    form.post(props.submitRoute);
}
</script>

<template>
    <form id="operator-form" @submit.prevent="submit">
        <div class="form-element">
            <label for="operationName">Operation name</label>
            <input
                type="text"
                id="operationName"
                v-model="form.operationName"
            />
        </div>

        <div class="row">
            <div class="form-element">
                <label for="year">Year</label>
                <input id="year" type="number" v-model="form.year" />
            </div>

            <div class="form-element">
                <label for="season">Season</label>
                <select id="season" name="season" v-model="form.season">
                    <option
                        v-for="seasonCount in [1, 2, 3, 4]"
                        :value="seasonCount"
                    >
                        {{ seasonCount }}
                    </option>
                </select>
            </div>
        </div>

        <div class="form-element">
            <label for="releaseDate">Release date</label>
            <input
                type="date"
                id="releaseDate"
                name="releaseDate"
                v-model="form.releaseDate"
            />
        </div>
        <div class="form-element">
            <label for="name">Name</label>
            <input id="name" type="text" v-model="form.name" />
        </div>
        <div class="form-element">
            <label for="description">Description</label>
            <textarea
                id="description"
                name="description"
                rows="5"
                cols="44"
                v-model="form.description"
            />
        </div>

        <div class="form-element">
            <label for="side">Side</label>
            <select id="side" v-model="form.side">
                <option v-for="side in ['Attack', 'Defense']" :value="side">
                    {{ side }}
                </option>
            </select>
        </div>

        <div class="form-element">
            <label for="roles">Role(s)</label>
            <select id="roles" multiple v-model="form.roles">
                <option v-for="r in props.roles" :value="r">{{ r }}</option>
            </select>
        </div>

        <div class="form-element">
            <label for="squad">Squad</label>
            <select id="squad" v-model="form.squad">
                <option v-for="squad in props.squads" :value="squad">
                    {{ squad }}
                </option>
            </select>
        </div>

        <div class="form-element">
            <label for="queer-identities">Queer identities</label>
            <select
                id="queer-identities"
                multiple
                v-model="form.queerIdentities"
            >
                <option v-for="q in props.queerIdentities" :value="q">
                    {{ q }}
                </option>
            </select>
        </div>

        <div class="form-element">
            <label for="portrait">Portrait</label>
            <input
                type="file"
                @input="form.portrait = $event.target.files[0]"
                id="portrait"
                name="portrait"
                accept="image/png"
            />
        </div>

        <div class="form-element">
            <label for="icon">Icon</label>
            <input
                type="file"
                @input="form.icon = $event.target.files[0]"
                id="icon"
                name="icon"
                accept="image/png"
            />
        </div>

        <button class="button-1" type="submit">Submit</button>

        <ul id="errors" v-if="Object.values(form.errors).length > 0">
            <li v-for="message in Object.values(form.errors)">{{ message }}</li>
        </ul>
    </form>
</template>
