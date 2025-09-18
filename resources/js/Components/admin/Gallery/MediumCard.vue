<template>
    <div
        class="mediumCard shadow-lg rounded-lg w-[160px] p-[2px] relative"
        @contextmenu.prevent="galleryStore.openActionsMethod(medium)"
        :title="`
${$t('forms.common.name')}: ${medium.name}
${$t('fields.createdAt')}: ${medium.created_at}
${$t('fields.size')}: ${putUnitForSize(medium.size)}
${$t('fields.uploader')}: ${uploaderNameComputed}
        `"
    >
        <figure class="h-[90px] rounded-lg overflow-hidden">
            <img :src="`/storage${medium.url}`" alt="">
        </figure>
        <div class="grid grid-cols-[auto,auto]">
            <div class="info px-3">
                <span class="block mt-2 text-[.8rem] text-black font-normal">{{ medium.name }}</span>
                <span class="block mt-1 text-[.7rem] text-[#999] font-normal">{{ medium.created_at }}</span>
                <div class="block" v-if="galleryStore.showExtraInfo">
                    <span class="inline-block text-[.7rem] text-[#999] font-normal">{{ $t('fields.size') }}: </span>
                    <span class="inline-block mr-2 text-[.7rem] text-[#999] font-normal">{{ putUnitForSize(medium.size) }}</span>
                </div>
                <div class="block" v-if="galleryStore.showExtraInfo">
                    <span class="inline-block text-[.7rem] text-[#999] font-normal">{{ $t('fields.uploader') }}: </span>
                    <span class="inline-block mr-2 text-[.7rem] text-[#999] font-normal">{{ uploaderNameComputed }}</span>
                </div>
            </div>
        </div>
        <transition name="simpleFade">
            <div class="actions" v-if="galleryStore.openedMediumActionPanelId === medium.id" v-click-outside="clickOutsideItemActionsMethod">
                <span @click="renameMethod">{{ $t('tables.fileManager.rename') }}</span>
                <span @click="copyCutMethod('copy')">{{ $t('tables.fileManager.copy') }}</span>
                <span @click="copyCutMethod('cut')">{{ $t('tables.fileManager.move') }}</span>
                <span @click="askDeleteAffirmation">{{$t('tables.common.delete')}}</span>
            </div>
        </transition>
    </div>
</template>

<script>
import SvgComponent from "../Widgets/SvgComponent.vue";
import RenameModal from "../FileManager/RenameModal.vue";
import {computed} from "vue";
import vClickOutside from 'click-outside-vue3'
import {router} from '@inertiajs/vue3';
import {useGalleryStore} from "../../../Stores/admin/GalleryStore";
import {useFileManagerStore} from "../../../Stores/admin/FileManager/FileManagerStore";
import iziToast from "izitoast";
import {putUnitForSize} from "@/scripts/PutUnitForSize";
import i18n from '../../../i18n';
import {askAffirmation} from "@/scripts/askAffirmation";
const t = i18n.global.t;

export default {
    name: "MediumCard",
    components: {RenameModal, SvgComponent},
    props:{
        medium: Object,
        showActions: Boolean,
    },
    directives: {
        clickOutside: vClickOutside.directive
    },
    setup(props){
        const galleryStore = useGalleryStore();
        const fileManagerStore = useFileManagerStore();


        const uploaderNameComputed = computed(()=>{
            return props.medium.uploader? props.medium.uploader.name : t('tables.gallery.deleted');
        });

        const clickOutsideItemActionsMethod = (event)=> {
            galleryStore.openActionsMethod(props.medium);
            // console.log('Clicked outside. Event: ', event)
        }

        const renameMethod = ()=>{
            let result = { name: props.medium.name };

            let lastSlashIndex = props.medium.url.lastIndexOf('/');
            fileManagerStore.path = '/public' + props.medium.url.substring(0, lastSlashIndex);

            fileManagerStore.openRenameModal(result);
        }

        const copyCutMethod = (type)=>{
            let lastSlashIndex = props.medium.url.lastIndexOf('/');
            let path = galleryStore.basePath + props.medium.url.substring(0, lastSlashIndex);
            let fileName = props.medium.url.substring(lastSlashIndex);

            fileManagerStore.selectedResults.push({
                path: path + fileName,
            });

            if (type==='copy'){
                fileManagerStore.copySelected();
            }else {
                fileManagerStore.cutSelected();
            }

            router.visit('/admin/gallery/fileManager', {
                method: 'get',
                data: { path: path },
            });
        }

        // below is for deleting
        const askDeleteAffirmation = () => {
            askAffirmation(deleteRowFinalize)
        }

        function deleteRowFinalize(){
            let path = galleryStore.basePath; // this basePath is in fact the publicPath from config/constants.php

            // to use the same delete backend as fileManager we have sent an array of results. so an array with only the target medium's path.
            let selectedResults = [ { path: path + props.medium.url } ];

            axios.delete("/admin/fileManager/deleteAll", {
                data: selectedResults
            }).then((res)=>{
                if (res.status === 200){
                    galleryStore.updateMedia();
                    iziToast.success({
                        title: t('toasts.success'),
                        message: t('toasts.deletedSuccessfully'),
                    });
                }
            });
        }

        return{
            galleryStore,
            uploaderNameComputed,
            clickOutsideItemActionsMethod,
            fileManagerStore,
            renameMethod,
            copyCutMethod,
            askDeleteAffirmation,
            putUnitForSize,
        }
    }
}
</script>

<style lang="pcss" scoped>

    div.info{
        overflow: hidden;
        span{
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        div.block{
            overflow: hidden;
            white-space: nowrap;
            //text-overflow: ellipsis;
            span{
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        }
    }

</style>
