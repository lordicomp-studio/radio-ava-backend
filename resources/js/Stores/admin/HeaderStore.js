import { defineStore } from 'pinia'
import {ref} from "vue";

export const useHeaderStore = defineStore('Header', {
    state: () => {
        return {
            title: '',
        }
    },
    actions: {

    },
})
