<template>
    <section class="permissionsIndex tableData bg-white p-6 rounded">
        <div
            v-if="permissionStore.showFullScreenLoader"
            class="fixed top-0 right-0 w-[100%] h-[100%] z-[1001]"
        >
            <Loader class="rounded-lg" :isActive="permissionStore.showFullScreenLoader"/>
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
                        v-if="permissionStore.selectedIds.length"
                        @click="askDeleteAffirmation({}, true)"
                > {{ $t('tables.common.deleteAll') }} ({{ permissionStore.selectedIds.length }})</button>
            </div>
            <div class="left space-x-2 space-x-reverse">
                <button class="btn btn-light-primary btn-light-active-primary"
                        @click="permissionStore.openModalCreate();">
                    <SvgComponent class="transition ease-in-out duration-150" name="add-simple" size="1.1rem" color="#ccc"/>
                    {{ $t('tables.common.add') }} {{ $t('pages.Permissions') }}
                </button>
            </div>
        </header>

        <CreateModal/>

        <main class="pt-6">
            <ul class="relative">
                <Loader class="rounded-lg" :isActive="permissionStore.isSearching"/>
                <li class="labels" :style="`grid-template-columns: ${permissionStore.tableLayout.gridTemplateColumns}`">
                    <input type="checkbox" class="input-check input-check-sm"
                            @click="permissionStore.selectAll" v-model="permissionStore.areAllSelected">

                    <span v-for="label in permissionStore.tableLayout.labels">{{label}}</span>
                </li>
                <li
                    class="contents" v-for="permission in permissionStore.tableData.data"
                    :style="`grid-template-columns: ${permissionStore.tableLayout.gridTemplateColumns}`"
                >
                    <input type="checkbox" class="input-check input-check-sm"
                            :value="permission.id" v-model="permissionStore.selectedIds">

                    <span class="text-[#7E8299] font-normal">{{ permission.id }}</span>

                    <span class="text-[#7E8299] font-normal">{{ permission.name }}</span>

                    <div class="grid grid-cols-[repeat(auto-fit,minmax(2rem,auto))] gap-2 pl-6 justify-start">
                        <span v-for="role in permission.roles"
                              class="px-2 py-1 rounded-lg text-sm font-medium"
                              :style="calculateColorMethod(role.color)"
                        >{{ role.name }}</span>
                    </div>

                    <span class="text-[#7E8299] font-normal text-[.8rem]">{{ permission.created_at }}</span>

                    <TableActionsButton>
                        <span @click="permissionStore.openModalEdit(permission)">{{$t('tables.common.edit')}}</span>
                        <span @click="askDeleteAffirmation(permission, false)">{{$t('tables.common.delete')}}</span>
                    </TableActionsButton>
                </li>
            </ul>
            <div class="pagination">
                <pagination :data="permissionStore.tableData" :baseLink="baseLink" ref="paginationComponent"
                            :filters="filters" v-on:sendData="receiveDataMethod"></pagination>
            </div>
        </main>
    </section>
</template>

<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import SvgComponent from "../../../Components/admin/Widgets/SvgComponent.vue";
import CreateModal from "../../../Components/admin/Permissions/CreateModal.vue";
import {usePermissionsStore} from "../../../Stores/admin/PermissionsStore";
import Pagination from "../../../Components/admin/Widgets/Table/Pagination.vue";
import Loader from "../../../Components/admin/Widgets/Loader.vue";
import {onBeforeMount, onMounted, ref, watch} from "vue";
import vClickOutside from 'click-outside-vue3'
import iziToast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import {useHeaderStore} from "../../../Stores/admin/HeaderStore";
import TableActionsButton from "../../../Components/admin/Widgets/Table/TableActionsButton.vue";
import {askAffirmation} from "@/scripts/askAffirmation";
import i18n from '../../../i18n';
const t = i18n.global.t;

export default {
    name: "PermissionsIndex",
    components: {TableActionsButton, Loader, Pagination, CreateModal, SvgComponent},
    layout: AdminLayout,
    directives: {
        clickOutside: vClickOutside.directive
    },
    props:{
        permissions:{
            type: Object,
        },
        baseLink:{
            type: String,
        }
    },
    setup(props){
        const showCreateModal = ref(true);
        const permissionStore = usePermissionsStore();
        const headerStore = useHeaderStore();
        const filters = ref({
            searchText: '',
        });
        let currentSearchTimer = null;

        onBeforeMount(()=>{
            permissionStore.setTableData(props.permissions);

            headerStore.title = permissionStore.PageTitle;
        });

        // receives the emitted data
        const receiveDataMethod = (newData)=>{
            permissionStore.setTableData(newData);
        }

        // the search svg event. near the search input.
        const paginationComponent = ref(null)
        const searchMethod = ()=>{
            let afterRequestMethod = ()=>{
                permissionStore.isSearching = false;
            };

            // console.log(paginationComponent.value)
            paginationComponent.value.getData(1, afterRequestMethod);
        }

        watch(() => filters.value.searchText, (currentValue, oldValue) => {
            clearTimeout(currentSearchTimer);
            permissionStore.isSearching = false;

            currentSearchTimer = setTimeout(()=>{
                searchMethod();
                permissionStore.isSearching = true;
            }, 1000);
        });

        // calculates the css text  relative to the given color for each role.
        const calculateColorMethod = (color) =>{
            // bg color has lower opacity
            // gives 11 as hexOpacity(7th and 8th digit).
            // note: the default color is #7e8299, which is set as table default in the database
            return `color:${color}; background-color:${color}11`;
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
            axios.delete(`/admin/permissions/${rowData.id}`)
                .then(res => {
                    if (res.data === 200){
                        permissionStore.tableData.data = permissionStore.tableData.data.filter( item => item.id !== rowData.id );
                        permissionStore.selectedIds.splice(0, permissionStore.selectedIds.length);
                        iziToast.success({
                            title: t('toasts.success'),
                            message: t('toasts.deletedSuccessfully'),
                        });
                    }
                });
        }

        // below is for multiple delete button
        watch( ()=> permissionStore.selectedIds, (value) => {
            permissionStore.checkIfAllAreSelected();
        });

        function deleteMultipleFinalize(){
            axios.delete("/admin/permissions/api/deleteMultiple", {
                data: permissionStore.selectedIds
            }).then((res)=>{
                if (res.data === 200){
                    permissionStore.updateTableData();
                    permissionStore.selectedIds.splice(0, permissionStore.selectedIds.length);
                    permissionStore.areAllSelected = false;
                    iziToast.success({
                        title: t('toasts.success'),
                        message: t('toasts.deletedSuccessfully'),
                    });
                }
            });
        }

        return{
            showCreateModal,
            permissionStore,
            calculateColorMethod,
            receiveDataMethod,
            filters,
            searchMethod,
            paginationComponent, // ref to dom component
            askDeleteAffirmation,
        }
    },
}
</script>

<style scoped>
    ul li{
        @apply grid /*grid-cols-[3rem,15rem,1fr,11rem,6rem] it is now set in store*/
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
