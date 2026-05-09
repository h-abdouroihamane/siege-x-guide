<!--
  Aesthetic: tactical dossier / military-brutalist.
  Display: GT America Compressed Bold (font-display).
  Body: FK Grotesk (font-sans). Accent: signal-red (#ff4b3c).
  Dark only — no dark: variants.
-->
<script setup lang="ts">
// Operation section — Reka UI Combobox with inline search.
// "Create new operation" opens OperatorFormOperationSheet (slide-in
// Dialog) so this file stays under the 250-line cap.
import {
    ComboboxAnchor,
    ComboboxContent,
    ComboboxInput,
    ComboboxItem,
    ComboboxRoot,
    Label,
} from 'reka-ui';
import { computed, ref } from 'vue';
import type { OperationOptionData } from '../../types/domain.ts';
import OperatorFormOperationSheet from './OperatorFormOperationSheet.vue';

const props = defineProps<{
    operationId: string;
    operations: { data: OperationOptionData[] };
}>();

const emit = defineEmits<{
    'update:operationId': [value: string];
    'operation-created': [value: OperationOptionData];
}>();

const query = ref('');
const sheetOpen = ref(false);
const sheetRef = ref<InstanceType<typeof OperatorFormOperationSheet> | null>(
    null,
);

const filtered = computed(() =>
    query.value.trim() === ''
        ? props.operations.data
        : props.operations.data.filter((op) =>
              op.name.toLowerCase().includes(query.value.trim().toLowerCase()),
          ),
);

// Display text for the currently selected operation.
const currentLabel = computed(() => {
    const op = props.operations.data.find((o) => o.id === props.operationId);
    return op ? `${op.id} - ${op.name}` : '';
});

function openSheet() {
    sheetRef.value?.resetForm();
    sheetOpen.value = true;
}

function onCreated(op: OperationOptionData) {
    emit('operation-created', op);
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
                Operation
            </h2>
        </header>

        <div class="space-y-3 p-5">
            <div>
                <Label
                    for="op-operation-input"
                    class="mb-1.5 block font-mono text-[11px] uppercase tracking-[0.12em] text-[#b0bac6]"
                >
                    Operation
                </Label>

                <ComboboxRoot
                    :model-value="operationId"
                    :display-value="() => currentLabel"
                    class="relative"
                    @update:model-value="
                        emit('update:operationId', $event as string)
                    "
                    @update:open="query = ''"
                >
                    <ComboboxAnchor
                        class="flex w-full items-center rounded-[4px] border border-[rgba(255,255,255,0.12)] bg-[rgba(255,255,255,0.04)] px-3 py-2 focus-within:outline-2 focus-within:outline-[#ff4b3c]"
                    >
                        <ComboboxInput
                            id="op-operation-input"
                            :placeholder="currentLabel || 'Search operations…'"
                            class="min-w-0 flex-1 bg-transparent font-sans text-sm text-white outline-none placeholder:text-[#b0bac6]"
                            @input="
                                query = ($event.target as HTMLInputElement)
                                    .value
                            "
                        />
                    </ComboboxAnchor>

                    <ComboboxContent
                        class="absolute left-0 right-0 top-full z-50 mt-1 max-h-60 overflow-y-auto rounded-[4px] border border-[rgba(255,255,255,0.12)] bg-[#111] py-1 shadow-lg"
                    >
                        <ComboboxItem
                            v-for="op in filtered"
                            :key="op.id"
                            :value="op.id"
                            class="cursor-pointer px-3 py-2 font-sans text-sm text-[#b0bac6] data-[highlighted]:bg-[rgba(255,75,60,0.15)] data-[highlighted]:text-white data-[state=checked]:text-[#ff4b3c]"
                        >
                            {{ op.id }} — {{ op.name }}
                        </ComboboxItem>
                        <div
                            v-if="filtered.length === 0"
                            role="option"
                            aria-disabled="true"
                            aria-live="polite"
                            class="px-3 py-2 font-mono text-[11px] uppercase tracking-widest text-[#b0bac6]"
                        >
                            No match
                        </div>
                    </ComboboxContent>
                </ComboboxRoot>
            </div>

            <button
                type="button"
                class="font-mono text-[11px] uppercase tracking-widest text-[#ff4b3c] hover:text-[#f8d002] focus-visible:outline-2 focus-visible:outline-[#ff4b3c]"
                @click="openSheet"
            >
                + Create new operation
            </button>
        </div>
    </section>

    <OperatorFormOperationSheet
        ref="sheetRef"
        :open="sheetOpen"
        @update:open="sheetOpen = $event"
        @created="onCreated"
    />
</template>
