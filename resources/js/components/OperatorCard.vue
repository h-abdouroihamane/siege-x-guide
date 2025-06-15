<script setup>
const props = defineProps(['operator', 'selected', 'slim']);
const slim = props.slim ?? false;
</script>

<template>
    <div
        :class="{
            card: true,
            attacker: props.operator.isAttacker(),
            defender: props.operator.isDefender(),
            selected: props.selected,
        }"
        :id="props.operator.cleanName"
    >
        <img v-if="!slim" class="operator-portrait" :src="props.operator.portrait" :alt="props.operator.name" />
        <img class="operator-icon" :src="props.operator.icon" :alt="`${props.operator.name} icon`" />
        <span class="operator-name">{{ props.operator.name }}</span>
    </div>
</template>
<style scoped lang="scss">
@use '../../css/operator-card.css';
</style>
