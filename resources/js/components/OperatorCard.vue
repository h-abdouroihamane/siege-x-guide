<script setup lang="ts">
import type { Operator } from '../scripts/operator.ts';

const props = defineProps<{
    operator: Operator;
    selected: boolean;
}>();
</script>

<template>
    <div
        :id="props.operator.cleanName"
        :class="[
            'card relative mx-2.5 my-5 flex h-max w-[120px] flex-col items-center rounded-[2px] transition-all duration-300',
            '[box-shadow:rgba(255,255,255,0.09)_0px_3px_6px,rgba(255,255,255,0.16)_0px_3px_6px]',
            {
                attacker: props.operator.isAttacker(),
                defender: props.operator.isDefender(),
                selected: props.selected,
            },
        ]"
    >
        <img
            class="operator-portrait h-full w-full"
            :src="props.operator.portrait"
            :alt="props.operator.name"
        />
        <img
            class="operator-icon absolute top-[55%] left-1/2 z-[2] block h-auto w-1/2 [margin:0_-25%]"
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
                :aria-label="qIdentity"
                :title="qIdentity"
            ></span>
        </div>
        <span
            class="operator-name font-display flex h-[20px] items-center justify-center bg-[rgba(17,17,17,0.15)] px-0 pt-[7px] pb-[10px] text-[20px] text-white uppercase backdrop-blur-[2px]"
            >{{ props.operator.name }}</span
        >
    </div>
</template>

<style scoped>
/*
 * Combinatorial hover/selected state per faction. CSS class
 * combinators (e.g. `.card:hover:not(.queer).attacker`) read more
 * clearly here than as nested Vue conditional classes. Asset URLs
 * are absolute (public/) so they resolve regardless of where the
 * compiled CSS is served from.
 */
.card:hover:not(.selected) {
    cursor: pointer;
}
.card:hover,
.card.selected {
    background-size: cover;
}
.card:hover:not(.queer).attacker,
.card.selected:not(.queer).attacker {
    background-image: url('/siege-x-card-att.png');
}
.card:hover:not(.queer).defender,
.card.selected:not(.queer).defender {
    background-image: url('/siege-x-card-def.png');
}
.card:hover.queer,
.card.selected.queer {
    background-image: url('/siege-x-card-pride.png');
}
.card:hover .operator-name,
.card.selected .operator-name {
    color: #fff;
    text-shadow: 5px 5px 10px #000;
    -webkit-backdrop-filter: unset;
    backdrop-filter: unset;
}
.card:hover.attacker,
.card.selected.attacker {
    box-shadow: 0 0 10px 1px #d9610f;
}
.card:hover.defender,
.card.selected.defender {
    box-shadow: 0 0 10px 1px #0e87c8;
}
.card:hover.option {
    box-shadow: 0 0 10px 1px #ff4b3c;
    background-color: #ff4b3c;
}
</style>
