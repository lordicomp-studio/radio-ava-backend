<template>
    <CustomModal
        :is-active="isActive"
        @clickOutside="clickOutsideMethod"
    >
        <template v-slot:title>
            <div class="grid grid-cols-[auto,auto] justify-between items-center">
                <strong class="text-[1.2rem] leading-[35px]">انتخاب فایل</strong>
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
                <FileManagerTab :isSelector="true" @selected="receiveFileMethod"/>
            </div>
        </template>
        <template v-slot:actions>
            <div class="flex justify-center gap-4">
                <button @click="closeBtnMethod"
                        class="btn btn-light btn-active-light">بستن</button>
                <button @click="removeFileMethod"
                        class="btn btn-sm btn-outline btn-outline-danger">حذف فایل</button>
            </div>
        </template>
    </CustomModal>

    <CreateDirectoryModal />
    <keep-alive>
        <UploadFileModal />
    </keep-alive>
    <RenameModal environment="fileManager" />
</template>

<script>
import SvgComponent from "../Widgets/SvgComponent.vue";
import CustomModal from "./Modal/CustomModal.vue";
import {onMounted, ref, watch} from "vue";
import MediaList from "../Gallery/MediaList.vue";
import FileManagerTab from "@/Components/admin/FileManager/tabs/FileManagerTab.vue";
import {useFileManagerStore} from "@/Stores/admin/FileManager/FileManagerStore";
import CreateDirectoryModal from "@/Components/admin/FileManager/CreateDirectoryModal.vue";
import UploadFileModal from "@/Components/admin/FileManager/UploadFileModal.vue";
import RenameModal from "@/Components/admin/FileManager/RenameModal.vue";


export default {
    name: "SelectFileModal",
    components: {
        RenameModal,
        UploadFileModal,
        CreateDirectoryModal,
        FileManagerTab, MediaList, CustomModal, SvgComponent},
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
        receiveFileMethod:{
            type: Function,
        },
        removeFileMethod:{
            type: Function,
        }
    },
    setup(props){
        const fileManagerStore = useFileManagerStore();

        onMounted(()=>{
            axios.post('/admin/fileManager/getPathResults', {
                path: fileManagerStore.path,
            }).then((res)=>{
                // fileManagerStore.path = res.data.path;
                fileManagerStore.updateTableData();
            })
        });


        return{

        }
    }
}
</script>

<style scoped>

</style>
