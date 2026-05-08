<script setup lang="ts">
import IntroCard from '@/components/IntroCard.vue';
import Logo from '@/components/Logo.vue';
import Navbar from '@/components/Navbar.vue';
import PageLayout from '@/components/PageLayout.vue';
import { Head } from '@inertiajs/vue3';

const urls = {
    mentorCard: 'https://alsagone.gitlab.io/mentor-card/',
    ubiVersion:
        'https://www.ubisoft.com/en-gb/game/rainbow-six/siege/game-info/operators',
    oldVersion: 'https://alsagone.gitlab.io/r6-quick-guide/',
    json: 'https://gitlab.com/alsagone/r6-quick-guide/-/blob/master/public/r6-operators.json',
};
</script>

<template>
    <Head>
        <title>About me</title>
    </Head>
    <Navbar path="about" />
    <PageLayout>
        <Logo text="About Me" />

        <div id="about">
            <IntroCard />

            <div id="section-container">
                <div class="section">
                    <span class="title">Me!</span>
                    <p>
                        Hi!<br />
                        I'm Hakim/alsagone, the creator of this website. I made
                        this section to talk more about myself and why I decided
                        to build this website.<br /><br />
                        I'm a member of the
                        <span class="highlight italic"
                            >Ubisoft Creators Program</span
                        >
                        and if you are French, you probably know me for the 360+
                        page-long Google Doc summarizing the entire Assassin's
                        Creed lore I wrote with my best friend. If you want to
                        know more about that, I created
                        <a
                            :href="urls.mentorCard"
                            target="_blank"
                            rel="noopener noreferrer"
                            title="Other personal website"
                            >another website</a
                        >
                        to talk about my Ubisoft-related projects and my
                        experience with the various Ubisoft Community programs
                        :)<br /><br />

                        About Siege, I randomly picked up the Art of Siege
                        collector edition back in 2016 - because it was
                        discounted and the artwork on the box looked cool - not
                        knowing anything about the game. I played on a really
                        crappy laptop at 25 FPS but I had a ton of fun, so much
                        so that Siege is rivaling with Assassin's Creed for the
                        top hyperfixation spot now :D
                    </p>
                </div>

                <div class="section">
                    <span class="title">Why this website?</span>
                    <p>
                        I tried to recruit 90 percent of my extended friend
                        group over the years, with various success and even
                        though it's less true now that the Siege team made a big
                        effort on easing up the onboarding process, explaining
                        which operator does what to someone who just picked up
                        the game was a tough task. Sure, there is the
                        <a
                            :href="urls.ubiVersion"
                            target="_blank"
                            rel="noopener noreferrer"
                            title="Official Siege operator website"
                            >official website</a
                        >
                        that lists every operator but you have to access the
                        operator's profile page to get explained what they do,
                        so the process is not easy if you want to check every
                        operator. <br /><br />
                        Then I realized that I actually
                        <span class="italic">do</span> have the skill set to
                        make a variation of the official website that answer to
                        that issue, so here we are now.
                    </p>
                </div>

                <div class="section">
                    <span class="title">The technical stuff</span>
                    <p>
                        Back when I started experimenting with Front-end
                        frameworks, I made
                        <a
                            :href="urls.oldVersion"
                            target="_blank"
                            rel="noopener noreferrer"
                            title="Old website"
                            >this website</a
                        >
                        that looks like the version I made here, the key
                        difference being that all the data was stored in a
                        <a
                            :href="urls.json"
                            target="_blank"
                            rel="noopener noreferrer"
                            title="the JSON file"
                            >big JSON file</a
                        >
                        that proved to be really annoying to maintain as more
                        operators got added to the game, and I wished I could
                        just use a database instead - but I did not have the
                        proper skills to do that back then (+ hosting a website
                        using a database requires a subscription). <br /><br />

                        Fast forward to September 2024, I graduate with honors
                        with a Masters degree in Web development and during my
                        internship, I learned and coded a website using the
                        Laravel framework, which reignited my will to migrate my
                        old project to something more robust using Laravel.
                        <br /><br />

                        The major advantage is that I can now add/edit an
                        operator directly from the website using a form, like
                        the one you fill when you order stuff online, and the
                        data gets directly added to/updated in the database,
                        which is a process way cleaner than manipulating a big
                        JSON file like I used to do.<br /><br />

                        Also, I am looking for a Junior Web developer position
                        and needed a Laravel-based project to showcase my skills
                        so that was an extra incentive for me to complete that
                        project :)
                    </p>
                </div>
            </div>
        </div>
    </PageLayout>
</template>

<style lang="scss">
@use '../../css/about.css';
@use '../../css/section.css';
</style>
