<template>
    <CustomModal
        :is-active="fileManagerStore.showCreateDirModal"
        @clickOutside="fileManagerStore.closeCreateDirModal"
    >
        <template v-slot:title>
            <div class="grid grid-cols-[auto,auto] justify-between items-center">
                <strong class="text-[1.2rem] leading-[35px]">{{$t('forms.fileManager.createNewFolder')}}</strong>

                <div class="no-bg-svg-btn-blue" @click="fileManagerStore.closeCreateDirModal">
                    <SvgComponent name="fi-rr-cross" size=".7rem" color="#aaa"/>
                </div>
            </div>
        </template>
        <template v-slot:body>
            <div class="w-[500px]">
                <h4 class="text-base font-medium mb-2 text-[#3f4254] text-start">
                    {{ $t('forms.common.name') }}
                    <span class="text-danger">*</span>
                </h4>
                <input type="text" class="input-text input-text-solid w-full"
                       v-model="newDirName" :placeholder="`${$t('forms.common.name')}...`">
                <p v-if="error['folderName']" class="text-danger text-start">{{ error['folderName'][0] }}</p>
            </div>
        </template>
        <template v-slot:actions>
            <div class="grid grid-cols-[auto,auto] justify-center gap-4">
                <button @click="fileManagerStore.closeCreateDirModal"
                        class="btn btn-light btn-active-light">{{$t('forms.common.close')}}</button>
                <button @click="sendDataMethod"
                        class="btn btn-primary btn-active-primary">{{$t('forms.common.save')}}</button>
            </div>
        </template>
    </CustomModal>
</template>

<script>
import SvgComponent from "../Widgets/SvgComponent.vue";
import CustomModal from "../Widgets/Modal/CustomModal.vue";
import {useFileManagerStore} from "../../../Stores/admin/FileManager/FileManagerStore";
import {onMounted, ref, watch} from "vue";

import iziToast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css';

export default {
    name: "CreateDirectoryModal",
    components: {CustomModal, SvgComponent},
    setup(){
        const fileManagerStore = useFileManagerStore();
        const newDirName = ref('');
        const error = ref({});

        const sendDataMethod = ()=>{
            fileManagerStore.showFullScreenLoader = true;

            axios.post('/admin/fileManager/makeFolder', {
                folderName: newDirName.value,
                path: fileManagerStore.path,
            }).then((res)=>{
                    fileManagerStore.showFullScreenLoader = false;
                    if (res.status === 200){
                        iziToast.success({title: t('toasts.success'), message: t('toasts.createdSuccessfully'),});

                        newDirName.value = '';
                        error.value = {};
                        fileManagerStore.closeCreateDirModal();
                    }
                })
                .catch((_error) => {
                    fileManagerStore.showFullScreenLoader = false;
                    error.value = _error.response.data.errors;
                    iziToast.error({title: t('toasts.error'), message: t('toasts.formDataFlawed')});
                });


            fileManagerStore.updateTableData();
        };

        return{
            fileManagerStore,
            newDirName,
            error,
            sendDataMethod,
        }
    }
}
</script>

<style scoped>

</style>
