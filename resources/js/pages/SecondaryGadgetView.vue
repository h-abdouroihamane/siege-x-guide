<script setup>
import Logo from '@/components/Logo.vue';
import Navbar from '@/components/Navbar.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { normalize } from '../scripts/operator.ts';
const page = usePage();
const secondaryGadgets = page.props.secondaryGadgets.data;
console.log(secondaryGadgets);
const squadHeader = `Secondary gadgets (from the latest patch of Year ${page.props.year}, Season ${page.props.season} - ${page.props.operationName})`;
const publicPath = import.meta.env.BASE_URL;

const getGadgetLogo = (gadgetName) => {
    const name = gadgetName.toLowerCase().replace(/ +/g, '-');
    return `${publicPath}secondaryGadgets/${name}.png`;
};

const getOperatorIcon = (operatorName) => `${publicPath}operatorIcons/${normalize(operatorName)}.png`;

const getAltText = () => {
    let text = `Table showing every secondary gadget according to Rainbow Six Siege X's latest patch (from Year ${page.props.year}, Season ${page.props.season} - ${page.props.operationName})\n\n`;

    for (const [gadget, operators] of secondaryGadgets) {
        text += gadget + ': ';
        text += operators.join(', ') + '\n\n';
    }

    text += 'Source: https://siege-x-guide.alsagone.ovh/secondary-gadgets';

    return text;
};

const copyAltText = () => {
    navigator.clipboard.writeText(getAltText()).then(
        () => {
            alert('Alt text copied!\nThanks for caring about alt text! :)');
        },
        () => {
            alert('Failed to copy :(');
        }
    );
};

let screenshotMode = ref(false);
const toggleScreenshotMode = () => {
    screenshotMode.value = !screenshotMode.value;
};
</script>
<template>
    <div>
        <Head>
            <title>Squads</title>
            <meta name="description" content="Page listing every squad from Rainbow Six Siege X according to the latest version of the lore" />
        </Head>
        <div id="background-image" />
        <div :class="{ screenshot: screenshotMode }">
            <Navbar path="secondaryGadgets" />
        </div>
        <div id="container">
            <Logo :text="squadHeader" />
            <div id="main-content">
                <table id="gadget-table">
                    <thead>
                        <tr>
                            <th class="tg-0lax"></th>
                            <th class="tg-0lax"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="gadget in secondaryGadgets" :key="gadget.name" class="gadget-row">
                            <td>
                                <div class="gadget">
                                    <img class="gadget-logo" :src="getGadgetLogo(gadget.name)" :alt="gadget.name" />
                                    <p class="gadget-name">{{ gadget.name }}</p>
                                </div>
                            </td>
                            <td>
                                <div class="operator-container">
                                    <img
                                        v-for="(operatorName, index) in gadget.operators"
                                        :key="index"
                                        class="operator-icon"
                                        :src="getOperatorIcon(operatorName)"
                                        :alt="operatorName"
                                    />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
@use '../../css/table.css';
@use '../../css/button.css';
#main-content {
    max-width: 80vw;
    flex-direction: column;
}

.screenshot {
    display: none;
}
</style>
