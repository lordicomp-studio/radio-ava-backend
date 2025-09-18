import { defineStore } from 'pinia'

import i18n from '../../../i18n';
const t = i18n.global.t;

export const useCreateArtistStore = defineStore('CreateArtist', {
    state: () => {
        return {
            // constants start
            PageTitleCreate: 'افزودن هنرمند',
            PageTitleEdit: 'ویرایش هنرمند',
            // constants end
            newData: {},
            photo: null,
            country: null,
            showFullScreenLoader: false,
        }
    },
    actions: {
        resetFormValues(){
            this.newData = {};
            this.photo = null;
        },
    },
})
