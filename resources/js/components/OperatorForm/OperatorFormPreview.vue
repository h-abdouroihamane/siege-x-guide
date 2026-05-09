<!--
  Aesthetic: tactical dossier / military-brutalist.
  Display: GT America Compressed Bold (font-display). Body: FK Grotesk.
  Accent: signal-red (#ff4b3c). Dark only.
-->
<script setup lang="ts">
// Sticky right-column live preview panel.
// Receives a duck-typed operator object and wraps OperatorCard.
// Displayed only on lg+ (hidden class applied by parent).
import OperatorCard from '../OperatorCard.vue';

defineProps<{
    // Duck-typed: only fields OperatorCard reads are required.
    operator: {
        name: string;
        cleanName: string;
        portrait: string;
        icon: string;
        queerIdentities: string[];
        isAttacker: () => boolean;
        isDefender: () => boolean;
    };
}>();
</script>

<template>
    <div class="lg:sticky lg:top-24">
        <div
            class="rounded-[6px] border border-[rgba(254,254,254,0.08)] bg-[rgba(17,17,17,0.55)] p-5"
        >
            <div class="mb-4 flex items-center justify-between">
                <h3
                    class="font-display text-sm uppercase tracking-[0.2em] text-white"
                >
                    Live preview
                </h3>
                <span class="font-mono text-[10px] text-[#b0bac6]">
                    OperatorCard.vue
                </span>
            </div>

            <div class="flex justify-center">
                <!-- OperatorCard accepts Operator; cast via any for duck type -->
                <OperatorCard :operator="operator as any" :selected="false" />
            </div>

            <p
                class="mt-3 text-center font-mono text-[10px] uppercase tracking-widest text-[#b0bac6]"
            >
                Updates as you edit.
            </p>
        </div>
    </div>
</template>
