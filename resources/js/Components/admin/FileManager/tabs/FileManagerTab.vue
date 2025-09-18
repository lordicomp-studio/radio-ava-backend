<template>
    <section class="filesList">
        <header class="grid grid-cols-[auto,auto] justify-between">
            <div class="right grid grid-cols-[auto,auto,auto] gap-4">
                <div class="relative inline-block">
                    <div class="absolute right-4 top-3 rotate-[90deg]">
                        <SvgComponent name="fi-rr-search" size="1.2rem" color="#a1a5b7"/>
                    </div>

                    <input type="text" :placeholder="$t('tables.common.search')" v-model="fileManagerStore.searchText"
                           class="input-text input-text-solid pr-[2.8rem!important] text-[.9rem] font-normal">
                </div>

                <button class="btn btn-danger btn-active-danger inline-block"
                        v-if="fileManagerStore.selectedResults.length"
                        @click="askDeleteAffirmation({}, true)"
                > {{ $t('tables.common.deleteAll') }} ({{ fileManagerStore.selectedResults.length }})</button>

                <div class="copyCutPaste inline-block select-none">
                    <div class="grid grid-cols-[auto,auto,auto] gap-4">
                        <div class="inline-block cursor-pointer hover:text-primary group"
                             @click="fileManagerStore.copySelected">
                            <div class="flex justify-center">
                                <SvgComponent class="group-hover:fill-primary group-hover:opacity-50"
                                              name="fi-rr-copy-alt" size="1.4rem" color="#a1a5b7"/>
                            </div>
                            <span class="text-[.6rem] opacity-50">{{ $t('tables.fileManager.copy') }}</span>
                        </div>
                        <div class="inline-block cursor-pointer hover:text-primary group"
                             @click="fileManagerStore.cutSelected">
                            <div class="flex justify-center">
                                <SvgComponent class="group-hover:fill-primary group-hover:opacity-50"
                                              name="move" size="1.4rem" color="#a1a5b7"/>
                            </div>
                            <span class="text-[.6rem] opacity-50">{{ $t('tables.fileManager.move') }}</span>
                        </div>
                        <div class="inline-block cursor-pointer hover:text-primary group relative"
                             @click="fileManagerStore.pasteClipboard">
                            <div class="flex justify-center">
                                <SvgComponent class="group-hover:fill-primary group-hover:opacity-50"
                                              name="ballot" size="1.4rem" color="#a1a5b7"/>
                            </div>
                            <span class="text-[.6rem] opacity-50">{{ $t('tables.fileManager.paste') }}</span>
                            <span class="absolute top-[-20%] right-[-20%] text-[.6rem] text-white
                                                bg-primary rounded-full p-1 px-[.4rem]"
                                  v-if="fileManagerStore.clipboard.results.length"> {{fileManagerStore.clipboard.results.length}} </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="left space-x-2 rtl:space-x-reverse">
                <button class="btn btn-light-primary btn-light-active-primary"
                        @click="fileManagerStore.openCreateDirModal">
                    <SvgComponent class="transition ease-in-out duration-150" name="add-simple" size="1.1rem" color="#ccc"/>
                    {{ $t('tables.fileManager.newFolder') }}
                </button>
                <button class="btn btn-primary btn-active-primary"
                        @click="fileManagerStore.openUploadFileModal">
                    <SvgComponent class="transition ease-in-out duration-150" name="export" size="1.1rem" color="#ccc"/>
                    {{ $t('tables.fileManager.uploadFile') }}
                </button>
            </div>
        </header>

        <div class="files">
            <div class="info grid grid-cols-[auto,auto] justify-between items-center gap-4 mt-8">
                <div class="address bg-primary-light rounded select-none">
                    <div class="path">
                        <div class="inline-block space-x-4 rtl:space-x-reverse px-2 py-1">
                            <SvgComponent class="inline-block" name="address" size="1.4rem" color="#009ef7"/>
                            <div class="inline-block rtl:space-x-reverse space-x-2">
                                <span
                                    class="inline-block text-primary text-[.85rem] font-normal hover:text-[#006dab] cursor-pointer"
                                    @click="fileManagerStore.path=''; fileManagerStore.updateTableData();"
                                >
                                    {{ $t('tables.fileManager.root') }}:
                                </span>
                                <div class="inline-block cursor-default">
                                    <SvgComponent class="inline-block rtl:rotate-180" name="addressSeparator" size="1.4rem" color="#009ef7"/>
                                </div>
                            </div>
                        </div>

                        <div v-for="(item, index) in pathSplit" class="inline-block pe-2">
                            <div v-if="item" class="inline-block rtl:space-x-reverse space-x-2">
                                    <span class="inline-block text-primary text-[.85rem] font-normal hover:text-[#006dab] cursor-pointer"
                                          @click="goToAddressPathMethod(index)"> {{item}} </span>
                                <div class="inline-block cursor-default"
                                     v-if="index !== pathSplit.length - 1">
                                    <SvgComponent class="inline-block rtl:rotate-180" name="addressSeparator" size="1.4rem" color="#009ef7"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-primary text-white py-1 px-2 rounded text-center grid items-center h-fit">
                    <span class="text-[.75rem] font-normal">{{ fileManagerStore.tableData.length }} {{ $t('tables.fileManager.items') }}</span>
                </div>
            </div>
            <table class="w-[100%] mt-2">
                <thead>
                    <tr>
                        <td>
                            <input type="checkbox" class="input-check input-check-sm"
                                   @click="fileManagerStore.selectAll" v-model="fileManagerStore.areAllSelected">
                        </td>
                        <td>{{ $t('fields.name') }}</td>
                        <td>{{ $t('fields.size') }}</td>
                        <td>{{ $t('fields.lastChange') }}</td>
                        <td>{{ $t('fields.actions') }}</td>
                    </tr>
                </thead>
                <tbody class="block max-h-[350px] overflow-y-auto">
                    <tr v-for="result in fileManagerStore.searchedTableData">
                        <td>
                            <input type="checkbox" class="input-check input-check-sm"
                                   :value="result" v-model="fileManagerStore.selectedResults">
                        </td>

                        <td v-if="result.type === 'dir'"
                            class="grid grid-cols-[auto,auto] gap-2 justify-start items-center cursor-pointer"
                            @click="openResultMethod(result)"
                        >
                            <SvgComponent class="inline-block" :name="getSvgNameForFileType(result.type)"
                                          size="1.3rem" color="#009EF7"/>
                            <span class="hover:text-primary transition duration-200 ease-in-out">{{result.name}}</span>
                        </td>
                        <td v-else>
                            <a
                                @click="selectItemMethod(result, $event)"
                                :href="`/admin/fileManager/getFile?path=${result.path}`"
                                class="grid grid-cols-[auto,auto] gap-2 justify-start items-center"
                            >
                                <SvgComponent class="inline-block" :name="getSvgNameForFileType(result.type)"
                                              size="1.3rem" color="#009EF7"/>
                                <span class="hover:text-primary cursor-pointer transition duration-200 ease-in-out">{{result.name}}</span>
                            </a>
                        </td>

                        <td>{{putUnitForSize(result.size)}}</td>

                        <td>{{result.time}}</td>

                        <td>
                            <TableActionsButton :minimal="true">
                                <a :href="`/admin/fileManager/getFile?path=${result.path}`">{{$t('tables.fileManager.download')}}</a>
                                <span @click="fileManagerStore.openRenameModal(result)">{{$t('tables.fileManager.rename')}}</span>
                                <span @click="copySingleMethod(result)">{{$t('tables.fileManager.copy')}}</span>
                                <span @click="cutSingleMethod(result)">{{$t('tables.fileManager.move')}}</span>
                                <span @click="askDeleteAffirmation(result, false)">{{$t('tables.common.delete')}}</span>
                            </TableActionsButton>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>

<script>
import SvgComponent from "../../Widgets/SvgComponent.vue";
import {computed, ref, watch} from "vue";
import iziToast from "izitoast";
import {useFileManagerStore} from "../../../../Stores/admin/FileManager/FileManagerStore";
import vClickOutside from 'click-outside-vue3'
import {putUnitForSize} from "../../../../scripts/PutUnitForSize";
import {getSvgNameForFileType} from "../../../../scripts/getSvgNameForFileType";
import TableActionsButton from "../../../admin/Widgets/Table/TableActionsButton.vue";
import {askAffirmation} from "@/scripts/askAffirmation";
import i18n from '../../../../i18n';
const t = i18n.global.t;

export default {
    name: "FileManagerTab",
    props: {
        isSelector: {
            type: Boolean,
            default: false,
        },
    },
    emits: ['selected'],
    components: {TableActionsButton, SvgComponent},
    directives: {
        clickOutside: vClickOutside.directive
    },
    setup(props, context){
        const fileManagerStore = useFileManagerStore();

        const pathSplit = computed(()=>{
            return fileManagerStore.path.split(/[\\|/]/); // splits by "/" and "\\"
        });

        const openResultMethod = (result)=>{
            axios.post('/admin/fileManager/getPathResults', {
                path: fileManagerStore.path + `/${result.name}`,
            }).then((res)=>{
                fileManagerStore.path = res.data.path;
                fileManagerStore.updateTableData();
            })
        };

        const goToAddressPathMethod = (pathWordIndex)=>{
            // console.log(pathSplit.value[pathWordIndex]);
            let path = '';
            for (let i = 0; i <= pathWordIndex; i++) {
                path += pathSplit.value[i];
                if (i !== pathWordIndex){
                    path += '/';
                }
            }
            fileManagerStore.path = path;
            fileManagerStore.updateTableData();
        }

        // this is for checking if all results are selected.
        watch( ()=> fileManagerStore.selectedResults, (value) => {
            fileManagerStore.checkIfAllAreSelected();
        });

        // this handles the searchInput and searchedResults
        watch(()=> fileManagerStore.searchText, (value)=>{
            let results = [];
            for (const key in fileManagerStore.tableData) {
                let item = fileManagerStore.tableData[key];
                if (item.name.includes(fileManagerStore.searchText))
                    results.push(item);
            }
            fileManagerStore.searchedTableData = results;
        });

        // below is for deleting
        const askDeleteAffirmation = (data, isMultiple) => {
            if (isMultiple){
                askAffirmation(deleteMultipleFinalize)
            } else {
                askAffirmation(()=>{
                    deleteRowFinalize(data);
                })
            }
        }

        function deleteRowFinalize(result){
            fileManagerStore.selectedResults = [];
            fileManagerStore.selectedResults.push(result);
            deleteMultipleFinalize();
        }

        function deleteMultipleFinalize(){
            axios.delete("/admin/fileManager/deleteAll", {
                data: fileManagerStore.selectedResults
            }).then((res)=>{
                if (res.status === 200){
                    fileManagerStore.updateTableData();
                    fileManagerStore.selectedResults.splice(0, fileManagerStore.selectedResults.length);
                    fileManagerStore.areAllSelected = false;
                    iziToast.success({
                        title: t('toasts.success'),
                        message: t('toasts.deletedSuccessfully'),
                    });
                }
            });
        }

        // other actions
        const copySingleMethod = (result)=>{
            fileManagerStore.selectedResults.push(result);
            fileManagerStore.copySelected();
        }

        const cutSingleMethod = (result)=>{
            fileManagerStore.selectedResults.push(result);
            fileManagerStore.cutSelected();
        }

        const selectItemMethod = (item, e)=>{
            // emits file data and stops the <a> tag from downloading the file
            if(props.isSelector){
                context.emit('selected', item);
                e.preventDefault();
            }
        }

        return {
            fileManagerStore,
            pathSplit,
            getSvgNameForFileType,
            putUnitForSize,
            openResultMethod,
            goToAddressPathMethod,
            askDeleteAffirmation,
            copySingleMethod,
            cutSingleMethod,
            selectItemMethod,
        };
    },
}
</script>

<style scoped>
    table tr{
        @apply grid grid-cols-[2rem,3fr,.5fr,2fr,3.5rem] gap-6
        border-b-[1px] py-4 border-dashed w-[100%];
    }

    table thead tr td{
        @apply text-sm font-medium text-gray-400;
    }

    table tbody tr td{
        @apply text-gray-900 text-sm flex items-center;
    }
</style>
