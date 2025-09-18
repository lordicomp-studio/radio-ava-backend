import { defineStore } from 'pinia'

import i18n from '../../../i18n';
const t = i18n.global.t;

export const useThemeSettingsStore = defineStore('ThemeSettings', {
    state: () => {
        return {
            // constants start
            PageTitle: `${t('pages.ThemeSettings')} Settings`,
            // constants end
            settings: {},
            newSettings: {
                promotedProduct: null,
                featuredProducts: [],
            },
            showFullScreenLoader: false,
        }
    },
    actions: {
        extractKeyValues(){
            this.newSettings.promotedProduct = cloneData(this.settings.promotedProduct);
            this.newSettings.featuredProducts = cloneData(this.settings.featuredProducts);
            // for (const key in this.settings) {
            //     this.newSettings[this.settings[key].key] = this.settings[key].value;
            // }
        },
        addFeaturedProduct(){
            this.newSettings.featuredProducts.push(null);
        },
        removeFeaturedProduct(index){
            this.newSettings.featuredProducts.splice(index, 1);
        }
    },
})

function cloneData(data){
    return JSON.parse(JSON.stringify(data));
}
