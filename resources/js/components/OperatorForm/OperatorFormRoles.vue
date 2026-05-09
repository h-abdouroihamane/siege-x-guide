<!--
  Aesthetic: tactical dossier / military-brutalist.
  Display: GT America Compressed Bold (font-display).
  Body: FK Grotesk (font-sans). Accent: signal-red (#ff4b3c).
  Dark only — no dark: variants.
-->
<script setup lang="ts">
// Roles section — multi-select extracted from OperatorFormReleaseRoles.
defineProps<{
    roles: string[];
    selectedRoles: string[];
}>();

const emit = defineEmits<{
    'update:selectedRoles': [value: string[]];
}>();
</script>

<template>
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
                :value="selectedRoles"
                multiple
                class="w-full rounded-[4px] px-3 py-2 focus-visible:outline-2 focus-visible:outline-[#ff4b3c]"
                @change="
                    emit(
                        'update:selectedRoles',
                        Array.from(
                            ($event.target as HTMLSelectElement)
                                .selectedOptions,
                        ).map((o) => o.value),
                    )
                "
            >
                <option v-for="r in roles" :key="r" :value="r">
                    {{ r }}
                </option>
            </select>
        </div>
    </section>
</template>
