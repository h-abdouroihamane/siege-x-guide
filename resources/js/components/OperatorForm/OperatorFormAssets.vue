<!--
  Aesthetic: tactical dossier / military-brutalist.
  Display: GT America Compressed Bold. Body: FK Grotesk.
  Accent: signal-red (#ff4b3c). Dark only.
-->
<script setup lang="ts">
// Assets section — portrait and icon drop-zones.
// File values are owned by the parent (useForm in index.vue).
// This component surfaces the drop-zone UI and emits changes up.
// In edit mode, derives existing image URLs from operator name.
import { computed } from 'vue';
import { normalize } from '../../scripts/operator.ts';
import DropZone from './DropZone.vue';

const publicPath = import.meta.env.BASE_URL;

const props = defineProps<{
    mode: 'create' | 'edit';
    portrait: File | null;
    icon: File | null;
    // Operator name from the form (used to derive existing image URL in edit)
    operatorName: string;
}>();

const emit = defineEmits<{
    'update:portrait': [file: File | null];
    'update:icon': [file: File | null];
}>();

// Derive existing asset URLs from the operator's clean name.
// Matches the URL contract in operator.ts:54–56.
const cleanName = computed(() => normalize(props.operatorName));

const existingPortraitUrl = computed(() =>
    props.mode === 'edit' && props.operatorName
        ? `${publicPath}operatorPortraits/${cleanName.value}.png`
        : null,
);

const existingIconUrl = computed(() =>
    props.mode === 'edit' && props.operatorName
        ? `${publicPath}operatorIcons/${cleanName.value}.png`
        : null,
);

const existingPortraitFilename = computed(() =>
    props.mode === 'edit' && cleanName.value ? `${cleanName.value}.png` : null,
);

const existingIconFilename = computed(() =>
    props.mode === 'edit' && cleanName.value ? `${cleanName.value}.png` : null,
);
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
            <!-- Portrait drop-zone -->
            <DropZone
                label="Portrait"
                dimension-hint="300 × 500 max"
                :current-file="props.portrait"
                :existing-url="existingPortraitUrl"
                :existing-filename="existingPortraitFilename"
                @update:file="emit('update:portrait', $event)"
            />

            <!-- Icon drop-zone -->
            <DropZone
                label="Icon"
                dimension-hint="250 × 250 max"
                :current-file="props.icon"
                :existing-url="existingIconUrl"
                :existing-filename="existingIconFilename"
                @update:file="emit('update:icon', $event)"
            />
        </div>
    </section>
</template>
