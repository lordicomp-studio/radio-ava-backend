<template>
    <section class="mediaList bg-white rounded-lg">
        <div
            v-if="galleryStore.showFullScreenLoader"
            class="fixed top-0 right-0 w-[100%] h-[100%] z-[1001]"
        >
            <Loader class="rounded-lg" :isActive="galleryStore.showFullScreenLoader"/>
        </div>

        <header class="flex justify-between items-center p-4 pl-8 border-b-[1px]">
            <div @click="galleryStore.openUploadModal"
                class="uploadBtn btn btn-light btn-active-light rounded-full rtl:space-x-reverse space-x-[.7rem]
                    pt-[.3rem] pb-[.25rem] px-[1.3rem] cursor-pointer border-[1px]"
            >
                <SvgComponent class="transition ease-in-out duration-150" name="fi-rr-cloud-upload" size=".95rem" color="#ccc"/>
                <span class="text-sm">{{ $t('tables.gallery.uploadMedia') }}</span>
            </div>

            <div class="left rtl:space-x-reverse space-x-4">
                <div class="inline-block">
                    <label class="text-sm text-gray-500 select-none cursor-pointer">
                        {{ $t('tables.gallery.showDetails') }}
                        <input type="checkbox" v-model="galleryStore.showExtraInfo"
                               class="input-check input-check-sm m-2 inline-block">
                    </label>
                </div>
                <button class="btn btn-light-primary btn-light-active-primary"
                        @click="galleryStore.openFiltersModal">
                    <SvgComponent class="transition ease-in-out duration-150" name="fi-rr-filter" size="1.1rem" color="#ccc"/>
                    {{ $t('tables.common.filter') }}
                </button>
            </div>
        </header>

        <section class="content grid grid-cols-[repeat(auto-fill,minmax(10rem,auto))] justify-start gap-y-4 gap-x-3 p-4">
            <div
                v-for="medium in galleryStore.media.data"
                @click="$emit('clicked', medium)"
            >
                <MediumCard :medium="medium"/>
            </div>
        </section>

        <div class="pagination">
            <pagination :data="galleryStore.media" :baseLink="galleryStore.dataApiLink" ref="paginationComponent"
                        :filters="galleryStore.filters" v-on:sendData="receiveDataMethod"></pagination>
        </div>
    </section>
    <FiltersModal/>
    <KeepAlive>
        <UploadMediaModal/>
    </KeepAlive>
    <RenameModal environment="media"/>

</template>

<script>
import MediumCard from './MediumCard.vue'
import Pagination from "../Widgets/Table/Pagination.vue";
import SvgComponent from "../Widgets/SvgComponent.vue";
import FiltersModal from "./FiltersModal.vue";
import UploadMediaModal from "./UploadMediaModal.vue";
import RenameModal from "../FileManager/RenameModal.vue";
import {useGalleryStore} from "../../../Stores/admin/GalleryStore";
import Loader from "@/Components/admin/Widgets/Loader.vue";

export default {
    name: "MediaList",
    components: {Loader, RenameModal, UploadMediaModal, FiltersModal, SvgComponent, Pagination, MediumCard},
    props: {},
    emits: ['clicked'],
    setup(){
        const galleryStore = useGalleryStore();

        const receiveDataMethod = (data)=>{
            galleryStore.setMediaData(data);
        }

        return{
            galleryStore,
            receiveDataMethod,
        }
    }
}
</script>

<style scoped>

</style>
