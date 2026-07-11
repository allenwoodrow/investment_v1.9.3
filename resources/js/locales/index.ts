import { createI18n } from 'vue-i18n';
import en from './en';
import es from './es';
import pt from './pt';

// Get the saved locale from localStorage, or default to English.
function getSavedLocale(): string {
  const preferred = localStorage.getItem('app_locale_preferred');
  if (preferred === '1') {
    const saved = localStorage.getItem('app_locale');
    if (saved && ['pt', 'en', 'es'].includes(saved)) {
      return saved;
    }
  }

  const browserLang = navigator.language.split('-')[0];
  if (browserLang === 'es') return 'es';
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
