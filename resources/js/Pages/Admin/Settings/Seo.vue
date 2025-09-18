<template>
    <section class="publicSettings p-6 rounded bg-white">
        <div
            v-if="settingsStore.showFullScreenLoader"
            class="fixed top-0 right-0 w-[100%] h-[100%] z-[101]"
        >
            <Loader class="rounded-lg" :isActive="settingsStore.showFullScreenLoader"/>
        </div>

        <h3 class="text-lg py-2">{{ $t('forms.seoSettings.general') }}</h3>

        <div class="">
            <h4 class="text-sm mb-2 text-[#3f4254]">{{ $t('forms.seoSettings.titleTag') }}</h4>
            <input v-model="settingsStore.newSettings.meta_title" :disabled="checkIsActiveMethod('meta_title')"
                   type="text" class="input-text w-full !p-2 disabled:bg-slate-100" :placeholder="`${$t('forms.seoSettings.titleTag')}...`">
            <div class="mt-2 flex gap-2">
                <label class="text-xs text-[#a1a5b7] font-light">{{$t('forms.seoSettings.similarToGeneralSettings')}}</label>
                <input @change="setLikeGeneralSettings('meta_title', 'title')"
                       v-model="isLikeGeneralCheckArray" value="meta_title"
                       type="checkbox" class="input-check input-check-sm inline-block">
            </div>
        </div>

        <div class="mt-7">
            <h4 class="text-sm mb-2 text-[#3f4254]">
                {{ $t('forms.seoSettings.descriptionTag') }}
            </h4>
            <input v-model="settingsStore.newSettings.meta_description" :disabled="checkIsActiveMethod('meta_description')"
                   type="text" class="input-text w-full !p-2 disabled:bg-slate-100" :placeholder="`${$t('forms.seoSettings.descriptionTag')}...`">
            <div class="mt-2 flex gap-2">
                <label class="text-xs text-[#a1a5b7] font-light">{{$t('forms.seoSettings.similarToGeneralSettings')}}</label>
                <input @change="setLikeGeneralSettings('meta_description', 'description')"
                       v-model="isLikeGeneralCheckArray" value="meta_description"
                       type="checkbox" class="input-check input-check-sm inline-block">
            </div>
        </div>

        <h3 class="text-lg mt-4 py-2">{{ $t('forms.seoSettings.openGraphProtocol') }}</h3>

        <div class="">
            <h4 class="text-sm mb-2 text-[#3f4254]">{{ $t('forms.seoSettings.protocolTitle') }}</h4>
            <input v-model="settingsStore.newSettings.ogp_title" :disabled="checkIsActiveMethod('ogp_title')"
                   type="text" class="input-text w-full !p-2 disabled:bg-slate-100" :placeholder="`${$t('forms.seoSettings.protocolTitle')}...`">
            <div class="mt-2 flex gap-2">
                <label class="text-xs text-[#a1a5b7] font-light">{{$t('forms.seoSettings.similarToGeneralSettings')}}</label>
                <input @change="setLikeGeneralSettings('ogp_title', 'title')"
                       v-model="isLikeGeneralCheckArray" value="ogp_title"
                       type="checkbox" class="input-check input-check-sm inline-block">
            </div>
        </div>

        <div class="mt-7">
            <h4 class="text-sm mb-2 text-[#3f4254]">{{ $t('forms.seoSettings.protocolDescription') }}</h4>
            <input v-model="settingsStore.newSettings.ogp_description" :disabled="checkIsActiveMethod('ogp_description')"
                   type="text" class="input-text w-full !p-2 disabled:bg-slate-100" :placeholder="`${$t('forms.seoSettings.protocolDescription')}...`">
            <div class="mt-2 flex gap-2">
                <label class="text-xs text-[#a1a5b7] font-light">{{$t('forms.seoSettings.similarToGeneralSettings')}}</label>
                <input @change="setLikeGeneralSettings('ogp_description', 'description')"
                       v-model="isLikeGeneralCheckArray" value="ogp_description"
                       type="checkbox" class="input-check input-check-sm inline-block">
            </div>
        </div>

        <div class="logo mt-2">
            <h4 class="text-sm mb-2 text-[#3f4254]">
                {{ $t('forms.seoSettings.protocolPhoto') }}
            </h4>
            <div class="grid grid-cols-[auto,auto] justify-start gap-4 items-end">
                <figure class="h-32 w-32 border-2 border-[#e4e6ef]">
                    <img class="object-contain" :src="`/storage${settingsStore.newSettings?.ogpPicture?.url}`" alt="">
                </figure>
                <button @click="settingsStore.openModal('ogpPicture')" class="btn btn-light btn-active-light ml-4">
                    {{$t('forms.common.change')}}
                </button>
            </div>
        </div>


        <div class="grid grid-cols-[auto,auto] gap-4 mt-8 justify-start">
            <button @click="cancelMethod" class="btn btn-light btn-active-light">{{ $t('forms.common.revert') }}</button>
            <button @click="sendSettingsMethod" class="btn btn-primary btn-active-primary">{{ $t('forms.common.save') }}</button>
        </div>
        <SelectMediumModal
            :is-active="settingsStore.mediumPicker.showModal"
            :click-outside-method="settingsStore.closeModal"
            :close-btn-method="settingsStore.closeModal"
            :receive-medium-method="settingsStore.receiveMedium"
            :remove-photo-method="settingsStore.removePhoto"
        />
    </section>
</template>

<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import SelectMediumModal from "../../../Components/admin/Widgets/SelectMediumModal.vue";
import {onBeforeMount, ref} from "vue";
import {useSeoSettingsStore} from "../../../Stores/admin/settings/SeoSettingsStore";
import iziToast from "izitoast";
import {useHeaderStore} from "../../../Stores/admin/HeaderStore";
import Loader from "@/Components/admin/Widgets/Loader.vue";
import i18n from '../../../i18n';
const t = i18n.global.t;

export default {
    components: {Loader, SelectMediumModal},
    layout : AdminLayout,
    props:{
        generalSettings:{
            type: Object,
        },
        settings:{
            type: Object,
        },
    },
    setup(props){
        const settingsStore = useSeoSettingsStore(); // 'settingsStore' is in fact 'seoSettingsStore'
        const headerStore = useHeaderStore();
        const isLikeGeneralCheckArray = ref([]);


        const sendSettingsMethod = ()=>{
            settingsStore.showFullScreenLoader = true;

            axios.post('/admin/settings/seo', {
                metaTitle: settingsStore.newSettings.meta_title,
                metaDescription: settingsStore.newSettings.meta_description,
                ogpTitle: settingsStore.newSettings.ogp_title,
                ogpDescription: settingsStore.newSettings.ogp_description,
                ogp_picture_id: settingsStore.newSettings.ogpPicture?.id ?? 0,
            }).then((res)=>{
                settingsStore.showFullScreenLoader = false;

                if (res.status === 200){
                    settingsStore.settings = settingsStore.newSettings;
                    iziToast.success({title: t('toasts.success'), message: t('toasts.editedSuccessfully'),});
                }
            });
        };

        const cancelMethod = ()=>{
            settingsStore.extractKeyValues();
            iziToast.success({title: t('toasts.success'), message: t('toasts.revertedSuccessfully'),});
        };

        const setLikeGeneralSettings = (valueName, key)=>{
            let generalSettingValue = '';
            for (const index in props.generalSettings) {
                if (props.generalSettings[index].key === key){
                    settingsStore.newSettings[valueName] = props.generalSettings[index].value;
                    // console.log(props.generalSettings[index].value);
                }
            }
        }

        const checkIsActiveMethod = (inputName)=>{
            return isLikeGeneralCheckArray.value.includes(inputName);
        }

        onBeforeMount(()=>{
            settingsStore.settings = props.settings;
            settingsStore.extractKeyValues();

            headerStore.title = settingsStore.PageTitle;
        });

        return {
            settingsStore,
            sendSettingsMethod,
            cancelMethod,
            setLikeGeneralSettings,
            isLikeGeneralCheckArray,
            checkIsActiveMethod,
        }
    },
}
</script>
