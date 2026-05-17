<script setup>
import { operatorIcon as getIcon, publicAsset } from '../scripts/operator.ts';

const socials = [
    {
        name: 'Bluesky',
        url: 'https://bsky.app/profile/alsagone.bsky.social',
        img: publicAsset('bluesky.svg'),
        id: 'bluesky',
    },
    {
        name: 'Twitch',
        url: 'https://twitch.tv/alsagone',
        img: publicAsset('twitch.svg'),
        id: 'twitch',
    },
    {
        name: 'Ko-Fi',
        url: 'https://ko-fi.com/alsagone',
        img: publicAsset('kofi.png'),
        id: 'kofi',
    },
];

const mains = {
    attack: ['Zero', 'Ace', 'Thermite'],
    defense: ['Kaid', 'Mute', 'Wamai'],
};

const getAlt = (name) => `${name}'s logo`;
</script>

<template>
    <div id="intro-card">
        <div id="header">
            <img
                id="profile-picture"
                :src="publicAsset('profilePicture.png')"
                alt="Me wearing a cosplay of Marcus Holloway from Watch_Dogs 2"
            />
            <p class="title">
                Hakim
                <span class="italic"
                    >(aka <span id="username">alsagone</span>)</span
                >
            </p>
            <hr />
        </div>

        <div id="socials">
            <p class="title">Where you can find me</p>
            <a
                class="social-link"
                v-for="social in socials"
                :href="social.url"
                target="_blank"
                rel="noopener noreferrer"
            >
                <img
                    :id="social.id"
                    class="social-logo hvr-grow"
                    :src="social.img"
                    :alt="getAlt(social.name)"
                    :title="social.name"
                />
            </a>
            <hr />
        </div>

        <div id="mains">
            <div id="attack">
                <p class="title">Attack mains</p>
                <div class="icon-container">
                    <div
                        class="main-operator"
                        v-for="(name, index) in mains.attack"
                        :key="index"
                    >
                        <img
                            :src="getIcon(name)"
                            :alt="getAlt(name)"
                            class="icon"
                        />
                        <span class="name">{{ name }}</span>
                    </div>
                </div>
            </div>
            <hr />
            <div id="defense">
                <p class="title">Defense mains</p>
                <div class="icon-container">
                    <div
                        class="main-operator"
                        v-for="(name, index) in mains.defense"
                        :key="index"
                    >
                        <img
                            :src="getIcon(name)"
                            :alt="getAlt(name)"
                            class="icon"
                        />
                        <span class="name">{{ name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
@use '../../css/intro-card.css';
</style>
