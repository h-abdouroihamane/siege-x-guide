<script setup>
import { useRoute } from 'vue-router';
const route = useRoute();

const activeRoute = {
    operators: route.name === 'operators' || route.path === '/',
    squads: route.name === 'squads',
    admin: route.name === 'admin',
};
</script>

<template>
    <nav>
        <div class="logo">
            <img src="siege-x-icon.png" alt="logo" />
        </div>
        <ul>
            <li>
                <RouterLink to="/operators" :class="{ active: activeRoute.operators }">Operators</RouterLink>
            </li>
            <li>
                <RouterLink to="/squads" :class="{ active: activeRoute.squads }">Squads</RouterLink>
            </li>
            <li><RouterLink to="/admin" :class="{ active: activeRoute.admin }">Admin panel</RouterLink></li>
        </ul>
        <div class="hamburger">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </div>
    </nav>
    <div class="menubar">
        <ul>
            <li>
                <RouterLink to="/operators" :class="{ active: activeRoute.operators }">Operators</RouterLink>
            </li>
            <li>
                <RouterLink to="/squads" :class="{ active: activeRoute.squads }">Squads</RouterLink>
            </li>
            <li><RouterLink to="/admin" :class="{ active: activeRoute.admin }">Admin panel</RouterLink></li>
        </ul>
    </div>
</template>

<style>
nav {
    padding: 5px 5%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow:
        rgba(50, 50, 93, 0.25) 0px 2px 5px -1px,
        rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
    z-index: 1;
    border-bottom: 1px solid #fe3d2c;
    margin: 0 0 10px 0;
}
nav .logo {
    display: flex;
    align-items: center;
}
nav .logo img {
    height: 25px;
    width: auto;
    margin-right: 10px;
}
nav .logo h1 {
    font-size: 1.1rem;
    background: linear-gradient(to right, #b927fc 0%, #2c64fc 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

nav ul {
    list-style: none;
    display: flex;
}
nav ul li {
    margin-left: 1.5rem;
}
nav ul li a {
    text-decoration: none;
    color: #b0bac6;
    font-size: 95%;
    font-weight: 400;
    padding: 4px 8px;
    border-radius: 5px;
}

nav ul li a:hover {
    background-color: #fe3d2c;
    color: #0c0f16;
}

nav ul li a.active {
    color: #fe3d2c;
}

.hamburger {
    display: none;
    cursor: pointer;
}

.hamburger .line {
    width: 25px;
    height: 1px;
    background-color: #fe3d2c;
    display: block;
    margin: 7px auto;
    transition: all 0.3s ease-in-out;
}
.hamburger-active {
    transition: all 0.3s ease-in-out;
    transition-delay: 0.6s;
    transform: rotate(45deg);
}

.hamburger-active .line:nth-child(2) {
    width: 0px;
}

.hamburger-active .line:nth-child(1),
.hamburger-active .line:nth-child(3) {
    transition-delay: 0.3s;
}

.hamburger-active .line:nth-child(1) {
    transform: translateY(12px);
}

.hamburger-active .line:nth-child(3) {
    transform: translateY(-5px) rotate(90deg);
}

.menubar {
    position: absolute;
    top: 0;
    left: -60%;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    width: 60%;
    height: 100vh;
    padding: 20% 0;
    background: rgba(255, 255, 255);
    transition: all 0.5s ease-in;
    z-index: 2;
}
.active {
    left: 0;
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
}

.menubar ul {
    padding: 0;
    list-style: none;
}
.menubar ul li {
    margin-bottom: 32px;
}

.menubar ul li a {
    text-decoration: none;
    color: #000;
    font-size: 95%;
    font-weight: 400;
    padding: 5px 10px;
    border-radius: 5px;
}

.menubar ul li a:hover {
    background-color: #f5f5f5;
}
@media screen and (max-width: 790px) {
    .hamburger {
        display: block;
    }
    nav ul {
        display: none;
    }
}
</style>
