<!--
  Aesthetic: tactical dossier / military-brutalist.
  Display: GT America Compressed Bold (font-display).
  Body: FK Grotesk (font-sans). Accent: signal-red (#ff4b3c).
  Dark only — no dark: variants.
-->
<script setup lang="ts">
// Slide-in Dialog (sheet) for creating a new operation inline.
// Owned by OperatorFormOperation.vue which controls open state.
import axios from 'axios';
import {
    DialogContent,
    DialogOverlay,
    DialogPortal,
    DialogRoot,
} from 'reka-ui';
import { ref } from 'vue';
import type { OperationOptionData } from '../../types/domain.ts';

const props = defineProps<{ open: boolean }>();

const emit = defineEmits<{
    'update:open': [value: boolean];
    created: [value: OperationOptionData];
}>();

const submitting = ref(false);
const newName = ref('');
const newYear = ref<number | ''>('');
const newSeason = ref<number | ''>('');
const newReleaseDate = ref('');
const fieldErrors = ref<Record<string, string>>({});

function resetForm() {
    newName.value = '';
    newYear.value = '';
    newSeason.value = '';
    newReleaseDate.value = '';
    fieldErrors.value = {};
}
defineExpose({ resetForm });

function close() {
    emit('update:open', false);
}

async function submitOperation() {
    submitting.value = true;
    fieldErrors.value = {};
    try {
        const csrfToken =
            (
                document.querySelector(
                    'meta[name="csrf-token"]',
                ) as HTMLMetaElement | null
            )?.content ?? '';
        const res = await axios.post(
            route('operation.store'),
            {
                name: newName.value,
                year: newYear.value,
                season: newSeason.value,
                release_date: newReleaseDate.value,
            },
            { headers: { 'X-CSRF-TOKEN': csrfToken } },
        );
        emit('created', res.data as OperationOptionData);
        close();
    } catch (err: unknown) {
        if (axios.isAxiosError(err) && err.response?.status === 422) {
            const errs = err.response.data?.errors ?? {};
            const flat: Record<string, string> = {};
            for (const [k, v] of Object.entries(errs)) {
                flat[k] = Array.isArray(v) ? (v[0] as string) : String(v);
            }
            fieldErrors.value = flat;
        }
    } finally {
        submitting.value = false;
    }
}

const labelCls =
    'mb-1.5 block font-mono text-[11px] uppercase tracking-[0.12em]' +
    ' text-[#b0bac6]';
const inputCls =
    'w-full rounded-[4px] px-3 py-2 focus-visible:outline-2' +
    ' focus-visible:outline-[#ff4b3c]';
const errorCls = 'mt-1 font-mono text-[11px] text-[#ff4b3c]';
</script>

<template>
    <DialogRoot :open="props.open" @update:open="emit('update:open', $event)">
        <DialogPortal>
            <DialogOverlay
                class="fixed inset-0 z-40 bg-[rgba(0,0,0,0.65)] data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=open]:fade-in data-[state=closed]:fade-out"
            />
            <DialogContent
                aria-labelledby="sheet-title"
                class="fixed right-0 top-0 z-50 h-full w-full max-w-md overflow-y-auto border-l border-[rgba(255,75,60,0.25)] bg-[#0d0d0d] p-6 data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=open]:slide-in-from-right data-[state=closed]:slide-out-to-right"
            >
                <h2
                    id="sheet-title"
                    class="font-display mb-6 text-base uppercase tracking-[0.04em] text-white"
                >
                    Create new operation
                </h2>

                <form class="space-y-4" @submit.prevent="submitOperation">
                    <!-- Name -->
                    <div>
                        <label for="sheet-op-name" :class="labelCls">
                            Name
                        </label>
                        <input
                            id="sheet-op-name"
                            v-model="newName"
                            type="text"
                            :class="inputCls"
                            autocomplete="off"
                        />
                        <p v-if="fieldErrors.name" :class="errorCls">
                            {{ fieldErrors.name }}
                        </p>
                    </div>

                    <!-- Year -->
                    <div>
                        <label for="sheet-op-year" :class="labelCls">
                            Year
                        </label>
                        <input
                            id="sheet-op-year"
                            v-model.number="newYear"
                            type="number"
                            min="1"
                            :class="inputCls"
                        />
                        <p v-if="fieldErrors.year" :class="errorCls">
                            {{ fieldErrors.year }}
                        </p>
                    </div>

                    <!-- Season -->
                    <div>
                        <label for="sheet-op-season" :class="labelCls">
                            Season
                        </label>
                        <select
                            id="sheet-op-season"
                            v-model.number="newSeason"
                            :class="inputCls"
                        >
                            <option value="">Select…</option>
                            <option :value="1">1</option>
                            <option :value="2">2</option>
                            <option :value="3">3</option>
                            <option :value="4">4</option>
                        </select>
                        <p v-if="fieldErrors.season" :class="errorCls">
                            {{ fieldErrors.season }}
                        </p>
                    </div>

                    <!-- Release date -->
                    <div>
                        <label for="sheet-op-date" :class="labelCls">
                            Release date
                        </label>
                        <input
                            id="sheet-op-date"
                            v-model="newReleaseDate"
                            type="date"
                            :class="inputCls"
                        />
                        <p v-if="fieldErrors.release_date" :class="errorCls">
                            {{ fieldErrors.release_date }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 pt-2">
                        <button
                            type="submit"
                            :disabled="submitting"
                            class="button-1 disabled:opacity-50"
                        >
                            {{ submitting ? 'Creating…' : 'Create & select' }}
                        </button>
                        <button
                            type="button"
                            class="font-mono text-[11px] uppercase tracking-widest text-[#b0bac6] hover:text-white focus-visible:outline-2 focus-visible:outline-[#ff4b3c]"
                            @click="close"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </DialogContent>
        </DialogPortal>
    </DialogRoot>
</template>
