import { defineStore } from 'pinia'

import i18n from '../../../i18n';
const t = i18n.global.t;

export const useCreatePageStore = defineStore('CreatePage', {
    state: () => {
        return {
            // constants start
            PageTitleCreate: t('pages.CreatePage'),
            PageTitleEdit: t('pages.EditPage'),
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
