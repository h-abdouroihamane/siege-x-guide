<script setup lang="ts">
const publicPath = import.meta.env.BASE_URL;

const props = defineProps<{
    text?: string;
}>();
const text = props.text ?? '';
</script>

<template>
    <img
        :src="`${publicPath}Logo_blank.png`"
        alt="Rainbow Six Siege X Logo"
        class="mt-[10px] max-h-[70px] max-[1024px]:max-w-[50vw]"
    />
    <p v-if="text" class="m-[5px] text-[24px] font-bold text-[#b0bac6]">
        {{ text }}
    </p>
</template>
