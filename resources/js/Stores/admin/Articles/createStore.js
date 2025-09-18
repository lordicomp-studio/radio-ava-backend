import { defineStore } from 'pinia'

import i18n from '../../../i18n';
const t = i18n.global.t;

export const useCreateArticleStore = defineStore('CreateArticle', {
    state: () => {
        return {
            // constants start
            PageTitleCreate: t('pages.CreateArticle'),
            PageTitleEdit: t('pages.EditArticle'),
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
