<script setup lang="ts">
import AttackerLogo from '../components/AttackerLogo.vue';
import DefenderLogo from '../components/DefenderLogo.vue';

const props = defineProps<{
    name: string;
    description: string;
    side: string;
    year: number;
    season: number;
    portrait: string;
    icon: string;
    operationReleaseDate: Date;
    operationName: string;
    squad: string;
    roles: string[];
    queerIdentities: string[] | null;
    reworked: boolean;
}>();

const getRoleStr = () => {
    if (!props.roles) {
        return '';
    }

    let roleString = 'Role' + (props.roles.length > 1 ? 's' : '') + ': ';
    roleString += props.roles.join(', ');
    return roleString;
};

const formatReleaseDate = (d) => {
    const options = {
        year: 'numeric',
        month: 'long',
    };

    return d.toLocaleDateString('en-US', options);
};

const getOperationStr = () => {
    const releaseDate = formatReleaseDate(props.operationReleaseDate);
    const verb = props.reworked ? 'Reworked' : 'Released';
    return props.year > 0
        ? `${verb} on Year ${props.year}, Season ${props.season} - ${props.operationName} (${releaseDate})`
        : 'Part of the base operators';
};

const getSide = () => {
    return props.side === 'Attack' ? 'Attacker' : 'Defender';
};
</script>

<template>
    <div
        id="description"
        class="fixed bottom-0 left-0 z-[2] grid min-h-[190px] w-screen border-t border-[#ff4b3c] bg-[rgba(1,1,1,0.95)] text-[#fefefe] [grid-template-columns:10vw_90vw] max-lg:flex max-lg:h-[30vh] max-lg:max-h-[30vh] max-lg:flex-col max-lg:justify-center max-lg:text-[15px]"
    >
        <div
            id="description-icon"
            class="grid w-full items-center justify-items-center max-lg:flex max-lg:flex-row max-lg:justify-around"
        >
            <img
                class="icon h-[90px] max-h-[90px] w-auto max-lg:max-h-[50px]"
                :src="props.icon"
                :alt="`${props.name} icon`"
            />
            <span class="name font-gt-america text-[22px] uppercase">{{
                props.name
            }}</span>

            <div id="side-container" class="flex flex-row items-center">
                <AttackerLogo
                    class="side-icon h-[30px] w-[30px] max-lg:max-h-[20px]"
                    v-if="side === 'Attack'"
                />
                <DefenderLogo
                    class="side-icon h-[30px] w-[30px] max-lg:max-h-[20px]"
                    v-else-if="side === 'Defense'"
                />
                <span id="side-name">{{ getSide() }}</span>
            </div>
            <div class="pride-description" v-if="props.queerIdentities">
                <div
                    class="queer-row"
                    v-for="qId in props.queerIdentities"
                    :key="qId"
                >
                    <span
                        :class="`pride-flag ${qId.toLowerCase()} max-lg:w-[20px]`"
                    />
                    <p>{{ qId.toUpperCase() }}</p>
                </div>
            </div>
        </div>

        <div
            id="description-text"
            class="flex h-full max-w-[97vw] flex-col justify-evenly p-[5px] max-lg:w-full"
        >
            <span id="ability">{{ props.description }}</span>
            <span id="roles">{{ getRoleStr() }}</span>
            <span id="operation">{{ getOperationStr() }}</span>
        </div>
    </div>
</template>

<style scoped>
/*
 * The side-icon path fills sit on SVG paths rendered by child
 * components (AttackerLogo / DefenderLogo); :deep() pierces
 * scope so the descendant selector reaches them.
 */
:deep(.side-icon.attacker-logo path) {
    fill: #d9610f;
}

:deep(.side-icon.defender-logo path) {
    fill: #0e87c8;
}
</style>
