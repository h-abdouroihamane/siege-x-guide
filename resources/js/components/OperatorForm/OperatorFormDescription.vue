<!--
  Aesthetic: tactical dossier / military-brutalist.
  Display: GT America Compressed Bold (font-display).
  Body: FK Grotesk (font-sans). Mono: Simplon Mono (font-mono).
  Accent: signal-red (#ff4b3c). Dark only — no dark: variants.
-->
<script setup lang="ts">
// Description panel — textarea with live character counter.
// Counter shifts from muted (#b0bac6) → warning (#f8d002) at 225
// chars, then to danger (#ff4b3c) at the 250 cap.
import { computed } from 'vue';

const props = defineProps<{
    modelValue: string;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const charCount = computed(() => props.modelValue?.length ?? 0);

const counterColor = computed(() => {
    if (charCount.value >= 250) return '#ff4b3c';
    if (charCount.value >= 225) return '#f8d002';
    return '#b0bac6';
});
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
                Description
            </h2>
            <span
                class="font-mono text-[10px] uppercase tracking-widest text-[#b0bac6]"
            >
                Max 250 chars
            </span>
        </header>
        <div class="p-5">
            <label
                for="op-description"
                class="mb-1.5 block font-mono text-[11px] uppercase tracking-[0.12em] text-[#b0bac6]"
            >
                Description
            </label>
            <textarea
                id="op-description"
                :value="modelValue"
                rows="4"
                maxlength="250"
                class="w-full resize-none rounded-[4px] px-3 py-2"
                @input="
                    emit(
                        'update:modelValue',
                        ($event.target as HTMLTextAreaElement).value,
                    )
                "
            />
            <div class="mt-1.5 flex justify-end">
                <span
                    class="font-mono text-[10px]"
                    :style="{ color: counterColor }"
                >
                    {{ charCount }} / 250
                </span>
            </div>
        </div>
    </section>
</template>
