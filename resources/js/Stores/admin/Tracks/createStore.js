import { defineStore } from 'pinia'

import i18n from '../../../i18n';
const t = i18n.global.t;

export const useCreateTrackStore = defineStore('CreateTrack', {
    state: () => {
        return {
            // constants start
            PageTitleCreate: "ایجاد آهنگ",
            PageTitleEdit: "ویرایش آهنگ",
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
