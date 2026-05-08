<script setup lang="ts">
const publicPath = import.meta.env.BASE_URL;

const socials = [
    {
        name: 'Bluesky',
        url: 'https://bsky.app/profile/alsagone.bsky.social',
        img: `${publicPath}bluesky.svg`,
        id: 'bluesky',
    },
    {
        name: 'Twitch',
        url: 'https://twitch.tv/alsagone',
        img: `${publicPath}twitch.svg`,
        id: 'twitch',
    },
    {
        name: 'Ko-Fi',
        url: 'https://ko-fi.com/alsagone',
        img: `${publicPath}kofi.png`,
        id: 'kofi',
    },
];

const mains = {
    attack: ['Zero', 'Ace', 'Thermite'],
    defense: ['Kaid', 'Mute', 'Wamai'],
};

const getIcon = (name) =>
    `${publicPath}operatorIcons/${name.toLowerCase()}.png`;
const getAlt = (name) => `${name}'s logo`;
</script>

<template>
    <div
        class="flex w-[250px] flex-col items-center justify-center border border-[rgba(176,186,198,0.5)] text-[#fefefe]"
    >
        <div class="w-full">
            <img
                :src="`${publicPath}profilePicture.png`"
                alt="Me wearing a cosplay of Marcus Holloway from Watch_Dogs 2"
                class="h-auto max-w-[250px]"
            />
            <p class="mb-[5px] text-[20px]">
                Hakim
                <span class="italic"
                    >(aka <span class="text-[#ff4b3c]">alsagone</span>)</span
                >
            </p>
            <hr />
        </div>

        <div class="w-full">
            <p class="mb-[5px] text-[20px]">Where you can find me</p>
            <a
                v-for="social in socials"
                :key="social.id"
                :href="social.url"
                target="_blank"
                rel="noopener noreferrer"
                class="mx-2.5 my-[5px]"
            >
                <img
                    class="hvr-grow mb-[5px] h-[35px] w-auto"
                    :class="{ 'max-w-[190px]': social.id === 'kofi' }"
                    :src="social.img"
                    :alt="getAlt(social.name)"
                    :title="social.name"
                />
            </a>
            <hr />
        </div>

        <div class="mb-[9px] w-full">
            <div>
                <p class="mb-[5px] text-[20px]">Attack mains</p>
                <div class="flex items-center justify-center">
                    <div
                        v-for="(name, index) in mains.attack"
                        :key="index"
                        class="flex flex-col items-center justify-center"
                    >
                        <img
                            :src="getIcon(name)"
                            :alt="getAlt(name)"
                            class="h-[60px] w-[60px]"
                        />
                        <span class="font-gt-america uppercase">{{
                            name
                        }}</span>
                    </div>
                </div>
            </div>
            <hr />
            <div>
                <p class="mb-[5px] text-[20px]">Defense mains</p>
                <div class="flex items-center justify-center">
                    <div
                        v-for="(name, index) in mains.defense"
                        :key="index"
                        class="flex flex-col items-center justify-center"
                    >
                        <img
                            :src="getIcon(name)"
                            :alt="getAlt(name)"
                            class="h-[60px] w-[60px]"
                        />
                        <span class="font-gt-america uppercase">{{
                            name
                        }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
