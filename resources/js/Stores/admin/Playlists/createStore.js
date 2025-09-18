import { defineStore } from 'pinia'

import i18n from '../../../i18n';
const t = i18n.global.t;

export const useCreatePlaylistStore = defineStore('CreatePlaylist', {
    state: () => {
        return {
            // constants start
            PageTitleCreate: 'ایجاد پلی لیست',
            PageTitleEdit: "ویرایش پلی لیست",
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
