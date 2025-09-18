import { defineStore } from 'pinia'

import i18n from '../../../i18n';
const t = i18n.global.t;

export const useCreateUserStore = defineStore('CreateUser', {
    state: () => {
        return {
            // constants start
            PageTitleCreate: t('pages.CreateUser'),
            PageTitleEdit: t('pages.EditUser'),
            // constants end
            newUser: {},
            profilePhoto: null,
            roleName: 'normalUser',
            showFullScreenLoader: false,
            allLevels: [], // passed from backend
        }
    },
    actions: {
        resetFormValues(){
            this.newUser = {level: this.allLevels.Client};
            this.profilePhoto = null;
            this.roleName = 'normalUser';
        },
    },
})
