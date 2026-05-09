<!--
  Aesthetic: tactical dossier / military-brutalist.
  Display: GT America Compressed Bold (font-display).
  Body: FK Grotesk (font-sans). Accent: signal-red (#ff4b3c).
  Dark only — no dark: variants.
  Local to OperatorSelection — not a shared component.
-->
<script setup lang="ts">
import type { Operator } from '../../scripts/operator.ts';
import { Link } from '@inertiajs/vue3';
import { Pencil } from 'lucide-vue-next';

const props = defineProps<{
    operator: Operator;
    editHref: string;
}>();
</script>

<template>
    <Link
        :href="props.editHref"
        :class="[
            'group relative block w-full overflow-hidden rounded-[2px]',
            'border border-[rgba(254,254,254,0.06)] bg-[rgba(17,17,17,0.5)]',
            'transition-all duration-200',
            'focus-visible:outline-2 focus-visible:outline-[#ff4b3c]',
            'focus-visible:outline-offset-2',
            props.operator.isAttacker() &&
                'attacker hover:shadow-[0_0_14px_1px_#d9610f] hover:border-[#d9610f] hover:-translate-y-0.5',
            props.operator.isDefender() &&
                'defender hover:shadow-[0_0_14px_1px_#0e87c8] hover:border-[#0e87c8] hover:-translate-y-0.5',
        ]"
        :aria-label="`Edit ${props.operator.name}`"
    >
        <!-- Portrait (3:4 aspect ratio) -->
        <div class="relative aspect-[3/4] overflow-hidden bg-[#111]">
            <img
                class="h-full w-full object-cover"
                :src="props.operator.portrait"
                :alt="props.operator.name"
            />

            <!-- Pride flag pips — top-right, non-color-only (aria-label) -->
            <div
                v-if="props.operator.queerIdentities?.length"
                class="absolute top-1.5 right-1.5 flex flex-col gap-0.5"
                aria-hidden="true"
            >
                <span
                    v-for="identity in props.operator.queerIdentities"
                    :key="identity"
                    :class="`pride-flag ${identity.toLowerCase()}`"
                    :title="identity"
                />
            </div>

            <!-- Squad chip — bottom-left of portrait -->
            <span
                class="absolute bottom-0.5 left-1 font-mono text-[9px] uppercase tracking-[0.08em] text-[rgba(255,255,255,0.85)] bg-[rgba(0,0,0,0.5)] px-1 py-px rounded-[2px]"
            >
                {{ props.operator.squad }}
            </span>

            <!-- Edit overlay — fades in on group-hover -->
            <div
                class="absolute inset-0 flex items-center justify-center bg-[rgba(0,0,0,0.55)] opacity-0 transition-opacity duration-200 group-hover:opacity-100 pointer-events-none"
                aria-hidden="true"
            >
                <span
                    class="inline-flex items-center gap-1 rounded-[4px] bg-[#ff4b3c] px-3 py-1.5 font-display text-[12px] font-bold uppercase tracking-[0.06em] text-[#010101]"
                >
                    <Pencil class="h-3.5 w-3.5" aria-hidden="true" />
                    Edit
                </span>
            </div>
        </div>

        <!-- Operator name strip -->
        <div
            class="bg-[rgba(17,17,17,0.5)] px-1.5 py-1 text-center backdrop-blur-[2px]"
        >
            <span
                class="font-display text-sm uppercase text-white leading-snug"
            >
                {{ props.operator.name }}
            </span>
        </div>
    </Link>
</template>
