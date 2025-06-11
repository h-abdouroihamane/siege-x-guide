<script setup>
import LogoImg from '../../../public/Logo_blank.png';
const props = defineProps(['text']);
const text = props.text ?? '';
</script>

<template>
    <img id="logo" :src="LogoImg" alt="Rainbow Six Siege X Logo" />
    <p id="logo-text" v-if="text">{{ text }}</p>
</template>

<style>
#logo {
    max-height: 70px;
    width: auto;
    height: auto;
    margin: 10px 0 0 0;
}

#logo-text {
    font-family: 'FK Grotesk', sans-serif;
    font-weight: bold;
    font-size: 24px;
    margin: 5px;
    color: #b0bac6;
}
@media only screen and (max-width: 760px), (min-device-width: 768px) and (max-device-width: 1024px) {
    #logo {
        max-width: 50vw;
    }
}
</style>
