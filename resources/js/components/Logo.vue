<!--
  Aesthetic: tactical dossier (R6 Siege).
  Logo image + optional text label used at the top of every page.
  Body: FK Grotesk (font-sans). Palette: near-black, muted-foreground text.
  Dark only — no light variant.
-->
<script setup lang="ts">
const publicPath = import.meta.env.BASE_URL;

const props = defineProps<{
    text?: string;
}>();
const text = props.text ?? '';
</script>

<template>
    <img
        id="logo"
        :src="`${publicPath}Logo_blank.png`"
        alt="Rainbow Six Siege X Logo"
        class="mt-2.5 h-auto w-auto max-h-[70px] max-sm:max-w-[50vw]"
    />
    <p v-if="text" class="font-sans font-bold text-2xl text-[#b0bac6] m-[5px]">
        {{ text }}
    </p>
</template>
