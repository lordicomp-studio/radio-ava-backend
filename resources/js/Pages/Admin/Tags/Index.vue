<template>
    <section class="permissionsIndex tableData bg-white p-6 rounded">
        <div
            v-if="store.showFullScreenLoader"
            class="fixed top-0 right-0 w-[100%] h-[100%] z-[1001]"
        >
            <Loader class="rounded-lg" :isActive="store.showFullScreenLoader" z-index="1001"/>
        </div>

        <header class="grid grid-cols-[auto,auto] justify-between">
            <div class="right space-x-4 rtl:space-x-reverse">
                <div class="relative inline-block">
                    <div class="absolute right-4 top-3 rotate-[90deg] cursor-pointer" @click="searchMethod">
                        <SvgComponent name="fi-rr-search" size="1.2rem" color="#a1a5b7"/>
                    </div>

                    <input v-model="filters.searchText" @keyup.enter="searchMethod"
                           type="text" :placeholder="$t('tables.common.search')"
                           class="input-text input-text-solid w-[250px] bg-[#f5f8FA]
                            focus:bg-[#EEF3F7] pr-[2.8rem!important] text-gray-400 text-[.9rem] font-normal">
                </div>
                <button class="btn btn-danger btn-active-danger inline-block"
                        v-if="store.selectedIds.length"
                        @click="askDeleteAffirmation({}, true)"
                > {{ $t('tables.common.deleteAll') }} ({{ store.selectedIds.length }})</button>
            </div>
            <div class="left space-x-2 space-x-reverse">
                <button class="btn btn-light-primary btn-light-active-primary"
                        @click="store.openModalCreate();">
                    <SvgComponent class="transition ease-in-out duration-150" name="add-simple" size="1.1rem" color="#ccc"/>
                    {{ $t('tables.common.add') }} {{ $t('pages.Tags') }}
                </button>
            </div>
        </header>

        <CreateModal/>

        <main class="pt-6">
            <ul class="relative">
                <Loader class="rounded-lg" :isActive="store.isSearching"/>

                <li class="labels" :style="`grid-template-columns: ${store.tableLayout.gridTemplateColumns}`">
                    <input type="checkbox" class="input-check input-check-sm"
                            @click="store.selectAll" v-model="store.areAllSelected">
                    <span v-for="label in store.tableLayout.labels">{{label}}</span>
                </li>
                <li
                    class="contents" v-for="item in store.tableData.data"
                    :style="`grid-template-columns: ${store.tableLayout.gridTemplateColumns}`"
                >
                    <input type="checkbox" class="input-check input-check-sm"
                            :value="item.id" v-model="store.selectedIds">

                    <span class="text-[#7E8299] font-normal">{{ item.id }}</span>
                    <span class="text-[#7E8299] font-normal">{{ item.name }}</span>

                    <TableUserItem :user="item.user" profilePhotoFieldName="profile_photo"/>

                    <span class="text-[#7E8299] font-normal text-[.8rem]">{{ item.created_at }}</span>

                    <TableActionsButton>
                        <span @click="store.openModalEdit(item)">{{$t('tables.common.edit')}}</span>
                        <span @click="askDeleteAffirmation(item, false)">{{$t('tables.common.delete')}}</span>
                    </TableActionsButton>
                </li>
            </ul>
            <div class="pagination">
                <pagination :data="store.tableData" :baseLink="baseLink" ref="paginationComponent"
                            :filters="filters" v-on:sendData="receiveDataMethod"/>
            </div>
        </main>
    </section>
</template>

<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import SvgComponent from "../../../Components/admin/Widgets/SvgComponent.vue";
import Pagination from "../../../Components/admin/Widgets/Table/Pagination.vue";
import CreateModal from "../../../Components/admin/Tags/CreateModal.vue";
import Loader from "../../../Components/admin/Widgets/Loader.vue";
import {useTagIndexStore} from "../../../Stores/admin/Tags/TagsIndexStore";
import {onBeforeMount, onMounted, ref, watch} from "vue";
import vClickOutside from 'click-outside-vue3'
import iziToast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import {useHeaderStore} from "../../../Stores/admin/HeaderStore";
import TableActionsButton from "../../../Components/admin/Widgets/Table/TableActionsButton.vue";
import TableUserItem from "../../../Components/admin/Widgets/Table/TableUserItem.vue";
import {askAffirmation} from "@/scripts/askAffirmation";
import i18n from '../../../i18n';
const t = i18n.global.t;

export default {
    name: "CategoryIndex",
    components: {TableUserItem, TableActionsButton, Loader, CreateModal, Pagination, SvgComponent},
    layout: AdminLayout,
    directives: {
        clickOutside: vClickOutside.directive
    },
    props:{
        tags:{
            type: Object,
        },
        baseLink:{
            type: String,
        }
    },
    setup(props){
        const showCreateModal = ref(true);
        const store = useTagIndexStore();
        const headerStore = useHeaderStore();
        const filters = ref({
            searchText: '',
        });
        let currentSearchTimer = null;

        onBeforeMount(()=>{
            store.setTableData(props.tags);
            headerStore.title = store.PageTitle;
        });

        // receives the emitted data
        const receiveDataMethod = (newData)=>{
            store.setTableData(newData);
        }

        // the search svg event. near the search input.
        const paginationComponent = ref(null)
        const searchMethod = ()=>{
            let afterRequestMethod = ()=>{
                store.isSearching = false;
            };

            // console.log(paginationComponent.value)
            paginationComponent.value.getData(1, afterRequestMethod);
        }

        watch(() => filters.value.searchText, (currentValue, oldValue) => {
            clearTimeout(currentSearchTimer);
            store.isSearching = false;

            currentSearchTimer = setTimeout(()=>{
                searchMethod();
                store.isSearching = true;
            }, 1000);
        });

        const clickOutsideActionsPanelMethod = (event)=>{
            store.openRowActionPanel(0); // closes the open action panel
        }

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

        function deleteRowFinalize(rowData){
            axios.delete(`/admin/tags/${rowData.id}`)
                .then(res => {
                    if (res.status === 200){
                        store.tableData.data = store.tableData.data.filter( item => item.id !== rowData.id );
                        store.selectedIds.splice(0, store.selectedIds.length);
                        iziToast.success({
                            title: t('toasts.success'),
                            message: t('toasts.deletedSuccessfully'),
                        });
                    }
                });
        }

        // below is for multiple delete button
        watch( ()=> store.selectedIds, (value) => {
            store.checkIfAllAreSelected();
        });

        function deleteMultipleFinalize(){
            axios.delete("/admin/tags/api/deleteMultiple", {
                data: store.selectedIds
            }).then((res)=>{
                if (res.status === 200){
                    store.updateTableData();
                    store.selectedIds.splice(0, store.selectedIds.length);
                    store.areAllSelected = false;
                    iziToast.success({
                        title: t('toasts.success'),
                        message: t('toasts.deletedSuccessfully'),
                    });
                }
            });
        }

        return{
            showCreateModal,
            store,
            receiveDataMethod,
            filters,
            searchMethod,
            paginationComponent, // ref to dom component
            askDeleteAffirmation,
            clickOutsideActionsPanelMethod,
        }
    },
}
</script>

<style scoped>
    ul li{
        @apply grid /*grid-cols-[3rem,2fr,1fr,1fr,6rem] it is now set in store */
        py-3 border-b-[1px] border-dashed items-center;
    }

    ul li.labels{
        @apply text-sm font-medium;
    }

    ul li.labels span{
        @apply text-gray-400
    }

    ul li.contents{
        @apply text-gray-500 text-sm;
    }

    span{
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }
</style>
