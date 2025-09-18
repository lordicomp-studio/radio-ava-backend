<template>
    <section class="generalSettings p-6 rounded bg-white">
        <div
            v-if="settingsStore.showFullScreenLoader"
            class="fixed top-0 right-0 w-[100%] h-[100%] z-[101]"
        >
            <Loader class="rounded-lg" :isActive="settingsStore.showFullScreenLoader"/>
        </div>

        <div class="">
            <h4 class="text-sm mb-2 text-[#3f4254]">
                {{$t('forms.generalSettings.websiteTitle')}}
            </h4>
            <input
                type="text"
                v-model="settingsStore.newSettings.title"
                class="input-text w-full !p-2"
                :placeholder="$t('forms.generalSettings.websiteTitle')"
            >
            <p class="text-xs mt-2 text-[#a1a5b7] font-light">{{$t('forms.generalSettings.websiteTitleComment')}}</p>
        </div>

        <div class="mt-7">
            <h4 class="text-sm mb-2 text-[#3f4254]">
                {{$t('forms.generalSettings.websiteDescription')}}
            </h4>
            <input
                type="text"
                v-model="settingsStore.newSettings.description"
                class="input-text w-full !p-2"
                :placeholder="$t('forms.generalSettings.websiteDescription')"
            >
            <p class="text-xs mt-2 text-[#a1a5b7] font-light">{{$t('forms.generalSettings.websiteDescriptionComment')}}</p>
        </div>

        <div class="mt-7 grid grid-cols-[auto,auto] justify-start items-center gap-x-4">
            <input v-model="settingsStore.newSettings.appDebug" type="checkbox" class="input-check inline-block">
            <h4 class="text-sm text-[#3f4254]">
                {{$t('forms.generalSettings.debugMode')}}
                <span class="text-danger">*</span>
            </h4>
            <p class="text-xs mt-2 text-[#a1a5b7] font-light col-span-4">
                {{$t('forms.generalSettings.debugModeComment')}}
            </p>
        </div>

        <div class="mt-7 grid grid-cols-[auto,auto]">
            <div class="logo">
                <h4 class="text-sm mb-2 text-[#3f4254]">
                    {{$t('forms.generalSettings.logo')}}
                </h4>
                <div class="grid grid-cols-[auto,auto] justify-start gap-4 items-end">
                    <figure class="h-32 w-32 border-2 border-[#e4e6ef]">
                        <img class="object-contain" :src="`/storage${settingsStore.newSettings?.logo?.url}`" alt="">
                    </figure>
                    <button class="btn btn-light btn-active-light ml-4" @click="settingsStore.openModal('logo')">
                        {{$t('forms.common.change')}}
                    </button>
                </div>
            </div>

            <div class="favicon">
                <h4 class="text-sm mb-2 text-[#3f4254]">
                    {{$t('forms.generalSettings.favicon')}}
                </h4>
                <div class="grid grid-cols-[auto,auto] justify-start gap-4 items-end">
                    <figure class="h-32 w-32 border-2 border-[#e4e6ef]">
                        <img class="object-contain" :src="`/storage${settingsStore.newSettings?.favicon?.url}`" alt="">
                    </figure>
                    <button class="btn btn-light btn-active-light ml-4" @click="settingsStore.openModal('favicon')">
                        {{$t('forms.common.change')}}
                    </button>
                </div>
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
import {useGeneralSettingsStore} from "../../../Stores/admin/settings/GeneralSettingsStore";
import {onBeforeMount, onMounted} from "vue";
import iziToast from "izitoast";
import {useHeaderStore} from "../../../Stores/admin/HeaderStore";
import Loader from "@/Components/admin/Widgets/Loader.vue";
import i18n from '../../../i18n';
const t = i18n.global.t;

export default {
    components: {Loader, SelectMediumModal},
    layout : AdminLayout,
    props:{
        settings:{
            type: Object,
        },
    },
    setup(props){
        const settingsStore = useGeneralSettingsStore(); // 'settingsStore' is in fact 'generalSettingsStore'
        const headerStore = useHeaderStore();

        const sendSettingsMethod = ()=>{
            settingsStore.showFullScreenLoader = true;

            axios.post('/admin/settings/general', {
                title: settingsStore.newSettings.title,
                description: settingsStore.newSettings.description,
                appDebug: settingsStore.newSettings.appDebug,
                logo_id: settingsStore.newSettings.logo?.id ?? 0,
                favicon_id: settingsStore.newSettings.favicon?.id ?? 0,
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

        onBeforeMount(()=>{
            settingsStore.settings = props.settings;
            settingsStore.extractKeyValues();

            headerStore.title = settingsStore.PageTitle;
        });

        return {
            settingsStore,
            sendSettingsMethod,
            cancelMethod,
        }
    },
}
</script>
