<script setup>
import { RouterLink } from 'vue-router';
import useUserStore from '/assets/stores/user.js';
const store = useUserStore();
</script>
<template>
    <div v-if="!store.isAuth" class="btn-group">
        <RouterLink class="btn btn-outline-success" to="/login">{{ $t('login') }}</RouterLink>
        <RouterLink class="btn btn-outline-success" to="/register">{{ $t('register') }}</RouterLink>
        <!-- Navbar-->
    </div>
    <div v-else class="dropdown">
        <button class="btn btn-outline-success dropdown-toggle d-flex align-items-center" type="button"
            id="userMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-2"></i>
            <span class="d-none d-md-inline">{{ store.userData?.email }}</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuDropdown">
            <li>
                <RouterLink :to="{ name: 'settings' }" class="dropdown-item">{{ $t('settings') }}</RouterLink>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <a class="dropdown-item" href="#" @click.prevent="logoutAction(store)">{{ $t('logout') }}</a>
            </li>
        </ul>
    </div>
</template>
<script>
export default {
    methods: {
        logoutAction(store) {
            store.resetState();
            this.$router.push('/');
        }
    },
}
</script>
