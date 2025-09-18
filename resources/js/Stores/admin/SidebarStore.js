import { defineStore } from 'pinia'
import {ref} from "vue";

export const useSidebarStore = defineStore('Sidebar', {
    state: () => {
        return {
            openedMenuName: '',
            currentPageLink: '',
        }
    },
    actions: {

    },
})
