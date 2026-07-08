import { useNotification } from "@kyvg/vue3-notification";
import i18n from '@/locales';

const { notify } = useNotification()

export class Notify {
    error(message: string) {
        notify({
            title: i18n.global.t('notifications.error'),
            text: message,
            type: 'error'
        })
    }

    success(message: string) {
        notify({
            title: i18n.global.t('notifications.success'),
            text: message,
            type: 'success'
        })
    }

    info(title: string, message: string) {
        notify({
            title: title,
            text: message,
            type: 'info'
        })
    }
}

export const toast = new Notify()