<template>
    <CustomModal
        :is-active="store.showCreateSubmenuModal"
        @clickOutside="store.closeSubmenuModal"
    >
        <template v-slot:title>
            <div class="grid grid-cols-[auto,auto] justify-between items-center">
                <strong v-if="!store.editSubmenu.isEdit" class="text-[1.2rem] leading-[35px]">{{ $t('forms.menuSettings.addSubmenu') }}</strong>
                <strong v-else class="text-[1.2rem] leading-[35px]">{{ $t('forms.menuSettings.editSubmenu') }}</strong>

                <div class="no-bg-svg-btn-blue" @click="store.closeSubmenuModal">
                    <SvgComponent name="fi-rr-cross" size=".7rem" color="#aaa"/>
                </div>
            </div>
        </template>
        <template v-slot:body>
            <div class="w-[500px] text-start">
                <h4 class="text-base font-medium mb-2 text-[#3f4254]">
                    {{ $t('forms.common.title') }}
                    <span class="text-danger">*</span>
                </h4>
                <input type="text" class="input-text input-text-solid w-full"
                       v-model="newSubmenu.title" :placeholder="`${$t('forms.common.title')}...`">
                <p v-if="error['title']" class="text-danger">{{ error['title'][0] }}</p>
            </div>
            <div class="w-[500px] mt-4 text-start">
                <h4 class="text-base font-medium mb-2 text-[#3f4254]">
                    {{ $t('forms.menuSettings.link') }}
                    <span class="text-danger">*</span>
                </h4>
                <input type="text" class="input-text input-text-solid w-full"
                       v-model="newSubmenu.link" :placeholder="`${$t('forms.menuSettings.link')}...`">
                <p v-if="error['link']" class="text-danger">{{ error['link'][0] }}</p>
            </div>
        </template>
        <template v-slot:actions>
            <div class="grid grid-cols-[auto,auto] justify-center gap-4">
                <button @click="store.closeSubmenuModal"
                        class="btn btn-light btn-active-light">{{ $t('forms.common.close') }}</button>
                <button @click="sendSubmenuData"
                        class="btn btn-primary btn-active-primary">{{ $t('forms.common.save') }}</button>
            </div>
        </template>
    </CustomModal>
</template>

<script>
import SvgComponent from "../Widgets/SvgComponent.vue";
import CustomModal from "../Widgets/Modal/CustomModal.vue";
import SelectBasic from "../Widgets/Input/Selects/SelectBasic.vue";
import {onMounted, ref, watch} from "vue";
import {useMenuSettingsStore} from "../../../Stores/admin/settings/MenuSettingsStore";
import iziToast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css';

export default {
    name: "CreateSubmenuModal",
    components: {SelectBasic, CustomModal, SvgComponent},
    setup(){
        const store = useMenuSettingsStore();
        const newSubmenu = ref({});
        const error = ref({});


        // this watch is triggered whenever the modal opens up
        watch(() => store.showCreateSubmenuModal, (value)=>{
            if (value){
                if (store.editSubmenu.isEdit){
                    newSubmenu.value.title = store.editSubmenu.toBeEdited.title;
                    newSubmenu.value.link = store.editSubmenu.toBeEdited.url;
                }else {
                    newSubmenu.value.title = '';
                    newSubmenu.value.link = '';
                }
            }
        });

        const sendSubmenuData = ()=>{
            if (store.editSubmenu.isEdit){
                store.editSubmenu.toBeEdited.title = newSubmenu.value.title;
                store.editSubmenu.toBeEdited.url = newSubmenu.value.link;
            }
            else {
                let newItem = {title: newSubmenu.value.title, url: newSubmenu.value.link, submenus: []};
                store.addSubmenuItem(newItem);
            }

            store.closeSubmenuModal();
        };


        return{
            store,
            newSubmenu,
            error,
            sendSubmenuData,
        }
    }
}
</script>

<style scoped>

</style>
