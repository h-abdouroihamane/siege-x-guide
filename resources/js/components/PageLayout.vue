<!-- Aesthetic: dossier / military-tactical. Dark-only (§10). -->
<!-- Wraps every public page: fixed full-screen background + -->
<!-- centered flex column content container. -->
<script setup lang="ts">
import bgUrl from '../../css/menu_background.jpg';
</script>

<template>
    <div
        aria-hidden="true"
        class="fixed inset-0 -z-10 w-screen h-screen bg-black bg-cover bg-center bg-no-repeat overflow-hidden"
        :style="{ backgroundImage: `url(${bgUrl})` }"
    />
    <div class="flex flex-col justify-center items-center text-center">
        <slot />
    </div>
</template>
