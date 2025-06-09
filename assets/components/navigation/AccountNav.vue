<script setup>
    import { RouterLink } from 'vue-router';
    import useUserStore from '/assets/stores/user.js';
    const store = useUserStore();
</script>
<template>
    <!-- Navbar-->
    <div v-show="store.isAuth" class="col-md-2 text-end">
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                   data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <li><RouterLink v-if="store.isGranted('admin')" class="dropdown-item" to="/admin">admin</RouterLink></li>
            <li><RouterLink class="dropdown-item" to="/settings">{{ $t('settings') }}</RouterLink></li>
            <li><hr class="dropdown-divider" /></li>
            <li><a @click="logoutAction(store)" class="dropdown-item" href="#">{{ $t('logout') }}</a></li>
        </ul>
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
