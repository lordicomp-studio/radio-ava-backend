<template>
    <section class="themeSettings p-6 rounded bg-white">
        <div
            v-if="settingsStore.showFullScreenLoader"
            class="fixed top-0 right-0 w-[100%] h-[100%] z-[101]"
        >
            <Loader class="rounded-lg" :isActive="settingsStore.showFullScreenLoader"/>
        </div>

        <div class="">
            <h4 class="text-sm mb-2 text-[#3f4254]">
                {{ $t('forms.themeSettings.chosenProduct') }}
            </h4>
            <ItemSelector v-model="settingsStore.newSettings.promotedProduct" searchLink="/admin/products/indexDataApi"/>
        </div>

        <div class="mt-7 border-2 border-dashed py-2 px-1">
            <h4 class="text-sm mb-2 text-[#3f4254]">
                {{ $t('forms.themeSettings.specialProducts') }}
            </h4>
            <div
                v-for="(item, index) in settingsStore.newSettings.featuredProducts"
                class="mt-2 grid grid-cols-[1fr,auto] gap-2"
            >
                <ItemSelector v-model="settingsStore.newSettings.featuredProducts[index]" searchLink="/admin/products/indexDataApi" />

                <div class="hover:cursor-pointer grid items-center" @click="settingsStore.removeFeaturedProduct(index)">
                    <SvgComponent name="fi-rr-cross-circle" size="2rem"
                                  class="transition-color duration-300 ease-in-out fill-red-500"/>
                </div>
            </div>
            <button @click="settingsStore.addFeaturedProduct" class="btn btn-primary btn-active-primary mt-3">{{ $t('tables.common.add') }}</button>
        </div>

        <div class="grid grid-cols-[auto,auto] gap-4 mt-8 justify-start">
            <button @click="cancelMethod" class="btn btn-light btn-active-light">{{ $t('forms.common.revert') }}</button>
            <button @click="sendSettingsMethod" class="btn btn-primary btn-active-primary">{{ $t('forms.common.save') }}</button>
        </div>
    </section>
</template>

<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {useHeaderStore} from "../../../Stores/admin/HeaderStore";
import {onBeforeMount, reactive, ref} from "vue";
import {useThemeSettingsStore} from "../../../Stores/admin/settings/ThemeSettingsStore";
import Loader from "@/Components/admin/Widgets/Loader.vue";
import ItemSelector from "../../../Components/admin/Widgets/Input/ItemSelector.vue";
import SvgComponent from "@/Components/admin/Widgets/SvgComponent.vue";
import iziToast from "izitoast";
import 'izitoast/dist/css/iziToast.min.css';

export default {
    components: {SvgComponent, ItemSelector, Loader},
    layout : AdminLayout,
    props:{
        settings:{
            type: Object,
        },
    },
    setup(props){
        const settingsStore = useThemeSettingsStore(); // 'settingsStore' is in fact 'themeSettingsStore'
        const headerStore = useHeaderStore();

        onBeforeMount(()=>{
            settingsStore.settings = props.settings;
            settingsStore.extractKeyValues();

            headerStore.title = settingsStore.PageTitle;
        })

        const sendSettingsMethod = ()=>{
            settingsStore.showFullScreenLoader = true;

            axios.post('/admin/settings/theme', {
                theme_promoted_product_id: settingsStore.newSettings.promotedProduct.id,
                theme_featured_products_ids: settingsStore.newSettings.featuredProducts.filter((item)=>{
                    return item != null; // removes null items(null items are selects that haven't selected anything)
                }).map((item)=>{
                    return item.id; // returns id of selected products
                }),
            }).then((res)=>{
                settingsStore.showFullScreenLoader = false;

                if (res.status === 200){
                    settingsStore.settings = JSON.parse(JSON.stringify(settingsStore.newSettings));
                    iziToast.success({title: t('toasts.success'), message: t('toasts.editedSuccessfully'),});
                }
            });
        };

        const cancelMethod = ()=>{
            console.log('asd')
            settingsStore.extractKeyValues();
            iziToast.success({title: t('toasts.success'), message: t('toasts.revertedSuccessfully'),});
        };

        return {
            settingsStore,
            sendSettingsMethod,
            cancelMethod,
        };
    },
}
</script>
