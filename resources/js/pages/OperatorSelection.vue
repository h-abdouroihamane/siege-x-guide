<!--
  Aesthetic: tactical dossier / military-brutalist.
  Display: GT America Compressed Bold (font-display).
  Body: FK Grotesk (font-sans). Accent: signal-red (#ff4b3c).
  Dark only — no dark: variants.
-->
<script setup lang="ts">
import Navbar from '@/components/Navbar.vue';
import PageLayout from '@/components/PageLayout.vue';
import OperatorTile from '@/components/OperatorSelection/OperatorTile.vue';
import type { OperatorData } from '@/types/domain.ts';
import { Head, Link } from '@inertiajs/vue3';
import { Search } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { Operator } from '../scripts/operator.ts';

const props = defineProps<{
    operators: { data: OperatorData[] };
}>();

type Side = 'all' | 'attack' | 'defense';

interface OperatorEntry {
    id: string;
    operator: Operator;
}

const searchTerm = ref('');
const currentSide = ref<Side>('all');

// Operator class doesn't store id, so we pair raw id + instance.
const allEntries: OperatorEntry[] = props.operators.data.map((op) => ({
    id: op.id,
    operator: new Operator(
        op.name,
        op.description,
        op.side,
        op.year,
        op.season,
        op.operation.name,
        op.operation.release_date,
        op.roles,
        op.squad,
        op.queerIdentities,
        op.reworked,
    ),
}));

const filtered = computed(() => {
    const term = searchTerm.value.trim().toLowerCase();
    return allEntries.filter(({ operator: op }) => {
        // Side filter: resource emits 'Attack' / 'Defense' (capitalised)
        if (currentSide.value === 'attack' && !op.isAttacker()) return false;
        if (currentSide.value === 'defense' && !op.isDefender()) return false;
        // Name search — case-insensitive
        if (term && !op.name.toLowerCase().includes(term)) return false;
        return true;
    });
});

function setSide(side: Side) {
    currentSide.value = side;
}

function clearFilters() {
    searchTerm.value = '';
    currentSide.value = 'all';
}

function sideButtonClass(btn: Side): string {
    const modifier =
        btn === 'all' ? 'sort' : btn === 'attack' ? 'attackers' : 'defenders';
    const active = currentSide.value === btn ? 'active' : '';
    return ['radio-button', modifier, active].filter(Boolean).join(' ');
}
</script>

<template>
    <Head>
        <title>Select Operator — Edit</title>
    </Head>
    <Navbar path="admin" />
    <PageLayout>
        <!-- Page heading -->
        <header
            class="w-full border-b border-[rgba(255,75,60,0.2)] bg-[rgba(17,17,17,0.45)]"
        >
            <div
                class="mx-auto max-w-7xl px-6 py-5 flex items-end gap-3 flex-wrap"
            >
                <Link
                    :href="route('admin.dashboard')"
                    class="font-mono text-sm text-[#ff4b3c] no-underline hover:text-[#3cf0ff] focus-visible:outline-2 focus-visible:outline-[#ff4b3c] focus-visible:outline-offset-2"
                >
                    &larr; Admin dashboard
                </Link>
                <span class="text-[rgba(254,254,254,0.2)]">/</span>
                <h1 class="font-display text-3xl text-white">Edit operator</h1>
                <span
                    class="font-mono text-xs text-[#b0bac6] uppercase tracking-widest pb-1"
                >
                    Pick a target from the roster
                </span>
            </div>
        </header>

        <main class="w-full mx-auto max-w-7xl px-6 py-8">
            <!-- Filter bar -->
            <section
                class="mb-8 rounded-[6px] border border-[rgba(254,254,254,0.08)] bg-[rgba(17,17,17,0.55)] backdrop-blur-[2px]"
            >
                <div class="p-4 flex flex-wrap items-end gap-4">
                    <!-- Search input -->
                    <div class="flex-1 min-w-[220px]">
                        <label
                            for="op-search"
                            class="block mb-1.5 text-[#b0bac6] text-[11px] uppercase tracking-[0.12em] font-medium"
                        >
                            Search by name
                        </label>
                        <div class="relative">
                            <Search
                                class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-[#b0bac6] pointer-events-none"
                                aria-hidden="true"
                            />
                            <input
                                id="op-search"
                                v-model="searchTerm"
                                type="search"
                                placeholder="Thermite, Jäger, Nøkk…"
                                class="w-full rounded-[4px] border border-[rgba(254,254,254,0.4)] bg-[rgba(17,17,17,0.7)] py-2 pl-9 pr-3 text-[#fefefe] text-base focus-visible:outline-2 focus-visible:outline-[#ff4b3c] focus-visible:outline-offset-2"
                            />
                        </div>
                    </div>

                    <!-- Side toggle -->
                    <div>
                        <span
                            class="block mb-1.5 text-[#b0bac6] text-[11px] uppercase tracking-[0.12em] font-medium"
                            id="side-label"
                        >
                            Side
                        </span>
                        <div
                            class="inline-flex"
                            role="group"
                            aria-labelledby="side-label"
                        >
                            <!-- left: round only left corners -->
                            <button
                                type="button"
                                :class="[sideButtonClass('all'), 'left']"
                                :aria-pressed="currentSide === 'all'"
                                @click="setSide('all')"
                            >
                                All
                            </button>
                            <!-- middle: no corner radius -->
                            <button
                                type="button"
                                :class="[
                                    sideButtonClass('attack'),
                                    'rounded-none',
                                ]"
                                :aria-pressed="currentSide === 'attack'"
                                @click="setSide('attack')"
                            >
                                Attack
                            </button>
                            <!-- right: round only right corners -->
                            <button
                                type="button"
                                :class="[sideButtonClass('defense'), 'right']"
                                :aria-pressed="currentSide === 'defense'"
                                @click="setSide('defense')"
                            >
                                Defense
                            </button>
                        </div>
                    </div>

                    <!-- Result count -->
                    <div class="ml-auto pb-2" aria-live="polite">
                        <span
                            class="font-mono text-xs text-[#b0bac6] uppercase tracking-widest"
                        >
                            {{ filtered.length }} of
                            {{ allEntries.length }} operators
                        </span>
                    </div>
                </div>
            </section>

            <!-- Operator grid -->
            <section
                v-if="filtered.length > 0"
                class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4"
                aria-label="Operator roster"
            >
                <OperatorTile
                    v-for="entry in filtered"
                    :key="entry.operator.cleanName"
                    :operator="entry.operator"
                    :edit-href="route('operator.edit', entry.id)"
                />
            </section>

            <!-- Empty state -->
            <div
                v-else
                class="py-16 text-center"
                role="status"
                aria-live="polite"
            >
                <p class="font-display text-xl text-[#b0bac6] mb-1">
                    No operators match
                </p>
                <p
                    class="font-mono text-xs text-[#b0bac6] uppercase tracking-widest"
                >
                    Adjust your filters or search term
                </p>
                <button type="button" class="button-1" @click="clearFilters">
                    Clear filters
                </button>
            </div>
        </main>
    </PageLayout>
</template>
