<script setup>
import { RouterLink } from 'vue-router';
import LocaleNav from './LocaleNav.vue';
import AccountNav from './AccountNav.vue';
import useUserStore from '/assets/stores/user.js';
import RegionSelector from '../RegionSelector.vue';
import FormatSelector from '../FormatSelector.vue';
const store = useUserStore();
</script>
<template>
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom align-items-center">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-2 link-body-emphasis text-decoration-none">
            <span class="fs-4">{{ $t('name') }}</span>
        </a>

        <ul class="nav nav-pills me-auto">
            <li class="nav-item">
                <RouterLink linkActiveClass="active" class="nav-link" to="/">Home</RouterLink>
            </li>
            <li class="nav-item">
                <RouterLink class="nav-link" to="/about">About</RouterLink>
            </li>
        </ul>
        <FormatSelector />

        <RegionSelector />

        <div class="col-md-2 text-end" v-show="!store.isAuth">
            <RouterLink class="btn btn-outline-success me-2" to="/login">Login</RouterLink>
            <RouterLink class="btn btn-outline-success me-2" to="/register">Register</RouterLink>
        </div>
        <AccountNav />
        <LocaleNav />
    </header>
</template>