import { defineStore } from 'pinia'

import i18n from '../../../i18n';
const t = i18n.global.t;

export const useCreateProductStore = defineStore('CreateProduct', {
    state: () => {
        return {
            // constants start
            PageTitleCreate: t('pages.CreateProduct'),
            PageTitleEdit: t('pages.EditProduct'),
            PageTitleConfirmation: t('pages.ConfirmProduct'),
            PageTitleEditRequest: t('pages.ReviewProductEditRequest'),
            // constants end
            newData: {},
            photo: null,
            showFullScreenLoader: false,
            errors: {},
        }
    },
    actions: {
        resetFormValues(){
            this.newData = {status: 'در انتظار بررسی'};
            this.photo = null;
        },

    },
})
