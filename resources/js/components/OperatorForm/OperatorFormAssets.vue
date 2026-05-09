<!--
  Aesthetic: tactical dossier / military-brutalist.
  Display: GT America Compressed Bold. Body: FK Grotesk.
  Accent: signal-red (#ff4b3c). Dark only.
-->
<script setup lang="ts">
// Assets section — portrait and icon file inputs.
// File inputs use @input (not v-model) per codebase convention.
// In edit mode the section hint reads "OPTIONAL ON EDIT";
// in create mode it reads "REQUIRED".
defineProps<{
    mode: 'create' | 'edit';
}>();

const emit = defineEmits<{
    'update:portrait': [file: File];
    'update:icon': [file: File];
}>();

function onPortraitInput(event: Event) {
    const input = event.target as HTMLInputElement;
    if (input.files?.[0]) {
        emit('update:portrait', input.files[0]);
    }
}

function onIconInput(event: Event) {
    const input = event.target as HTMLInputElement;
    if (input.files?.[0]) {
        emit('update:icon', input.files[0]);
    }
}
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
                Assets
            </h2>
            <span
                class="font-mono text-[10px] uppercase tracking-widest text-[#b0bac6]"
            >
                {{ mode === 'create' ? 'REQUIRED' : 'OPTIONAL ON EDIT' }}
            </span>
        </header>

        <div class="grid grid-cols-2 gap-4 p-5">
            <!-- Portrait -->
            <div>
                <label
                    for="op-portrait"
                    class="mb-1.5 block font-mono text-[11px] uppercase tracking-[0.12em] text-[#b0bac6]"
                >
                    Portrait
                </label>
                <input
                    id="op-portrait"
                    type="file"
                    name="portrait"
                    accept="image/png"
                    class="w-full rounded-[4px] px-3 py-2 text-sm"
                    @input="onPortraitInput"
                />
                <p
                    class="mt-1 font-mono text-[10px] text-[rgba(254,254,254,0.35)]"
                >
                    PNG · 512 × 720 recommended
                </p>
            </div>

            <!-- Icon -->
            <div>
                <label
                    for="op-icon"
                    class="mb-1.5 block font-mono text-[11px] uppercase tracking-[0.12em] text-[#b0bac6]"
                >
                    Icon
                </label>
                <input
                    id="op-icon"
                    type="file"
                    name="icon"
                    accept="image/png"
                    class="w-full rounded-[4px] px-3 py-2 text-sm"
                    @input="onIconInput"
                />
                <p
                    class="mt-1 font-mono text-[10px] text-[rgba(254,254,254,0.35)]"
                >
                    PNG · square recommended
                </p>
            </div>
        </div>
    </section>
</template>
