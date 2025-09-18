import { defineStore } from 'pinia'
import {ref} from "vue";

export const useFileSettingsStore = defineStore('FileSettings', {
    state: () => {
        return {
            // constants start
            allFolderingOptions: ['سال', 'ماه', 'روز', 'آیدی آپلود کننده', 'فرمت فایل'],
            // constants end
            settings: {
                file_acceptedFormats: [],
                file_uploadLimit: 0,
                file_chosenFoldering: [],
            },
            newSettings: {
                file_acceptedFormats: [],
                file_uploadLimit: 0,
                file_chosenFoldering: [],
            },
        }
    },
    actions: {
        extractKeyValues(){
            for (const key in this.settings) {
                if (this.settings[key].key === 'file_acceptedFormats'){
                    this.newSettings.file_acceptedFormats = JSON.parse(this.settings[key].value);
                }
                else if(this.settings[key].key === 'file_chosenFoldering'){
                    this.newSettings.file_chosenFoldering = JSON.parse(this.settings[key].value);
                }
                else {
                    this.newSettings[this.settings[key].key] = this.settings[key].value;
                }
            }
        },
    },
})
