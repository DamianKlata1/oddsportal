import { createRouter, createWebHistory } from 'vue-router';
import useUserStore from '../stores/user.js'
import * as Public from '../views/public';
import * as Admin from '../views/admin';
import * as Account from '../views/account';
import * as Error from '../views/error';

const routes = [
    {
        path: '/',
        name: 'public_layout',
        component: Public.Layout,
        redirect: { name: 'events' },
        children: [
            {
                path: 'events',
                name: 'events',
                component: Public.EventView
            },

        ]
    },
    {
        path: '/',
        name: 'account_layout',
        component: Account.Layout,
        children: [
            {
                path: 'register',
                name: 'register',
                component: Account.Register
            },
            {
                path: 'login',
                name: 'login',
                component: Account.Login
            },
            {
                path: 'change-password-request',
                name: 'change_password_request',
                component: Account.ChangePasswordRequestView
            },
            {
                path: 'change-password',
                name: 'change_password',
                component: Account.ChangePasswordView
            },
            {
                path: 'settings',
                name: 'settings',
                component: Account.Settings,
                meta: {
                    requiresAuth: true,
                    isGranted: 'ROLE_USER'
                }
            },
        ]
    },
    {
        path: '/admin',
        name: 'admin_layout',
        component: Admin.Layout,
        redirect: { name: 'admin_dashboard' },
        meta: {
            requiresAuth: true,
            isGranted: 'ROLE_ADMIN'
        },
        children: [
            {
                path: 'dashboard',
                name: 'admin_dashboard',
                component: Admin.Dashboard,
            },
            {
                path: '/users',
                name: 'admin_users',
                component: Admin.Users,
            },
            {
                path: '/events',
                name: 'admin_events',
                component: Admin.Events,
            }
        ]
    },
    {
        path: '/',
        name: 'error_layout',
        component: Error.Layout,
        children: [
            {
                path: 'error401',
                name: 'error_401',
                component: Error.Error401
            },
            {
                path: 'error404',
                name: 'error_404',
                component: Error.Error404
            }
        ]
    },
    {
        path: "/:pathMatch(.*)*",
        redirect: () => {
            // catch all redirect to 404
            return { name: "error_404" };
        }
    }
];

// create
const router = createRouter({
    history: createWebHistory(),
    //    linkActiveClass: "active",
    linkExactActiveClass: "active",
    routes: routes
});


// Middleware
router.beforeEach((to, from, next) => {
    const store = useUserStore()
    if (to.meta.requiresAuth && !store.isAuth) {
        next({ name: 'login' });
    } else if (to.meta.isGranted && !store.isGranted(to.meta.isGranted)) {
        next({ name: 'error_401' });
    } else {
        next();
    }
});


export default router
