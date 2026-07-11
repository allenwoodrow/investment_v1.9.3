import { createI18n } from 'vue-i18n';
import en from './en';
import es from './es';
import pt from './pt';

// Start in English and ignore any previously persisted Portuguese preference.
function getSavedLocale(): string {
  const saved = localStorage.getItem('app_locale');
  if (saved && ['en', 'es'].includes(saved)) {
    return saved;
  }

  localStorage.setItem('app_locale', 'en');
  localStorage.setItem('app_locale_preferred', '0');
  return 'en';
}

const i18n = createI18n({
  legacy: false,
  locale: getSavedLocale(),
  fallbackLocale: 'en',
  messages: {
    en,
    es,
    pt,
  },
});

export function setLocale(locale: 'pt' | 'en' | 'es') {
  i18n.global.locale.value = locale;
  localStorage.setItem('app_locale', locale);
  localStorage.setItem('app_locale_preferred', '1');
  // Also sync with backend
  fetch('/language/switch', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
    },
    body: JSON.stringify({ locale }),
  }).catch(() => {});
}

export function getCurrentLocale(): string {
  return i18n.global.locale.value;
}

export default i18n;
