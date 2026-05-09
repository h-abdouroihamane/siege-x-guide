<!--
  Aesthetic: tactical dossier / military-brutalist.
  Display: GT America Compressed Bold (font-display).
  Body: FK Grotesk (font-sans). Mono: Simplon Mono (font-mono).
  Accent: signal-red (#ff4b3c). Dark only — no dark: variants.
-->
<script setup lang="ts">
// Queer identities panel — checkbox grid.
// Uses a local copy of the selection to drive v-model on checkboxes,
// emitting the updated array on each change.
const props = defineProps<{
    modelValue: string[];
    queerIdentities: string[];
}>();

const emit = defineEmits<{
    'update:modelValue': [value: string[]];
}>();

function onCheck(identity: string, checked: boolean) {
    const current = [...props.modelValue];
    if (checked) {
        if (!current.includes(identity)) current.push(identity);
    } else {
        const idx = current.indexOf(identity);
        if (idx !== -1) current.splice(idx, 1);
    }
    emit('update:modelValue', current);
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
                Queer identities
            </h2>
        </header>
        <div class="grid grid-cols-2 gap-3 p-5">
            <label
                v-for="q in queerIdentities"
                :key="q"
                class="flex cursor-pointer items-center gap-2.5 text-sm text-[#b0bac6]"
            >
                <input
                    type="checkbox"
                    :value="q"
                    :checked="modelValue.includes(q)"
                    class="accent-[#ff4b3c]"
                    @change="
                        onCheck(q, ($event.target as HTMLInputElement).checked)
                    "
                />
                {{ q }}
            </label>
        </div>
    </section>
</template>
