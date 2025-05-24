<script setup>
const props = defineProps(['name', 'description', 'side', 'year', 'season', 'portrait', 'icon', 'operationName', 'squad']);

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
        <div class="card">
            <img class="operator-portrait" :src="props.portrait" :alt="props.name" />
            <img class="operator-icon" :src="props.icon" :alt="`${props.name} icon`" />
            <span class="operator-name">{{ props.name }}</span>
        </div>

        <div id="side-container">
            <span id="side-icon" :class="{ attacker: side === 'Attacker', defender: side === 'Defender' }"></span>
            <span id="side-name">{{ getSide() }}</span>
        </div>

        <div id="description-container">
            <p id="description-text">{{ props.description }}</p>
            <div id="info">
                <span id="roles">{{ getRoleStr() }}</span>
                <span id="squad" v-if="props.squad !== 'Unaffiliated'">Squad {{ props.squad }}</span>
                <span id="operation">{{ getOperationStr() }}</span>
            </div>
        </div>
    </div>
</template>

<style>
#description .card {
    width: 191px;
}

#description {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    max-width: 45vw;
    width: 45vw;
    height: 100vh;
    text-shadow: 1px 2px #000000;
    border-top: 1px solid #fe3d2c;
    color: #fefefe;
    font-size: 20px;
}

#description span {
    max-width: -moz-max-content;
    max-width: max-content;
}

#description .operator-icon {
    max-height: 90px;
    height: 90px;
    width: auto;
}

#description-icon {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: horizontal;
    -webkit-box-direction: normal;
    -ms-flex-direction: row;
    flex-direction: row;
    width: -webkit-max-content;
    width: -moz-max-content;
    width: max-content;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
}

#description .name {
    text-transform: uppercase;
}

#info {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

#description p,
#description-icon,
#roles,
#operation {
    margin: 0 0 5px 0;
}
#description-text {
    padding: 5px;
    text-align: center;
}

#side-icon {
    background-position: 50%;
    background-repeat: no-repeat;
    background-size: 80%;
}

#side-icon.attacker {
    background-image: url("data:image/svg+xml;charset=utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'><path fill='%2324262a' d='M8.32,19.16l-5.54,5.54H2V28h3.31v-0.79l5.54-5.54H8.32V19.16z M25.2,2L10.29,17.18L8.5,15.4l-0.94,0.94l1.87,1.87v2.36h2.36l1.87,1.87l0.94-0.94l-1.78-1.78L28,4.8V2H25.2z M21.68,19.16v2.52h-2.52l5.54,5.54V28H28v-3.31h-0.78L21.68,19.16z M14.6,11.98L4.8,2H2v2.8l9.98,9.8L14.6,11.98z M20.57,18.21l1.87-1.87L21.5,15.4l-1.78,1.78l-1.67-1.71l-2.56,2.56l1.7,1.67L15.4,21.5l0.94,0.94l1.87-1.87h2.36V18.21z'/></svg>");
}

#side-icon.defender {
    background-image: url("data:image/svg+xml;charset=utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'><path fill='%2324262a' d='M19.27,9.74l-4.25,2.17L10.73,9.7L8.15,28h13.7L19.27,9.74z M21.23,7.01V2h-2.88v1.73h-1.96V2h-2.78v1.73h-1.96V2H8.77v4.96l6.25,3.51L21.23,7.01z'/></svg>");
}

@media only screen and (max-width: 760px), (min-device-width: 768px) and (max-device-width: 1024px) {
    #description {
        max-height: 50vh;
        height: auto;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        font-size: 15px;
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

    #operation {
        display: none;
    }
}
</style>
