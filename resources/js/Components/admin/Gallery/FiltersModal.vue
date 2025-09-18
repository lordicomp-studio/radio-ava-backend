<template>
    <CustomModal
        :is-active="galleryStore.showFiltersModal"
        @clickOutside="galleryStore.closeFiltersModal"
    >
        <template v-slot:title>
            <div class="grid grid-cols-[auto,auto] justify-between items-center">
                <strong class="text-[1.2rem] leading-[35px] capitalize">{{$t('tables.common.filter')}} {{$t('pages.Gallery')}}</strong>

                <div class="no-bg-svg-btn-blue" @click="galleryStore.closeFiltersModal">
                    <SvgComponent name="fi-rr-cross" size=".7rem" color="#aaa"/>
                </div>
            </div>
        </template>
        <template v-slot:body>
            <div class="w-[400px] text-start">
                <h4 class="text-sm font-medium mb-2 text-[#3f4254]">{{$t('forms.common.name')}}:</h4>
                <input type="text" class="input-text w-[400px]"
                       v-model="galleryStore.filters.fileName" :placeholder="`${$t('forms.common.name')}...`">
            </div>
            <div class="w-[400px] mt-4 text-start">
                <h4 class="text-sm font-medium mb-2 text-[#3f4254] lowercase">{{$t('fields.uploader')}}:</h4>
                <ItemSelector v-model="galleryStore.filters.user" searchLink="/admin/users/indexDataApi"/>
            </div>
            <div class="w-[400px] mt-4 text-start">
                <h4 class="text-sm font-medium mb-2 text-[#3f4254]">{{$t('tables.gallery.uploadTime')}}:</h4>
                <SelectBasic
                    v-model="galleryStore.filters.uploadDate"
                    :options-array="{
                        'all' : $t('tables.gallery.all'),
                        'last year' : $t('tables.gallery.lastDay'),
                        'last month' : $t('tables.gallery.lastMonth'),
                        'last day' : $t('tables.gallery.lastYear'),
                    }"
                    :showSearch="false"
                />
            </div>
            <div class="w-[400px] mt-4 text-start">
                <h4 class="text-sm font-medium mb-2 text-[#3f4254]">{{$t('tables.gallery.fileType')}}:</h4>
                <SelectBasic
                    v-model="galleryStore.filters.fileType"
                    :options-array="{
                        'all' : $t('tables.gallery.all'),
                        'photo' : $t('tables.gallery.photo'),
                        'video' : $t('tables.gallery.video'),
                    }"
                    :showSearch="false"
                />
            </div>
        </template>
        <template v-slot:actions>
            <div class="grid grid-cols-[auto,auto,auto] justify-center gap-4">
                <button @click="galleryStore.closeFiltersModal"
                        class="btn btn-light btn-active-light">{{ $t('forms.common.close') }}</button>
                <button @click="resetMethod"
                        class="btn btn-light btn-active-light-primary">{{ $t('forms.common.revert') }}</button>
                <button @click="galleryStore.updateMedia(); galleryStore.closeFiltersModal();"
                        class="btn btn-primary btn-active-primary">{{ $t('tables.common.search') }}</button>
            </div>
        </template>
    </CustomModal>
</template>

<script>
import SvgComponent from "../Widgets/SvgComponent.vue";
import CustomModal from "../Widgets/Modal/CustomModal.vue";
import SelectBasic from "../Widgets/Input/Selects/SelectBasic.vue";
import {computed, onMounted, ref, watch} from "vue";
import {useGalleryStore} from "../../../Stores/admin/GalleryStore";
import iziToast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import i18n from '../../../i18n';
import ItemSelector from "../../../Components/admin/Widgets/Input/ItemSelector.vue";
const t = i18n.global.t;

export default {
    name: "FiltersModal",
    components: {ItemSelector, SelectBasic, CustomModal, SvgComponent},
    setup(){
        const galleryStore = useGalleryStore();
        const paginationComponent = ref();

        const resetMethod = ()=>{
            galleryStore.resetFilters();
        };

        // this watch is triggered whenever the modal opens up
        watch(() => galleryStore.showFiltersModal, (value)=>{
            /*if (value){
                if (permissionsStore.edit.isEdit){

                }else {

                }
            }*/
        });

        return{
            galleryStore,
            resetMethod,
            paginationComponent,
        }
    }
}
</script>

<style scoped>

</style>
