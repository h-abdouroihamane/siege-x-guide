<!--
  Aesthetic: tactical dossier / military-brutalist.
  Display: GT America Compressed Bold (font-display).
  Body: FK Grotesk (font-sans). Accent: signal-red (#ff4b3c).
  Dark only — no dark: variants.
-->
<script setup lang="ts">
// Secondary gadgets section — 4-column toggle-button grid.
// Filters by the current side (Attack | Defense) so swapping sides
// swaps the visible tiles without clearing selection.
import { computed } from 'vue';
import type { SecondaryGadgetOptionData } from '../../types/domain.ts';

const publicPath = import.meta.env.BASE_URL;

const props = defineProps<{
    secondaryGadgets: { data: SecondaryGadgetOptionData[] };
    selectedIds: string[];
    side: string;
}>();

const emit = defineEmits<{
    'update:selectedIds': [value: string[]];
}>();

// Only show gadgets that match the current side.
const visible = computed(() =>
    props.secondaryGadgets.data.filter((g) => g.side === props.side),
);

const selectedCount = computed(
    () =>
        props.secondaryGadgets.data.filter((g) =>
            props.selectedIds.includes(g.id),
        ).length,
);

const sideLabel = computed(() =>
    props.side === 'Attack' ? 'attacker' : 'defender',
);

function gadgetSlug(name: string): string {
    return name.toLowerCase().replace(/ +/g, '-');
}

function toggle(id: string) {
    const current = [...props.selectedIds];
    const idx = current.indexOf(id);
    if (idx === -1) {
        current.push(id);
    } else {
        current.splice(idx, 1);
    }
    emit('update:selectedIds', current);
}
</script>

<template>
    <section
        class="rounded-[6px] border border-[rgba(254,254,254,0.08)] bg-[rgba(17,17,17,0.55)]"
    >
        <header class="border-b border-[rgba(255,75,60,0.25)] px-5 py-3">
            <h2
                class="font-display text-sm uppercase tracking-[0.04em] text-white"
            >
                Secondary gadgets
            </h2>
            <p class="mt-0.5 font-mono text-[10px] text-[#b0bac6]">
                Showing {{ sideLabel }} gadgets &middot;
                {{ selectedCount }} selected
            </p>
        </header>

        <div class="p-5">
            <div
                v-if="visible.length > 0"
                class="grid grid-cols-4 gap-2"
                role="group"
                aria-label="Secondary gadgets"
            >
                <button
                    v-for="gadget in visible"
                    :key="gadget.id"
                    type="button"
                    :aria-pressed="selectedIds.includes(gadget.id)"
                    :aria-label="gadget.name"
                    :class="[
                        'flex min-h-[44px] flex-col items-center',
                        'justify-center gap-1 rounded-[4px] px-1 py-2',
                        'transition-colors',
                        'focus-visible:outline-2',
                        'focus-visible:outline-[#ff4b3c]',
                        selectedIds.includes(gadget.id)
                            ? 'bg-[rgba(255,75,60,0.12)]' +
                              ' ring-2 ring-[#ff4b3c]'
                            : 'bg-[rgba(255,255,255,0.03)]',
                    ]"
                    @click="toggle(gadget.id)"
                >
                    <img
                        :src="`${publicPath}secondaryGadgets/${gadgetSlug(gadget.name)}.png`"
                        :alt="gadget.name"
                        :class="[
                            'h-8 w-auto mx-auto',
                            selectedIds.includes(gadget.id)
                                ? ''
                                : 'opacity-55 grayscale',
                        ]"
                    />
                    <span
                        :class="[
                            'font-mono text-[10px] uppercase',
                            'tracking-widest text-center leading-tight',
                            selectedIds.includes(gadget.id)
                                ? 'text-white'
                                : 'text-[#b0bac6]',
                        ]"
                    >
                        {{ gadget.name }}
                    </span>
                </button>
            </div>
            <p
                v-else
                class="font-mono text-[11px] uppercase tracking-widest text-[#b0bac6]"
            >
                No gadgets for this side.
            </p>
        </div>
    </section>
</template>
