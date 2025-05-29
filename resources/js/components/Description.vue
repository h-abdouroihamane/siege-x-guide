<script setup>
import AttackerLogo from '../components/AttackerLogo.vue';
import DefenderLogo from '../components/DefenderLogo.vue';

const props = defineProps(['name', 'description', 'side', 'year', 'season', 'portrait', 'icon', 'operationName', 'squad', 'roles']);

const getRoleStr = () => {
    if (!props.roles) {
        return '';
    }

    let roleString = 'Role' + (props.roles.length > 1 ? 's' : '') + ': ';
    roleString += props.roles.join(', ');
    return roleString;
};

const getOperationStr = () => {
    return props.year > 0 ? `Released on Year ${props.year}, Season ${props.season} - ${props.operationName}` : 'Part of the base operators';
};

const getSide = () => {
    return props.side === 'Attack' ? 'Attacker' : 'Defender';
};
</script>

<template>
    <div id="description">
        <div id="description-icon">
            <img class="icon" :src="props.icon" :alt="`${props.name} icon`" />
            <span class="name">{{ props.name }}</span>

            <div id="side-container">
                <AttackerLogo class="side-icon" v-if="side === 'Attack'" />
                <DefenderLogo class="side-icon" v-else-if="side === 'Defense'" />
                <span id="side-name">{{ getSide() }}</span>
            </div>
        </div>
        <div id="description-text">
            <span id="ability">{{ props.description }}</span>
            <span id="roles">{{ getRoleStr() }}</span>
            <span id="operation">{{ getOperationStr() }}</span>
        </div>
    </div>
</template>

<style>
#side-container {
    display: flex;
    flex-direction: row;
    align-items: center;
}

.side-icon {
    width: 30px;
    height: 30px;
}

.side-icon.attacker-logo path {
    fill: #d9610f;
}

.side-icon.defender-logo path {
    fill: #0e87c8;
}

#description {
    font-family: 'Simplon Mono';
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100vw;
    z-index: 100;
    background-color: rgba(1, 1, 1, 0.95);
    color: #fefefe;
    min-height: 140px;
    height: 14vh;
    display: grid;
    grid-template-columns: 10vw 90vw;
    border-top: 1px solid #fe3d2c;
}

#description img {
    max-height: 90px;
    height: 90px;
    width: auto;
}

#description-icon {
    display: grid;
    width: 100%;
    justify-items: center;
    align-items: center;
}

#description .name {
    font-weight: bold;
    text-transform: uppercase;
}

#description-text {
    display: flex;
    flex-direction: column;
    max-width: 97vw;
    padding: 5px;
    height: 100%;
    justify-content: space-evenly;
}

@media only screen and (max-width: 760px), (min-device-width: 768px) and (max-device-width: 1024px) {
    #description {
        max-height: 50vh;
        height: auto;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        font-size: 20px;
    }
    #description > img {
        max-height: 10vh;
    }
    #description-icon {
        width: 20vw;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
    }
    #description-text {
        max-width: 80vw;
    }
}
</style>
