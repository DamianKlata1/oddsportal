//import "/node_modules/bootstrap/scss/bootstrap.scss";
//import "/node_modules/startbootstrap-sb-admin/src/scss/styles.scss";
import "/node_modules/startbootstrap-sb-admin/dist/css/styles.css";

import './assets/styles.scss';
// Import all of Bootstrap's JS
import * as bootstrap from 'bootstrap';


import { createApp } from 'vue';
import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import { i18n }from './i18n.js';
import App from './App.vue';
import router from './router';
import apiPrivate from '/assets/api/apiPrivate.js';
import useUserStore from '/assets/stores/user.js';

const app = createApp(App);

const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

app.use(pinia);
app.use(i18n);

const store = useUserStore();
async function initApp() {
    if (store.getToken) {
        try {
            const response = await apiPrivate().get('/api/account');
            if (response.data?.email) {
                store.updateData(response.data);
                store.setAuth(true);
            } else {
                store.resetState();
            }
        } catch (error) {
            console.error("Błąd autoryzacji", error);
        }
    }

    app.use(router);
    app.mount('#app');
}

initApp();
