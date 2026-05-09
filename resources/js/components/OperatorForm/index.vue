<!--
  Aesthetic: tactical dossier / military-brutalist.
  Display: GT America Compressed Bold (font-display). Body: FK Grotesk (font-sans).
  Dominant: near-black (#010101). Accent: signal-red (#ff4b3c).
  Memorable element: section panels with red-tinted header underline echoing
  the navbar's border — gives the form a classified-dossier register.
  Dark only — no dark: variants.
-->
<script setup lang="ts">
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { normalize } from '../../scripts/operator.ts';
import type { OperationOptionData, OperatorData } from '../../types/domain.ts';
import OperatorFormAssets from './OperatorFormAssets.vue';
import OperatorFormDescription from './OperatorFormDescription.vue';
import OperatorFormIdentity from './OperatorFormIdentity.vue';
import OperatorFormPreview from './OperatorFormPreview.vue';
import OperatorFormQueerIdentities from './OperatorFormQueerIdentities.vue';
import OperatorFormReleaseRoles from './OperatorFormReleaseRoles.vue';

const publicPath = import.meta.env.BASE_URL;

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

// Duck-typed operator object for OperatorCard.
// Option (a): build a plain object matching only what OperatorCard reads
// (cleanName, portrait, icon, name, queerIdentities, isAttacker, isDefender).
// Avoids the positional Operator constructor while staying fully reactive.
const livePreviewOperator = computed(() => {
    const clean = normalize(form.name || 'Operator');
    const portraitSrc = form.portrait
        ? URL.createObjectURL(form.portrait)
        : props.mode === 'edit' && props.operator?.name
          ? `${publicPath}operatorPortraits/${normalize(props.operator.name)}.png`
          : 'https://placehold.co/300x500';
    const iconSrc = form.icon
        ? URL.createObjectURL(form.icon)
        : props.mode === 'edit' && props.operator?.name
          ? `${publicPath}operatorIcons/${normalize(props.operator.name)}.png`
          : 'https://placehold.co/60x60/000000/FFF';

    return {
        name: form.name || 'Operator',
        cleanName: clean,
        portrait: portraitSrc,
        icon: iconSrc,
        queerIdentities: form.queerIdentities ?? [],
        isAttacker: () => form.side === 'Attack',
        isDefender: () => form.side === 'Defense',
    };
});

function submit() {
    form.post(props.submitRoute);
}
</script>

<template>
    <div class="mx-auto max-w-6xl px-6 py-10">
        <div class="flex flex-col gap-8 lg:grid lg:grid-cols-[1fr_320px]">
            <!-- Left column: form sections -->
            <form class="space-y-6" @submit.prevent="submit">
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

                <!-- Operation, Roles, Secondary gadgets -->
                <OperatorFormReleaseRoles
                    :operation-id="form.operation_id"
                    :operations="props.operations"
                    :roles="props.roles"
                    :selected-roles="form.roles"
                    @update:operation-id="form.operation_id = $event"
                    @update:selected-roles="form.roles = $event"
                />

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

                <!-- Assets: portrait + icon drop-zones -->
                <OperatorFormAssets
                    :mode="props.mode"
                    :portrait="form.portrait"
                    :icon="form.icon"
                    :operator-name="form.name"
                    @update:portrait="form.portrait = $event"
                    @update:icon="form.icon = $event"
                />

                <!-- Error summary -->
                <ul
                    v-if="Object.values(form.errors).length > 0"
                    class="rounded-[5px] bg-[#f2a097] p-2.5 text-sm text-black"
                >
                    <li
                        v-for="message in Object.values(form.errors)"
                        :key="message"
                    >
                        {{ message }}
                    </li>
                </ul>

                <!-- Bottom actions -->
                <div class="flex items-center justify-between pt-2">
                    <button
                        v-if="mode === 'edit'"
                        type="button"
                        :class="[
                            'font-display inline-flex cursor-pointer items-center',
                            'gap-1.5 border-0 bg-transparent text-sm uppercase',
                            'tracking-[0.04em] text-[#ff4b3c] hover:text-[#f8d002]',
                        ]"
                    >
                        Delete operator
                    </button>
                    <div
                        :class="[
                            'flex gap-2',
                            mode !== 'edit' ? 'ml-auto' : '',
                        ]"
                    >
                        <button type="submit" class="button-1">
                            {{
                                mode === 'create'
                                    ? 'Create operator'
                                    : 'Save changes'
                            }}
                        </button>
                    </div>
                </div>
            </form>

            <!-- Right column: sticky live preview (lg+ only) -->
            <aside class="hidden lg:block">
                <OperatorFormPreview :operator="livePreviewOperator" />
            </aside>
        </div>
    </div>
</template>
