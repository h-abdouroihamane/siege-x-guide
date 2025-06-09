<script setup>
import Logo from '@/components/Logo.vue';
import Navbar from '@/components/Navbar.vue';
import { usePage } from '@inertiajs/vue3';

import { normalize } from '../scripts/operator.ts';
const page = usePage();
const squads = Object.entries(page.props.squads);
const year = page.props.year;
const season = page.props.season;
const operationName = page.props.operationName;
const getSquadLogo = (squadName) => `squadLogos/${normalize(squadName)}.png`;
const getOperatorIcon = (operatorName) => `/operatorIcons/${normalize(operatorName)}.png`;

const getAltText = () => {
    let text = `Table showing every squad composition according to Rainbow Six Siege X's lore (up to Year ${year}, Season ${season} - ${operationName})\n\n`;

    for (const [squadName, operators] of squads) {
        text += squadName !== 'Unaffiliated' ? `Squad ${squadName}\n` : 'Unaffiliated operators\n';
        text += operators.join(', ') + '\n\n';
    }

    return text;
};

const copyAltText = () => {
    navigator.clipboard.writeText(getAltText()).then(
        () => {
            alert('Alt text copied !');
        },
        () => {
            alert('Failed to copy');
        }
    );
};
</script>
<template>
    <div>
        <div id="background-image" />
        <Navbar path="squads" />
        <div id="container">
            <Logo />
            <div id="main-content">
                <div class="header">
                    <p>Squads (up to Year {{ year }}, Season {{ season }} - {{ operationName }})</p>
                </div>
                <table id="squad-table">
                    <thead>
                        <tr>
                            <th class="tg-0lax"></th>
                            <th class="tg-0lax"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="[squad, operators] in squads" :key="squad" :class="squad">
                            <td :class="squad.toLowerCase()">
                                <div class="squad">
                                    <img v-if="squad !== 'Unaffiliated'" class="squad-logo" :src="getSquadLogo(squad)" :alt="`Squad ${squad}`" />
                                    <p class="squad-name">{{ squad }}</p>
                                </div>
                            </td>
                            <td :class="squad.toLowerCase()">
                                <div class="operator-container">
                                    <div v-for="(operatorName, index) in operators" :key="index" class="operator" :id="normalize(operatorName)">
                                        <img class="operator-icon" :src="getOperatorIcon(operatorName)" :alt="operatorName" />
                                        <p class="operator-name">{{ operatorName }}</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div id="credit">Crediting myself so my friends don't yell at me for not doing so: made by alsagone.bsky.social :)</div>

                <button class="button-1" role="button" @click="copyAltText()">Copy alt text</button>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
@use '../../css/squads.css';
#main-content {
    max-width: 80vw;
    flex-direction: column;
}
</style>
