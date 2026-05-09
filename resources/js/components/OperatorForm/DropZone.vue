<!--
  Aesthetic: tactical dossier / military-brutalist.
  Display: GT America Compressed Bold (font-display).
  Body: FK Grotesk (font-sans). Accent: signal-red (#ff4b3c).
  Dark only — no dark: variants.
-->
<script setup lang="ts">
// Reusable PNG drop-zone.
// State machine: empty → filled | error.
// The actual File value is owned by the parent (via emits).
// Local state: dragOver flag and error message only.
import { ref } from 'vue';
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

// Derive preview src: new pick > existing URL > null
function previewSrc(): string | null {
    if (props.currentFile) {
        return URL.createObjectURL(props.currentFile);
    }
    return props.existingUrl ?? null;
}

const isFilled = () => props.currentFile !== null || props.existingUrl !== null;

function validateAndEmit(file: File): void {
    const isPng =
        file.type === 'image/png' || file.name.toLowerCase().endsWith('.png');
    if (!isPng) {
        errorMessage.value = 'Only PNG files are accepted.';
        return;
    }
    errorMessage.value = null;
    emit('update:file', file);
}

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

        <!--
          The <label> wraps the hidden <input type="file">, making the
          entire zone a keyboard/click target without custom JS.
          Pressing Enter/Space on the focused label triggers the picker.
        -->
        <label
            :class="[
                'relative flex h-44 cursor-pointer flex-col items-center',
                'justify-center overflow-hidden rounded-[4px] transition-all',
                'duration-120',
                // Filled state
                isFilled() && !errorMessage
                    ? 'border-2 border-[rgba(255,75,60,0.6)]'
                    : errorMessage
                      ? 'border-2 border-[#ff4b3c]'
                      : isDragOver
                        ? [
                              'border-2 border-[#ff4b3c]',
                              'bg-[rgba(255,75,60,0.05)]',
                          ]
                        : [
                              'border-2 border-dashed',
                              'border-[rgba(254,254,254,0.18)]',
                              'bg-[rgba(17,17,17,0.4)]',
                              'hover:border-[#ff4b3c]',
                              'hover:bg-[rgba(255,75,60,0.05)]',
                          ],
                'focus-within:outline-2',
                'focus-within:outline-[#ff4b3c]',
                'focus-within:outline-offset-2',
            ]"
            @dragover="onDragOver"
            @dragleave="onDragLeave"
            @drop="onDrop"
        >
            <!-- Hidden file input — the label IS the click target -->
            <input
                ref="fileInputRef"
                type="file"
                accept="image/png"
                class="sr-only"
                :aria-label="`Upload ${props.label} PNG`"
                @change="onInputChange"
            />

            <!-- Filled state: thumbnail + remove button + filename -->
            <template v-if="isFilled() && !errorMessage">
                <img
                    :src="previewSrc() ?? undefined"
                    :alt="`${props.label} preview`"
                    class="h-full w-full object-cover"
                />
                <!-- Remove button — top-right -->
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

            <!-- Error state: AlertCircle icon + message -->
            <template v-else-if="errorMessage">
                <AlertCircle
                    class="mb-2 h-7 w-7 text-[#ff4b3c]"
                    aria-hidden="true"
                />
                <span class="px-4 text-center text-xs text-[#ff4b3c]">
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

        <!--
          Edit-mode sub-label: shown when no new file has been picked
          but an existing image exists.
        -->
        <p
            v-if="props.existingFilename && !props.currentFile"
            class="mt-1.5 font-mono text-[10px] text-[#b0bac6]"
        >
            Currently {{ props.existingFilename }} ·
            <!-- "replace" focuses the file input (same label already does this
                 on click, but the explicit link aids discoverability) -->
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
