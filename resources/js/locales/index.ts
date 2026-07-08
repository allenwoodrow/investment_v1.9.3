import { createI18n } from 'vue-i18n';
import en from './en';
import es from './es';
import pt from './pt';

// Get saved locale from localStorage or default to Portuguese
function getSavedLocale(): string {
  const saved = localStorage.getItem('app_locale');
  if (saved && ['pt', 'en', 'es'].includes(saved)) {
    return saved;
  }
  // Try browser language
  const browserLang = navigator.language.split('-')[0];
  if (browserLang === 'pt') return 'pt';
  if (browserLang === 'es') return 'es';
  // Default to Portuguese
  return 'pt';
}

const i18n = createI18n({
  legacy: false,
  locale: getSavedLocale(),
  fallbackLocale: 'pt',
  messages: {
    en,
    es,
    pt,
  },
});

export function setLocale(locale: 'pt' | 'en' | 'es') {
  i18n.global.locale.value = locale;
  localStorage.setItem('app_locale', locale);
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
