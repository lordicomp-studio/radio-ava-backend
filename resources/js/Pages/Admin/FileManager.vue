<template>
    <section class="FileManager p-6">
        <div
            v-if="fileManagerStore.showFullScreenLoader"
            class="fixed top-0 right-0 w-[100%] h-[100%] z-[1001]"
        >
            <Loader class="rounded-lg" :isActive="fileManagerStore.showFullScreenLoader"/>
        </div>

        <header class="px-6 pt-6 rounded bg-white grid grid-cols-1 gap-8
                        bg-left bg-no-repeat ltr:[background-position:right]"
                style="background-image: url(/Images/FileManagerHeaderBackground.png); background-size: auto calc(100% + 10rem);">
            <div class="title grid grid-cols-[auto,auto] gap-4 justify-start">
                <div class="w-[50px] h-[50px] grid justify-center items-center
                            border-[1px] border-[#e4e6ef] border-dashed rounded-full pl-2 pt-1">
                    <SvgComponent name="fileManagerTriangle" size="2rem" color="#009EF7"/>
                </div>
                <div class="info">
                    <h2 class="text-[19px] font-medium">{{ $t('pages.FileManager') }}</h2>
                    <div class="grid grid-cols-[repeat(4,auto)] gap-4">
                        <div class="text-primary cursor-pointer inline-block
                                    transition-colors duration-150 ease-in-out hover:text-[#006dab]
                                    text-[.9rem] font-medium">{{projectData.name}}</div>

                        <div class="text-[#a1a5b7] grid grid-cols-[auto,auto] justify-start items-center gap-2 before:w-[2px]
                            before:h-[13px] before:bg-[#A1A5B7] text-[.85rem] font-medium">
                            {{ $t('tables.fileManager.totalSpace') }}: {{putUnitForSize(projectData.space.total)}}
                        </div>

                        <div class="text-[#a1a5b7] grid grid-cols-[auto,auto] justify-start items-center gap-2 before:w-[2px]
                            before:h-[13px] before:bg-[#A1A5B7] text-[.85rem] font-medium">
                            {{ $t('tables.fileManager.freeSpace') }}: {{putUnitForSize(projectData.space.free)}}
                        </div>
                    </div>
                </div>
            </div>
            <ul class="tabs grid grid-cols-[repeat(auto-fit,minmax(2rem,auto))] gap-5 justify-start">
                <li
                    @click="fileManagerStore.openedTab = 'FileManagerTab'"
                    class="cursor-pointer py-4 border-b-2 border-[rgba(0,0,0,0)] hover:border-primary"
                    :class="fileManagerStore.openedTab === 'FileManagerTab' ? '!text-primary' : '!text-[#a1a5b7]'"
                >{{ $t('tables.fileManager.files') }}</li>
                <li
                    @click="fileManagerStore.openedTab = 'FileSettingsTab'"
                    class="cursor-pointer py-4 border-b-2 border-[rgba(0,0,0,0)] hover:border-primary"
                    :class="fileManagerStore.openedTab === 'FileSettingsTab' ? '!text-primary' : '!text-[#a1a5b7]'"
                >{{ $t('tables.fileManager.settings') }}</li>
            </ul>
        </header>

        <main class="bg-white p-6 rounded mt-8">
            <component :is="fileManagerStore.openedTab" />
        </main>
    </section>
    <CreateDirectoryModal />
    <keep-alive>
        <UploadFileModal />
    </keep-alive>
    <RenameModal environment="fileManager" />
</template>

<script>
import AdminLayout from "../../Layouts/AdminLayout.vue";
import SvgComponent from "../../Components/admin/Widgets/SvgComponent.vue";
import CreateDirectoryModal from "../../Components/admin/FileManager/CreateDirectoryModal.vue";
import UploadFileModal from "../../Components/admin/FileManager/UploadFileModal.vue";
import RenameModal from "../../Components/admin/FileManager/RenameModal.vue";
import FileManagerTab from "../../Components/admin/FileManager/tabs/FileManagerTab.vue";
import FileSettingsTab from "../../Components/admin/FileManager/tabs/FileSettingsTab.vue";
import Loader from "../../Components/admin/Widgets/Loader.vue";
import {computed, onBeforeMount, onMounted, watch} from "vue";
import {useFileSettingsStore} from "../../Stores/admin/FileManager/FileSettingsStore";
import {useFileManagerStore} from "../../Stores/admin/FileManager/FileManagerStore";
import iziToast from "izitoast";
import {useHeaderStore} from "../../Stores/admin/HeaderStore";
import {putUnitForSize} from "@/scripts/PutUnitForSize";

export default {
    name: "FileManager",
    components: {
        Loader,
        FileSettingsTab, FileManagerTab, RenameModal, CreateDirectoryModal, UploadFileModal, SvgComponent},
    layout: AdminLayout,
    props: {
        results: Array,
        path: String,
        settings: Object,
        projectData: Object,
    },
    setup(props){
        const fileManagerStore = useFileManagerStore();
        const fileSettingsStore = useFileSettingsStore();
        const headerStore = useHeaderStore();


        onBeforeMount(()=>{
            fileManagerStore.path = props.path;
            fileManagerStore.setTableData(props.results);
            // below is for file settings
            fileSettingsStore.settings = props.settings;
            fileSettingsStore.extractKeyValues();
            ///
            headerStore.title = fileManagerStore.PageTitle;
        });

        return {
            fileManagerStore,
            putUnitForSize
        };
    },
}
</script>

<style scoped>

</style>
