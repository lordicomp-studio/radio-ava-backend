<template>
    <CustomModal
        :is-active="isActive"
        @clickOutside="clickOutsideMethod"
    >
        <template v-slot:title>
            <div class="grid grid-cols-[auto,auto] justify-between items-center">
                <strong class="text-[1.2rem] leading-[35px]">انتخاب رسانه</strong>
                <div class="grid items-center justify-center w-[35px] h-[35px]
                    cursor-pointer rounded transition duration-150
                    hover:bg-[#F1FAFF] group"
                    @click="closeBtnMethod"
                >
                    <SvgComponent name="fi-rr-cross" size=".7rem" color="#aaa"
                                  class="transition duration-150 group-hover:fill-[#009ef7]"/>
                </div>
            </div>
        </template>
        <template v-slot:body>
            <div class="w-[1250px] max-h-[420px] overflow-y-auto">
                <MediaList @clicked="receiveMediumMethod"/>
            </div>
        </template>
        <template v-slot:actions>
            <div class="flex justify-center gap-4">
                <button @click="closeBtnMethod"
                        class="btn btn-light btn-active-light">بستن</button>
                <button @click="removePhotoMethod"
                        class="btn btn-sm btn-outline btn-outline-danger">حذف رسانه</button>
            </div>
        </template>
    </CustomModal>
</template>

<script>
import SvgComponent from "../Widgets/SvgComponent.vue";
import CustomModal from "./Modal/CustomModal.vue";
import {onMounted, ref, watch} from "vue";
import MediaList from "../Gallery/MediaList.vue";
import {useCreateUserStore} from "../../../Stores/admin/Users/createStore";
import {useGalleryStore} from "../../../Stores/admin/GalleryStore";


export default {
    name: "SelectMediumModal",
    components: {MediaList, CustomModal, SvgComponent},
    props:{
        isActive:{
            type: Boolean,
            Default: false,
        },
        clickOutsideMethod:{
            type: Function,
        },
        closeBtnMethod:{
            type: Function,
        },
        receiveMediumMethod:{
            type: Function,
        },
        removePhotoMethod:{
            type: Function,
        }
    },
    setup(props){
        // const createUserStore = useCreateUserStore();
        const galleryStore = useGalleryStore();

        onMounted(()=>{
            axios.post('/admin/gallery/indexDataApi', {
                filters: galleryStore.filters
            }).then((res)=>{
                    galleryStore.setMediaData(res.data);
                    galleryStore.dataApiLink = '/admin/gallery/indexDataApi';
                });
        })

        // this watch is triggered whenever the modal opens up
/*        watch(() => permissionsStore.showCreateModal, (value)=>{
            if (value){
                if (permissionsStore.edit.isEdit){
                    newPermission.value.name = permissionsStore.edit.toBeEdited.name;
                    newPermission.value.parent_name = getPermissionParentName(permissionsStore.edit.toBeEdited) ?
                        getPermissionParentName(permissionsStore.edit.toBeEdited) : 'is parent';
                }else {
                    newPermission.value.name = '';
                    newPermission.value.parent_name = '';
                }
            }
        });*/


        return{

        }
    }
}
</script>

<style scoped>

</style>
