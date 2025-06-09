import { createI18n } from 'vue-i18n';
import EN from './locale/en.json';
import PL from './locale/pl.json';

const i18n = createI18n({
    legacy: false, // allow to use i18n in composition api
    locale: localStorage.getItem('oddsportal|locale') || 'en', // set locale
    fallbackLocale: 'en', // set fallback locale
    messages: {
        en: EN,
        pl: PL,
    }
});

export {i18n};