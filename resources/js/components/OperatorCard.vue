<script setup lang="ts">
import type { Operator } from '../scripts/operator.ts';

const props = defineProps<{
    operator: Operator;
    selected: boolean;
}>();
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
        <img
            class="operator-portrait"
            :src="props.operator.portrait"
            :alt="props.operator.name"
        />
        <img
            class="operator-icon"
            :src="props.operator.icon"
            :alt="`${props.operator.name} icon`"
        />
        <div
            class="pride-flag-container"
            v-if="props.operator.queerIdentities.length > 0"
        >
            <span
                v-for="qIdentity in props.operator.queerIdentities"
                :key="qIdentity"
                :class="`pride-flag ${qIdentity.toLowerCase()}`"
            ></span>
        </div>
        <span class="operator-name">{{ props.operator.name }}</span>
    </div>
</template>
<style scoped lang="scss">
@use '../../css/operator-card.css';
</style>
