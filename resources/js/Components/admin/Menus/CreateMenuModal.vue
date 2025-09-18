<template>
    <CustomModal
        :is-active="store.showCreateMenuModal"
        @clickOutside="store.closeMenuModal"
    >
        <template v-slot:title>
            <div class="grid grid-cols-[auto,auto] justify-between items-center">
                <strong v-if="!store.editMenu.isEdit" class="text-[1.2rem] leading-[35px]">
                    {{ $t('tables.common.add') }} {{ $t('forms.menuSettings.menu') }}
                </strong>
                <strong v-else class="text-[1.2rem] leading-[35px]">{{ $t('tables.common.edit') }} {{ $t('forms.menuSettings.menu') }}</strong>

                <div class="no-bg-svg-btn-blue" @click="store.closeMenuModal">
                    <SvgComponent name="fi-rr-cross" size=".7rem" color="#aaa"/>
                </div>
            </div>
        </template>
        <template v-slot:body>
            <div class="w-[500px] text-start">
                <h4 class="text-base font-medium mb-2 text-[#3f4254]">
                    {{ $t('forms.menuSettings.menuTitle') }}
                    <span class="text-danger">*</span>
                </h4>
                <input type="text" class="input-text input-text-solid w-full"
                       v-model="newMenu.title" :placeholder="`${$t('forms.menuSettings.menuTitle')}...`">
                <p v-if="error['title']" class="text-danger">{{ error['title'][0] }}</p>
            </div>
            <div class="w-[500px] mt-4 text-start">
                <h4 class="text-base font-medium mb-2 text-[#3f4254]">
                    {{ $t('forms.menuSettings.menuName') }}
                    <span class="text-danger">*</span>
                </h4>
                <input type="text" class="input-text input-text-solid w-full"
                       v-model="newMenu.name" :placeholder="`${$t('forms.menuSettings.menuTitle')}...`">
                <p v-if="error['name']" class="text-danger">{{ error['name'][0] }}</p>
            </div>
        </template>
        <template v-slot:actions>
            <div class="grid grid-cols-[auto,auto] justify-center gap-4">
                <button @click="store.closeMenuModal"
                        class="btn btn-light btn-active-light">{{ $t('forms.common.close') }}</button>
                <button @click="sendDataMethod"
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
import i18n from '../../../i18n';
const t = i18n.global.t;
import iziToast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css';

export default {
    name: "CreateMenuModal",
    components: {SelectBasic, CustomModal, SvgComponent},
    setup(){
        const store = useMenuSettingsStore();
        const newMenu = ref({});
        const error = ref({});


        // this watch is triggered whenever the modal opens up
        watch(() => store.showCreateMenuModal, (value)=>{
            if (value){
                if (store.editMenu.isEdit){
                    newMenu.value.name = store.editMenu.toBeEdited.name;
                    newMenu.value.title = store.editMenu.toBeEdited.title;
                }else {
                    newMenu.value.name = '';
                    newMenu.value.title = '';
                }
            }
        });

        const sendDataMethod = async ()=>{
            store.showFullScreenLoader = true;

            if (store.editMenu.isEdit){
                let menuId = store.editMenu.toBeEdited.id;

                await axios.put(`/admin/menus/${menuId}`, newMenu.value)
                    .then((res)=>{
                        store.showFullScreenLoader = false;

                        if (res.status === 200){
                            iziToast.success({title: t('toasts.success'), message: t('toasts.editedSuccessfully'),});
                            store.closeMenuModal();
                        }
                    })
                    .catch((_error) => {
                        store.showFullScreenLoader = false;
                        error.value = _error.response.data.errors;
                        iziToast.error({title: t('toasts.error'), message: t('toasts.formDataFlawed')});
                    });
            }
            else {
                await axios.post('/admin/menus', newMenu.value)
                    .then((res)=>{
                        store.showFullScreenLoader = false;

                        if (res.status === 200){
                            iziToast.success({title: t('toasts.success'), message: t('toasts.createdSuccessfully'),});
                            newMenu.value = {};
                            error.value = {};
                        }
                    })
                    .catch((_error) => {
                        store.showFullScreenLoader = false;
                        error.value = _error.response.data.errors;
                        iziToast.error({title: t('toasts.error'), message: t('toasts.formDataFlawed')});
                    });
            }

            store.updateMenusData();
        };

        return{
            store,
            newMenu,
            error,
            sendDataMethod,
        }
    }
}
</script>

<style scoped>

</style>
