<!--
  Aesthetic: tactical dossier / military-brutalist.
  Display: GT America Compressed Bold (font-display). Body: FK Grotesk (font-sans).
  Dominant color: near-black (#010101). Accent: signal-red (#ff4b3c).
-->
<script setup lang="ts">
// Identity section — name, side toggle, squad select.
// Side uses the .radio-button pattern from button.css (orange/blue tint,
// active glow). No dark: variants — dark-only palette throughout.
defineProps<{
    modelValueName: string;
    modelValueSide: string;
    modelValueSquad: string;
    squads: string[];
}>();

const emit = defineEmits<{
    'update:modelValueName': [value: string];
    'update:modelValueSide': [value: string];
    'update:modelValueSquad': [value: string];
}>();
</script>

<template>
    <section
        class="rounded-[6px] border border-[rgba(254,254,254,0.08)] bg-[rgba(17,17,17,0.55)]"
    >
        <header
            class="flex items-center justify-between border-b border-[rgba(255,75,60,0.25)] px-5 py-3"
        >
            <h2
                class="font-display text-sm uppercase tracking-[0.04em] text-white"
            >
                Identity
            </h2>
            <span
                class="font-mono text-[10px] uppercase tracking-widest text-[#b0bac6]"
            >
                REQUIRED
            </span>
        </header>

        <div class="space-y-4 p-5">
            <!-- Name -->
            <div>
                <label
                    for="op-name"
                    class="mb-1.5 block font-mono text-[11px] uppercase tracking-[0.12em] text-[#b0bac6]"
                >
                    Name
                </label>
                <input
                    id="op-name"
                    type="text"
                    :value="modelValueName"
                    class="w-full rounded-[4px] px-3 py-2"
                    autocomplete="off"
                    @input="
                        emit(
                            'update:modelValueName',
                            ($event.target as HTMLInputElement).value,
                        )
                    "
                />
            </div>

            <!-- Side toggle -->
            <fieldset>
                <legend
                    class="mb-2 font-mono text-[11px] uppercase tracking-[0.12em] text-[#b0bac6]"
                >
                    Side
                </legend>
                <div class="inline-flex">
                    <button
                        type="button"
                        :class="[
                            'radio-button left attackers',
                            modelValueSide === 'Attack' ? 'active' : '',
                        ]"
                        @click="emit('update:modelValueSide', 'Attack')"
                    >
                        Attack
                    </button>
                    <button
                        type="button"
                        :class="[
                            'radio-button right defenders',
                            modelValueSide === 'Defense' ? 'active' : '',
                        ]"
                        @click="emit('update:modelValueSide', 'Defense')"
                    >
                        Defense
                    </button>
                </div>
            </fieldset>

            <!-- Squad -->
            <div>
                <label
                    for="op-squad"
                    class="mb-1.5 block font-mono text-[11px] uppercase tracking-[0.12em] text-[#b0bac6]"
                >
                    Squad
                </label>
                <select
                    id="op-squad"
                    :value="modelValueSquad"
                    class="w-full rounded-[4px] px-3 py-2"
                    @change="
                        emit(
                            'update:modelValueSquad',
                            ($event.target as HTMLSelectElement).value,
                        )
                    "
                >
                    <option value="Unaffiliated">Unaffiliated</option>
                    <option v-for="squad in squads" :key="squad" :value="squad">
                        {{ squad }}
                    </option>
                </select>
            </div>
        </div>
    </section>
</template>
