import { defineStore } from 'pinia'

import i18n from '../../../i18n';
const t = i18n.global.t;

export const useGeneralSettingsStore = defineStore('GeneralSettings', {
    state: () => {
        return {
            // constants start
            PageTitle: `${t('pages.GeneralSettings')} Settings`,
            // constants end
            settings: {},
            newSettings: {},
            mediumPicker: {
                showModal: false,
                valueKey: null,
            },
            showFullScreenLoader: false,
        }
    },
    actions: {
        extractKeyValues(){
            for (const key in this.settings) {
                if (this.settings[key].key === 'logo_id'){
                    this.newSettings['logo'] = this.settings[key];
                }
                else if(this.settings[key].key === 'favicon_id'){
                    this.newSettings['favicon'] = this.settings[key];
                }
                else {
                    this.newSettings[this.settings[key].key] = this.settings[key].value;
                }
            }
        },
        openModal(valueKey){
            this.mediumPicker.valueKey = valueKey;
            this.mediumPicker.showModal = true;
        },
        closeModal(){
            this.mediumPicker.showModal = false;
        },
        receiveMedium(medium){
            this.newSettings[this.mediumPicker.valueKey] = medium;
            this.closeModal();
        },
        removePhoto(){
            this.newSettings[this.mediumPicker.valueKey] = null;
            this.closeModal();
        },
    },
})
