<script setup>
import { RouterLink } from 'vue-router'
import useUserStore from '/assets/stores/user.js';
const store = useUserStore();
</script>
<template>
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <RouterLink class="nav-link" to="/admin/dashboard">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    {{ $t('dashboard') }}
                </RouterLink>
                <RouterLink class="nav-link" to="/admin/users">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    {{ $t('users') }}
                </RouterLink>
                <RouterLink class="nav-link" to="/admin/events">
                    <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                    {{ $t('events') }}
                </RouterLink>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            {{ store.userData.email }}
        </div>
    </nav>
</template>
<script>
export default {
    mounted() {
        // Toggle the side navigation
        const sidebarToggle = document.body.querySelector('#sidebarToggle');
        if (sidebarToggle) {
            // Uncomment Below to persist sidebar toggle between refreshes
            // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
            //     document.body.classList.toggle('sb-sidenav-toggled');
            // }
            sidebarToggle.addEventListener('click', event => {
                event.preventDefault();
                document.body.classList.toggle('sb-sidenav-toggled');
                localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
            });
        }
    },
    unmounted() {

    }

}
</script>