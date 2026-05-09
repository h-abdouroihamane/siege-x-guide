<!--
  Aesthetic: tactical dossier / military-brutalist.
  Display: GT America Compressed Bold (font-display). Body: FK Grotesk.
  Accent: signal-red (#ff4b3c). Dark only.
-->
<script setup lang="ts">
// Operation, Roles, and Secondary gadgets sections.
// All three share the same panel visual language and will be
// upgraded in PR 3 (combobox for operation, gadget grid).
import type { OperationOptionData } from '../../types/domain.ts';

defineProps<{
    operationId: string;
    operations: { data: OperationOptionData[] };
    roles: string[];
    selectedRoles: string[];
}>();

const emit = defineEmits<{
    'update:operationId': [value: string];
    'update:selectedRoles': [value: string[]];
}>();
</script>

<template>
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
                :value="operationId"
                class="w-full rounded-[4px] px-3 py-2"
                @change="
                    emit(
                        'update:operationId',
                        ($event.target as HTMLSelectElement).value,
                    )
                "
            >
                <option
                    v-for="op in operations.data"
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
                :value="selectedRoles"
                multiple
                class="w-full rounded-[4px] px-3 py-2"
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
</template>
