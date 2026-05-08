<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import type { OperationOptionData, OperatorData } from '../types/domain.ts';

// TODO(wave-3): OperatorData does not include `id`; the form reads
// operator.id (line 15) and the hidden input (line 34) will be
// undefined at runtime. OperatorResource.php should expose it.
const props = defineProps<{
    operator: OperatorData;
    squads: string[];
    submitRoute: string;
    operations: { data: OperationOptionData[] };
    queerIdentities: string[];
    roles: string[];
}>();
const operator = props.operator;

const form = useForm({
    id: operator.id,
    name: operator.name,
    description: operator.description,
    squad: operator.squad ?? 'Unaffiliated',
    side: operator.side,
    icon: null,
    portrait: null,
    operation_id: operator.operation.id ?? operations[0].id,
    queerIdentities: operator.queerIdentities ?? [],
    roles: operator.roles ?? [],
});

function submit() {
    form.post(props.submitRoute);
}
</script>

<template>
    <form
        id="operator-form"
        class="flex flex-col gap-y-2.5"
        @submit.prevent="submit"
    >
        <input type="hidden" name="id" :value="operator.id" />
        <div class="flex flex-col justify-evenly">
            <label for="name">Name</label>
            <input id="name" type="text" v-model="form.name" />
        </div>
        <div class="flex flex-col justify-evenly">
            <label for="description">Description</label>
            <textarea
                id="description"
                name="description"
                rows="5"
                cols="44"
                v-model="form.description"
            />
        </div>

        <div class="flex flex-col justify-evenly">
            <label for="side">Side</label>
            <select id="side" v-model="form.side">
                <option
                    v-for="side in ['Attack', 'Defense']"
                    :key="side"
                    :value="side"
                >
                    {{ side }}
                </option>
            </select>
        </div>

        <div class="flex flex-col justify-evenly">
            <label for="roles">Role(s)</label>
            <select id="roles" multiple v-model="form.roles">
                <option v-for="r in props.roles" :key="r" :value="r">
                    {{ r }}
                </option>
            </select>
        </div>

        <div class="flex flex-col justify-evenly">
            <label for="squad">Squad</label>
            <select id="squad" v-model="form.squad">
                <option value="None">None</option>
                <option
                    v-for="squad in props.squads"
                    :key="squad"
                    :value="squad"
                >
                    {{ squad }}
                </option>
            </select>
        </div>

        <div class="flex flex-col justify-evenly">
            <label for="operation_id">Operation</label>
            <select id="operation_id" v-model="form.operation_id">
                <option
                    v-for="op in props.operations.data"
                    :key="op.id"
                    :value="op.id"
                >
                    {{ op.name }}
                </option>
            </select>
        </div>

        <div class="flex flex-col justify-evenly">
            <label for="queer-identities">Queer identities</label>
            <select
                id="queer-identities"
                multiple
                v-model="form.queerIdentities"
            >
                <option v-for="q in props.queerIdentities" :key="q" :value="q">
                    {{ q }}
                </option>
            </select>
        </div>

        <div class="flex flex-col justify-evenly">
            <label for="portrait">Portrait</label>
            <input
                type="file"
                @input="form.portrait = $event.target.files[0]"
                id="portrait"
                name="portrait"
                accept="image/png"
            />
        </div>

        <div class="flex flex-col justify-evenly">
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

        <ul
            v-if="Object.values(form.errors).length > 0"
            class="list-none rounded-[5px] bg-[#f2a097] p-2.5 text-black"
        >
            <li v-for="message in Object.values(form.errors)" :key="message">
                {{ message }}
            </li>
        </ul>
    </form>
</template>
