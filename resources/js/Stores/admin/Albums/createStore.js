import { defineStore } from 'pinia'

import i18n from '../../../i18n';
const t = i18n.global.t;

export const useCreateAlbumStore = defineStore('CreateAlbum', {
    state: () => {
        return {
            // constants start
            PageTitleCreate: "ایجاد آلبوم",
            PageTitleEdit: "ویرایش آلبوم",
            // constants end
            newData: {},
            photo: null,
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
