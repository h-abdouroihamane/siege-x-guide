<!--
  Aesthetic: tactical dossier (R6 Siege).
  Provides the fixed full-screen background image and the centered
  flex-column content wrapper used by every page.
  Body: FK Grotesk (font-sans). Display: GT America Compressed Bold.
  Palette: near-black ground, signal-red (#ff4b3c) accent.
  Dark only — no light variant.
-->
<script setup lang="ts">
import menuBackground from '../../css/menu_background.JPG';
</script>

<template>
    <!--
      Fixed background image — sits behind #app's rgba(1,1,1,0.5) overlay.
      z-index: -1 keeps it beneath all page content.
    -->
    <div
        class="fixed inset-0 -z-10 bg-black bg-cover bg-center bg-no-repeat"
        :style="{ backgroundImage: `url(${menuBackground})` }"
        aria-hidden="true"
    />

    <!--
      Content wrapper — mirrors the former #container rule:
      flex column, centered horizontally and vertically, text-center.
    -->
    <div class="flex flex-col items-center justify-center text-center">
        <slot />
    </div>
</template>
