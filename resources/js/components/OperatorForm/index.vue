<!--
  Aesthetic: tactical dossier / military-brutalist.
  Display: GT America Compressed Bold (font-display). Body: FK Grotesk (font-sans).
  Dominant: near-black (#010101). Accent: signal-red (#ff4b3c).
  Memorable element: section panels with red-tinted header underline echoing
  the navbar's border — gives the form a classified-dossier register.
  Dark only — no dark: variants.
-->
<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import type { OperationOptionData, OperatorData } from '../../types/domain.ts';
import OperatorFormAssets from './OperatorFormAssets.vue';
import OperatorFormDescription from './OperatorFormDescription.vue';
import OperatorFormIdentity from './OperatorFormIdentity.vue';
import OperatorFormQueerIdentities from './OperatorFormQueerIdentities.vue';

const props = defineProps<{
    mode: 'create' | 'edit';
    submitRoute: string;
    squads: string[];
    operations: { data: OperationOptionData[] };
    queerIdentities: string[];
    roles: string[];
    operator?: OperatorData;
}>();

// Initialise from operator in edit mode; use empty defaults for create.
// Null-safe operation_id guards the runtime bug in the old OperatorForm.vue
// where `operations[0].id` was missing the `props.` prefix.
const form = useForm({
    id: props.operator?.id ?? undefined,
    name: props.operator?.name ?? '',
    description: props.operator?.description ?? '',
    squad: props.operator?.squad ?? 'Unaffiliated',
    side: props.operator?.side ?? 'Attack',
    icon: null as File | null,
    portrait: null as File | null,
    operation_id:
        props.operator?.operation?.id ?? props.operations.data[0]?.id ?? '',
    queerIdentities: props.operator?.queerIdentities ?? [],
    roles: props.operator?.roles ?? [],
});

function submit() {
    form.post(props.submitRoute);
}
</script>

<template>
    <form class="mx-auto max-w-2xl space-y-6 py-10" @submit.prevent="submit">
        <!-- Identity: name, side, squad -->
        <OperatorFormIdentity
            :model-value-name="form.name"
            :model-value-side="form.side"
            :model-value-squad="form.squad"
            :squads="props.squads"
            @update:model-value-name="form.name = $event"
            @update:model-value-side="form.side = $event"
            @update:model-value-squad="form.squad = $event"
        />

        <!-- Operation -->
        <section
            class="rounded-[6px] border border-[rgba(254,254,254,0.08)] bg-[rgba(17,17,17,0.55)]"
        >
            <header class="border-b border-[rgba(255,75,60,0.25)] px-5 py-3">
                <h2
                    class="font-display text-sm uppercase tracking-[0.04em] text-white"
                >
                    Operation
                </h2>
            </header>
            <div class="p-5">
                <label
                    for="op-operation"
                    class="mb-1.5 block font-mono text-[11px] uppercase tracking-[0.12em] text-[#b0bac6]"
                >
                    Operation
                </label>
                <select
                    id="op-operation"
                    v-model="form.operation_id"
                    class="w-full rounded-[4px] px-3 py-2"
                >
                    <option
                        v-for="op in props.operations.data"
                        :key="op.id"
                        :value="op.id"
                    >
                        {{ op.name }}
                    </option>
                </select>
            </div>
        </section>

        <!-- Roles -->
        <section
            class="rounded-[6px] border border-[rgba(254,254,254,0.08)] bg-[rgba(17,17,17,0.55)]"
        >
            <header class="border-b border-[rgba(255,75,60,0.25)] px-5 py-3">
                <h2
                    class="font-display text-sm uppercase tracking-[0.04em] text-white"
                >
                    Roles
                </h2>
            </header>
            <div class="p-5">
                <label
                    for="op-roles"
                    class="mb-1.5 block font-mono text-[11px] uppercase tracking-[0.12em] text-[#b0bac6]"
                >
                    Role(s)
                </label>
                <select
                    id="op-roles"
                    v-model="form.roles"
                    multiple
                    class="w-full rounded-[4px] px-3 py-2"
                >
                    <option v-for="r in props.roles" :key="r" :value="r">
                        {{ r }}
                    </option>
                </select>
            </div>
        </section>

        <!-- Secondary gadgets — full grid arrives in PR 3 -->
        <section
            class="rounded-[6px] border border-[rgba(254,254,254,0.08)] bg-[rgba(17,17,17,0.55)]"
        >
            <header class="border-b border-[rgba(255,75,60,0.25)] px-5 py-3">
                <h2
                    class="font-display text-sm uppercase tracking-[0.04em] text-white"
                >
                    Secondary gadgets
                </h2>
            </header>
            <div class="p-5">
                <p class="text-sm text-[#b0bac6]">
                    Gadget loadout management coming in a follow-up update.
                </p>
            </div>
        </section>

        <!-- Queer identities -->
        <OperatorFormQueerIdentities
            :model-value="form.queerIdentities"
            :queer-identities="props.queerIdentities"
            @update:model-value="form.queerIdentities = $event"
        />

        <!-- Description with live counter -->
        <OperatorFormDescription
            :model-value="form.description"
            @update:model-value="form.description = $event"
        />

        <!-- Assets: portrait + icon -->
        <OperatorFormAssets
            :mode="props.mode"
            @update:portrait="form.portrait = $event"
            @update:icon="form.icon = $event"
        />

        <!-- Error summary -->
        <ul
            v-if="Object.values(form.errors).length > 0"
            class="rounded-[5px] bg-[#f2a097] p-2.5 text-sm text-black"
        >
            <li v-for="message in Object.values(form.errors)" :key="message">
                {{ message }}
            </li>
        </ul>

        <!-- Bottom actions -->
        <div class="flex items-center justify-between pt-2">
            <button
                v-if="mode === 'edit'"
                type="button"
                class="font-display inline-flex cursor-pointer items-center gap-1.5 border-0 bg-transparent text-sm uppercase tracking-[0.04em] text-[#ff4b3c] hover:text-[#f8d002]"
            >
                Delete operator
            </button>
            <div :class="['flex gap-2', mode !== 'edit' ? 'ml-auto' : '']">
                <button type="submit" class="button-1">
                    {{ mode === 'create' ? 'Create operator' : 'Save changes' }}
                </button>
            </div>
        </div>
    </form>
</template>
