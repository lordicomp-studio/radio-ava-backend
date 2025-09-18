<template>
    <CustomModal
        :is-active="store.showRenameModal"
        @clickOutside="store.closeRenameModal"
    >
        <template v-slot:title>
            <div class="grid grid-cols-[auto,auto] justify-between items-center">
                <strong class="text-[1.2rem] leading-[35px] capitalize">{{$t('tables.fileManager.rename')}}</strong>

                <div class="no-bg-svg-btn-blue" @click="store.closeRenameModal">
                    <SvgComponent name="fi-rr-cross" size=".7rem" color="#aaa"/>
                </div>
            </div>
        </template>
        <template v-slot:body>
            <div class="w-[500px]">
                <h4 class="text-base font-medium mb-2 text-[#3f4254] text-start">
                    {{$t('forms.common.name')}}
                    <span class="text-danger">*</span>
                </h4>
                <input type="text" class="input-text input-text-solid w-full"
                       v-model="newName" :placeholder="`${$t('forms.common.name')}...`">
                <p v-if="error['name']" class="text-danger">{{ error['name'][0] }}</p>
            </div>
        </template>
        <template v-slot:actions>
            <div class="grid grid-cols-[auto,auto] justify-center gap-4">
                <button @click="store.closeRenameModal"
                        class="btn btn-light btn-active-light">{{$t('forms.common.close')}}</button>
                <button @click="sendDataMethod"
                        class="btn btn-primary btn-active-primary capitalize">{{$t('tables.fileManager.rename')}}</button>
            </div>
        </template>
    </CustomModal>
</template>

<script>
import SvgComponent from "../Widgets/SvgComponent.vue";
import CustomModal from "../Widgets/Modal/CustomModal.vue";
import {onMounted, ref, watch} from "vue";
import iziToast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import {useFileManagerStore} from "../../../Stores/admin/FileManager/FileManagerStore";
import {useGalleryStore} from "../../../Stores/admin/GalleryStore";
import i18n from '../../../i18n';
const t = i18n.global.t;

export default {
    name: "RenameModal",
    components: {CustomModal, SvgComponent},
    props: { environment: String },
    setup(props){
        const store = useFileManagerStore();
        const galleryStore = useGalleryStore();

        const newName = ref('');
        const error = ref({});


        // this watch is triggered whenever the modal opens up
        watch(() => store.showRenameModal, (value)=>{
            if (value){
                newName.value = store.toBeEdited.name;
            }
        });

        const sendDataMethod = ()=>{
            if (props.environment === 'fileManager'){
                store.showFullScreenLoader = true; // store here is the fileManager store
            }else {
                galleryStore.showFullScreenLoader = true;
            }

            axios.post('/admin/fileManager/rename', {
                'oldName': store.toBeEdited.name,
                'newName': newName.value,
                'path': store.path,
            }).then((res)=>{
                    if (props.environment === 'fileManager'){
                        store.showFullScreenLoader = false; // store here is the fileManager store
                    }else {
                        galleryStore.showFullScreenLoader = false;
                    }

                    if (res.status === 200){
                        iziToast.success({title: t('toasts.success'), message: t('toasts.editedSuccessfully'),});

                        newName.value = '';
                        error.value = {};
                        store.closeRenameModal();
                        if (props.environment === 'fileManager'){
                            store.updateTableData(); // store here is the fileManager store
                        }else {
                            galleryStore.updateMedia();
                        }
                    }
                })
                .catch((_error) => {
                    if (props.environment === 'fileManager'){
                        store.showFullScreenLoader = false; // store here is the fileManager store
                    }else {
                        galleryStore.showFullScreenLoader = false;
                    }

                    error.value = _error.response.data.errors;
                    iziToast.error({title: t('toasts.error'), message: t('toasts.formDataFlawed')});
                });
        };

        return{
            store,
            newName,
            error,
            sendDataMethod,
        }
    }
}
</script>

<style scoped>

</style>
