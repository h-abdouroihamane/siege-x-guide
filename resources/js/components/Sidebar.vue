<script setup>
import AttackerLogo from '../components/AttackerLogo.vue';
import DefenderLogo from '../components/DefenderLogo.vue';

import { ref } from 'vue';
const emit = defineEmits(['sortBy', 'filterSide']);
const isSmall = ref(true);
const toggleSidebar = () => (isSmall.value = !isSmall.value);

const sortMode = ref('date');

const toggleSort = (sortingMethod) => {
    sortMode.value = sortingMethod;
    emit('sortBy', sortingMethod);
};

const activeAttackers = ref(true);
const activeDefenders = ref(true);

const toggleAttackers = () => {
    if (!activeDefenders.value) {
        return;
    }

    activeAttackers.value = !activeAttackers.value;
    emitSortingSide();
};

const toggleDefenders = () => {
    if (!activeAttackers.value) {
        return;
    }

    activeDefenders.value = !activeDefenders.value;
    emitSortingSide();
};

const emitSortingSide = () => {
    let sides = '';
    sides += activeAttackers.value ? 'attackers' : '';
    sides += activeDefenders.value ? 'defenders' : '';
    emit('filterSide', sides);
};
</script>

<template>
    <div class="sidebar" :class="{ small: isSmall }">
        <button id="close-button" @click="toggleSidebar">
            <svg v-if="isSmall" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                <path
                    d="M470.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 256 265.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160zm-352 160l160-160c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L210.7 256 73.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0z"
                /></svg
            ><svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                <path
                    d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160zm352-160l-160 160c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L301.3 256 438.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0z"
                />
            </svg>
            Filters
        </button>

        <div id="btn-grid">
            <p class="filter-label">Side</p>
            <button id="attackers" class="radio-button left attackers" :class="{ active: activeAttackers }" @click="toggleAttackers">
                <AttackerLogo /> Attackers
            </button>
            <button id="defenders" class="radio-button right defenders" :class="{ active: activeDefenders }" @click="toggleDefenders">
                <DefenderLogo /> Defenders
            </button>
            <p class="sort-label">Sort by</p>
            <button
                id="sort-date"
                class="radio-button left sort date-btn"
                :class="{ active: sortMode === 'date' }"
                name="sortDate"
                @click="toggleSort('date')"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path
                        d="M152 24c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 40L64 64C28.7 64 0 92.7 0 128l0 16 0 48L0 448c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-256 0-48 0-16c0-35.3-28.7-64-64-64l-40 0 0-40c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 40L152 64l0-40zM48 192l352 0 0 256c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16l0-256z"
                    /></svg
                >Most recent
            </button>
            <button
                id="sort-name"
                class="radio-button right sort name-btn"
                :class="{ active: sortMode === 'name' }"
                name="sortName"
                @click="toggleSort('name')"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                    <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path
                        d="M183.6 469.6C177.5 476.2 169 480 160 480s-17.5-3.8-23.6-10.4l-88-96c-11.9-13-11.1-33.3 2-45.2s33.3-11.1 45.2 2L128 365.7 128 64c0-17.7 14.3-32 32-32s32 14.3 32 32l0 301.7 32.4-35.4c11.9-13 32.2-13.9 45.2-2s13.9 32.2 2 45.2l-88 96zM320 320c0-17.7 14.3-32 32-32l128 0c12.9 0 24.6 7.8 29.6 19.8s2.2 25.7-6.9 34.9L429.3 416l50.7 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-128 0c-12.9 0-24.6-7.8-29.6-19.8s-2.2-25.7 6.9-34.9L402.7 352 352 352c-17.7 0-32-14.3-32-32zM416 32c12.1 0 23.2 6.8 28.6 17.7l64 128 16 32c7.9 15.8 1.5 35-14.3 42.9s-35 1.5-42.9-14.3L460.2 224l-88.4 0-7.2 14.3c-7.9 15.8-27.1 22.2-42.9 14.3s-22.2-27.1-14.3-42.9l16-32 64-128C392.8 38.8 403.9 32 416 32zM395.8 176l40.4 0L416 135.6 395.8 176z"
                    /></svg
                >Name
            </button>
        </div>
    </div>
</template>

<style>
.sidebar {
    width: max-content;
    height: max-content;
    transition: 0.1s all ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: absolute;
    left: 0;
    color: #fefefe;
    border-right: 1px solid #fe3d2c;
    padding: 20px 10px;
    z-index: 5;
    background-color: rgba(1, 1, 1, 0.95);
}

.sidebar.small {
    width: 50px;
}

.sidebar.small #btn-grid {
    display: none;
}

.main-content {
    width: 90%;
}

.sidebar #close-button {
    background-color: transparent;
    border: none;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    height: 40px;
    width: 80%;
    box-shadow: 0px 1px 4px 1px rgba(0, 0, 0, 0.3);
    cursor: pointer;
    font-family: 'Simplon Mono', sans-serif;
    font-size: 18px;
    color: #fefefe;
}

#close-button svg {
    width: 30px;
    height: 30px;
    margin: 10px;
}
#close-button path {
    fill: #fe3d2c;
}

.sidebar.small #close-button {
    flex-direction: column;
    font-size: 15px;
    height: max-content;
}

#btn-grid .sort-label {
    grid-area: sort;
}
#btn-grid #sort-date {
    grid-area: date;
}
#btn-grid #sort-name {
    grid-area: name;
}
#btn-grid .filter-label {
    grid-area: filter;
}
#btn-grid #attackers {
    grid-area: attackers;
}
#btn-grid #defenders {
    grid-area: defenders;
}

#btn-grid {
    display: grid;
    grid-template-columns: repeat(2, 150px);
    grid-template-rows: repeat(4, 30px);
    grid-template-areas:
        'filter filter'
        'attackers defenders'
        'sort sort'
        'date name';

    grid-row-gap: 10px;
    grid-column-gap: 0;
    justify-content: center;
}

.radio-button {
    font-family: 'Simplon Mono';
    font-weight: bold;
    min-width: 132px;
    height: 100%;
    width: 100%;
    appearance: none;
    border: 1px solid rgba(27, 31, 35, 0.15);
    border-radius: 6px;
    box-shadow: rgba(27, 31, 35, 0.1) 0 1px 0;
    box-sizing: border-box;
    color: #fff;
    cursor: pointer;
    display: flex;
    justify-content: center;
    line-height: 20px;
    padding: 6px 16px;
    position: relative;
    text-align: center;
    text-decoration: none;
    user-select: none;
    -webkit-user-select: none;
    touch-action: manipulation;
    vertical-align: middle;
    white-space: nowrap;
    transition: 0.3s all ease;
}

.radio-button.left {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.radio-button.right {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
.radio-button.active.attackers,
.radio-button:not(.active):hover.attackers {
    background-color: #d9610f;
    box-shadow: rgba(217, 97, 15, 0.2) 0 1px 0 inset;
}
.radio-button.active.defenders,
.radio-button:not(.active):hover.defenders {
    background-color: #0e87c8;
    box-shadow: rgba(14, 135, 200, 0.2) 0 1px 0 inset;
}

.radio-button.active.sort,
.radio-button:not(.active):hover.sort {
    background-color: #fe3d2c;
    box-shadow: rgba(254, 61, 44, 0.2) 0 1px 0 inset;
}

.radio-button:not(.active) {
    color: rgba(254, 254, 254, 0.4);
}

.radio-button:not(.active) path {
    fill: rgba(254, 254, 254, 0.4);
}

.radio-button:not(.active).attackers {
    border-color: rgba(217, 97, 15, 0.1);
    background-color: rgba(217, 97, 15, 0.3);
}
.radio-button:not(.active).defenders {
    border-color: rgba(14, 135, 200, 0.1);
    background-color: rgba(14, 135, 200, 0.3);
}

.radio-button:not(.active).sort {
    border-color: rgba(254, 61, 44, 0.1);
    background-color: rgba(254, 61, 44, 0.3);
}

.radio-button:focus:not(:focus-visible):not(.focus-visible) {
    box-shadow: none;
    outline: none;
}

.radio-button:not(.active):hover.attackers {
    background-color: #d9610f;
}
.radio-button:not(.active):hover.defenders {
    background-color: #0e87c8;
}

.radio-button:focus {
    outline: none;
}
.radio-button:focus.attackers {
    box-shadow: rgba(217, 97, 15, 0.4) 0 0 0 3px;
}
.radio-button:focus.defenders {
    box-shadow: rgba(14, 135, 200, 0.4) 0 0 0 3px;
}

.radio-button svg {
    height: 20px;
    width: 20px;
    margin: 0px 6px 0 0;
}

.radio-button path {
    fill: #fefefe;
}
</style>
