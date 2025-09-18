<template>
    <CustomModal
        :is-active="fileManagerStore.showUploadFileModal"
        @clickOutside="fileManagerStore.closeUploadFileModal"
    >
        <template v-slot:title>
            <div class="grid grid-cols-[auto,auto] justify-between items-center">
                <strong class="text-[1.2rem] leading-[35px] capitalize">{{$t('tables.fileManager.uploadFile')}}</strong>

                <div class="no-bg-svg-btn-blue" @click="fileManagerStore.closeUploadFileModal">
                    <SvgComponent name="fi-rr-cross" size=".7rem" color="#aaa"/>
                </div>
            </div>
        </template>
        <template v-slot:body>
            <div class="w-[500px]">
                <label class="btn btn-primary btn-active-primary
                        inline-block w-[500px] text-center select-none cursor-pointer"
                >
                    {{$t('forms.fileManager.chooseFile')}}
                    <input @change="getFilesMethod" type="file" :disabled="fileManagerStore.isUploading" multiple hidden>
                </label>
                <div class="progressBars mt-8">
                    <div class="pl-4 max-h-[45vh] overflow-y-auto overflow-x-hidden">
                        <div v-for="(file, index) in fileManagerStore.selectedUploadFiles">
                            <div class="info grid grid-cols-[auto,auto] justify-between">
                                <span>{{ file.name }}</span>
                                <div class="grid items-center justify-center w-[19px] h-[19px]
                                                rounded transition duration-150 group"
                                     :class="{'hover:bg-red-100 cursor-pointer' : !fileManagerStore.isUploading}"
                                     @click="fileManagerStore.removeFileFromSelectedUploadMedia(index)"
                                >
                                    <SvgComponent name="fi-rr-cross" size=".7rem" color="#aaa"
                                                  class="transition duration-150"
                                                  :class="{'group-hover:fill-red-500' : !fileManagerStore.isUploading}"
                                    />
                                </div>

                            </div>
                            <div class="relative">
                                <progress max="100" :value="file.uploadPercent.value" class="html5"></progress>
                                <span class="absolute top-[2%] right-[50%] translate-x-[50%]">{{ file.uploadPercent.value }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template v-slot:actions>
            <div class="grid grid-cols-[auto,auto] justify-center gap-4">
                <button @click="fileManagerStore.closeUploadFileModal"
                        class="btn btn-light btn-active-light">{{$t('forms.common.close')}}</button>

                <button class="btn btn-light"
                        v-if="!fileManagerStore.selectedUploadFiles.length"
                >{{$t('forms.fileManager.pleaseChooseAFile')}}</button>
                <button class="btn btn-light"
                        v-else-if="fileManagerStore.isUploading"
                >{{$t('forms.fileManager.uploadIsInProgress')}}</button>
                <button class="btn btn-primary btn-active-primary"
                        @click="fileManagerStore.uploadFiles"
                        v-else
                >{{$t('forms.fileManager.upload')}}</button>
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
    name: "UploadFileModal",
    components: {CustomModal, SvgComponent},
    setup(){
        const fileManagerStore = useFileManagerStore();
        const test= ref(5);
        const error = ref({});

        const getFilesMethod = (e)=>{
            fileManagerStore.setUploadFiles(e.target.files);


        };

        return{
            fileManagerStore,
            error,
            getFilesMethod,
            test
        }
    }
}
</script>

<style scoped>

</style>
