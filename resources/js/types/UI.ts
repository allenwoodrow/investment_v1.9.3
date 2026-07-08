interface MenuGroup {
    label: string
    labelKey?: string
    icon?: string
    items: Array<MenuItem>
    separator?: string | undefined
}

export interface MenuCallback { (): void }

interface MenuItem {
    label: string
    labelKey?: string
    icon: string
    to?: {
        name: string
        params?: Object
    }
    url?: string | undefined
    preventExact?: boolean
    class?: string
    badge?: string | undefined
    visible?: boolean | true
    items?: Array<MenuItem>
    disabled?: boolean | false
    target?: string
    callback?: MenuCallback
    beta?: boolean | false
}

interface Testimonial {
    name: string
    testimony: string
    location: string
    image: string
}

export type { MenuGroup, MenuItem, Testimonial }