import { createI18n } from 'vue-i18n'
import vi from './locales/vi.json'
import en from './locales/en.json'

const i18n = createI18n({
    legacy: false,
    globalInjection: false,
    escapeParameter: true,
    locale: import.meta.env.VITE_I18N_LOCALE || 'vi',
    fallbackLocale: import.meta.env.VITE_I18N_FALLBACK_LOCALE || 'en',
    messages: {
        vi,
        en
    }
})

export default i18n