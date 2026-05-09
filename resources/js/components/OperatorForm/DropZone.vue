<!--
  Aesthetic: tactical dossier / military-brutalist.
  Display: GT America Compressed Bold (font-display).
  Body: FK Grotesk (font-sans). Accent: signal-red (#ff4b3c).
  Dark only — no dark: variants.
-->
<script setup lang="ts">
// Reusable PNG drop-zone. State: empty | filled | error.
// File value owned by parent; local refs hold blob URL + UI state.
import { computed, onBeforeUnmount, ref, watch } from 'vue';
import { AlertCircle, Upload } from 'lucide-vue-next';

const props = defineProps<{
    label: string;
    dimensionHint: string;
    // Filled state: a newly picked File, or an existing URL (edit mode).
    currentFile: File | null;
    existingUrl: string | null;
    // Shown below zone in edit mode when no new file picked.
    existingFilename: string | null;
}>();

const emit = defineEmits<{
    'update:file': [file: File | null];
}>();

const fileInputRef = ref<HTMLInputElement | null>(null);
const isDragOver = ref(false);
const errorMessage = ref<string | null>(null);

// Blob URL lifecycle: revoke previous before replacing; revoke on unmount.
const blobUrl = ref<string | null>(null);
watch(
    () => props.currentFile,
    (file) => {
        if (blobUrl.value) URL.revokeObjectURL(blobUrl.value);
        blobUrl.value = file ? URL.createObjectURL(file) : null;
    },
    { immediate: true },
);
onBeforeUnmount(() => {
    if (blobUrl.value) URL.revokeObjectURL(blobUrl.value);
});

const previewSrc = computed<string | null>(
    () => blobUrl.value ?? props.existingUrl ?? null,
);

const isFilled = () => props.currentFile !== null || props.existingUrl !== null;

// Stable slug used for ARIA id (parameterised per zone instance).
const errorId = computed(
    () => `dropzone-${props.label.toLowerCase().replace(/\s+/g, '-')}-error`,
);

function validateAndEmit(file: File): void {
    // Defence-in-depth: require BOTH image/png MIME and .png extension.
    // OS picker filters via accept; server re-validates with mimes:png.
    const isPng =
        file.type === 'image/png' && file.name.toLowerCase().endsWith('.png');
    if (!isPng) {
        errorMessage.value = 'Only PNG files are accepted.';
        return;
    }
    errorMessage.value = null;
    emit('update:file', file);
}

// Border/background driven by state machine: filled > error > drag > idle.
const labelClasses = computed(() => {
    const base = [
        'relative flex h-44 cursor-pointer flex-col items-center',
        'justify-center overflow-hidden rounded-[4px] transition-all',
        'duration-120 focus-within:outline-2',
        'focus-within:outline-[#ff4b3c] focus-within:outline-offset-2',
    ];
    if (isFilled() && !errorMessage.value) {
        return [...base, 'border-2 border-[rgba(255,75,60,0.6)]'];
    }
    if (errorMessage.value) {
        return [...base, 'border-2 border-[#ff4b3c]'];
    }
    if (isDragOver.value) {
        return [...base, 'border-2 border-[#ff4b3c] bg-[rgba(255,75,60,0.05)]'];
    }
    return [
        ...base,
        'border-2 border-dashed border-[rgba(254,254,254,0.18)]',
        'bg-[rgba(17,17,17,0.4)] hover:border-[#ff4b3c]',
        'hover:bg-[rgba(255,75,60,0.05)]',
    ];
});

function onDragOver(event: DragEvent): void {
    event.preventDefault();
    isDragOver.value = true;
}

function onDragLeave(): void {
    isDragOver.value = false;
}

function onDrop(event: DragEvent): void {
    event.preventDefault();
    isDragOver.value = false;

    const items = event.dataTransfer?.items;
    if (items && items.length > 0) {
        const item = items[0];
        // Defence-in-depth: check both MIME and extension
        if (item.kind === 'file') {
            const file = item.getAsFile();
            if (file) {
                validateAndEmit(file);
                return;
            }
        }
    }

    const files = event.dataTransfer?.files;
    if (files && files.length > 0) {
        validateAndEmit(files[0]);
    }
}

function onInputChange(event: Event): void {
    const input = event.target as HTMLInputElement;
    if (input.files?.[0]) {
        validateAndEmit(input.files[0]);
    }
}

function clearFile(event: MouseEvent): void {
    event.preventDefault();
    event.stopPropagation();
    errorMessage.value = null;
    emit('update:file', null);
    if (fileInputRef.value) {
        fileInputRef.value.value = '';
    }
}

function focusInput(): void {
    fileInputRef.value?.click();
}
</script>

<template>
    <div>
        <!-- Field label (sits above zone) -->
        <span
            class="mb-2 block font-mono text-[11px] uppercase tracking-[0.12em] text-[#b0bac6]"
        >
            {{ props.label }}
        </span>

        <!-- <label> wraps the hidden <input>: keyboard + click target. -->
        <label
            :class="labelClasses"
            @dragover="onDragOver"
            @dragleave="onDragLeave"
            @drop="onDrop"
        >
            <input
                ref="fileInputRef"
                type="file"
                accept="image/png"
                class="sr-only"
                :aria-label="`Upload ${props.label} PNG`"
                :aria-describedby="errorMessage ? errorId : undefined"
                :aria-invalid="errorMessage ? 'true' : undefined"
                @change="onInputChange"
            />

            <!-- Filled state: thumbnail + remove button + filename -->
            <template v-if="isFilled() && !errorMessage">
                <img
                    :src="previewSrc ?? undefined"
                    :alt="`${props.label} preview`"
                    class="h-full w-full object-cover"
                />
                <button
                    type="button"
                    aria-label="Remove file"
                    class="absolute top-1.5 right-1.5 flex h-6 w-6 items-center justify-center rounded-full border border-[rgba(254,254,254,0.18)] bg-[rgba(1,1,1,0.7)] text-[#b0bac6] hover:text-[#ff4b3c] focus-visible:outline-2 focus-visible:outline-[#ff4b3c]"
                    @click="clearFile"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
                <!-- Filename — bottom-left -->
                <span
                    v-if="props.currentFile"
                    class="absolute bottom-1.5 left-1.5 font-mono text-[10px] uppercase text-[#b0bac6]"
                >
                    {{ props.currentFile.name }}
                </span>
            </template>

            <!-- Error state: AlertCircle icon + message.
                 role="alert" announces immediately to assistive tech;
                 the input above also points here via aria-describedby. -->
            <template v-else-if="errorMessage">
                <AlertCircle
                    class="mb-2 h-7 w-7 text-[#ff4b3c]"
                    aria-hidden="true"
                />
                <span
                    :id="errorId"
                    role="alert"
                    class="px-4 text-center text-xs text-[#ff4b3c]"
                >
                    {{ errorMessage }}
                </span>
            </template>

            <!-- Empty state: Upload icon + hint text -->
            <template v-else>
                <Upload
                    class="mb-2 h-7 w-7 text-[#b0bac6]"
                    aria-hidden="true"
                />
                <span class="text-xs text-[#b0bac6]">
                    Drag PNG here or click
                </span>
                <span
                    class="mt-1 font-mono text-[10px] text-[rgba(254,254,254,0.3)]"
                >
                    {{ props.dimensionHint }}
                </span>
            </template>
        </label>

        <!-- Edit-mode sub-label when an existing image is shown unchanged. -->
        <p
            v-if="props.existingFilename && !props.currentFile"
            class="mt-1.5 font-mono text-[10px] text-[#b0bac6]"
        >
            Currently {{ props.existingFilename }} ·
            <button
                type="button"
                class="text-[#ff4b3c] underline underline-offset-2 hover:text-[#3cf0ff] focus-visible:outline-2 focus-visible:outline-[#ff4b3c]"
                @click="focusInput"
            >
                replace
            </button>
        </p>
    </div>
</template>
