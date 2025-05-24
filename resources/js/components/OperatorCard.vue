<script setup>
const props = defineProps(['operator', 'selected']);
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
        <img class="operator-portrait" :src="props.operator.portrait" :alt="props.operator.name" />
        <img class="operator-icon" :src="props.operator.icon" :alt="`${props.operator.name} icon`" />
        <span class="operator-name">{{ props.operator.name }}</span>
    </div>
</template>
