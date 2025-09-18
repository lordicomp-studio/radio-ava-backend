<template>
    <section class="tableData itemIndex bg-white p-6 rounded">
        <header class="grid grid-cols-[auto,auto] justify-between">
            <div class="right rtl:space-x-reverse space-x-4">
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
            <div class="left flex items-center">  <!-- space-x-2 space-x-reverse-->
                <InertiaLink href="/admin/artists/create" class="btn btn-primary btn-active-primary !m-0">
                    <SvgComponent class="transition ease-in-out duration-150" name="add-simple" size="1.1rem" color="#ccc"/>
                    افزودن هنرمند
                </InertiaLink>
            </div>
        </header>
        <main class="pt-6">
            <ul class="relative">
                <Loader class="rounded-lg" :isActive="store.isSearching"/>
                <li class="labels" :style="`grid-template-columns: ${store.tableLayout.gridTemplateColumns}`">
                    <input type="checkbox" class="input-check input-check-sm"
                           @click="store.selectAll" v-model="store.areAllSelected">

                    <span v-for="label in store.tableLayout.labels">{{label}}</span>
                </li>
                <li class="contents" v-for="(item,index) in store.tableData.data" :key="index"
                        :style="`grid-template-columns: ${store.tableLayout.gridTemplateColumns}`">
                    <input type="checkbox" class="input-check input-check-sm"
                           :value="item.id" v-model="store.selectedIds">

                    <span>{{ item.id }}</span>

                    <figure class="h-[50px] w-[50px] rounded-full overflow-hidden">
                        <img
                            :src="item.photo ? `/storage${item.photo.url}` : '/Images/image not found.webp'"
                            class="object-cover w-[100%] h-[100%]"
                            alt=""
                        >
                    </figure>

                    <span>{{item.name}}</span>
                    <span>{{item.country ? item.country.name : 'ناموجود'}}</span>
                    <span>{{item.description}}</span>
                    <span>{{item.birth_date}}</span>
                    <span>{{item.created_at}}</span>

                    <TableActionsButton>
                        <inertia-link :href="`/admin/${type}s/${item.id}`">{{$t('tables.common.show')}}</inertia-link>
                        <inertia-link :href="`/admin/${type}s/${item.id}/edit`">{{$t('tables.common.edit')}}</inertia-link>
                        <span @click="askDeleteAffirmation(item, false)">{{$t('tables.common.delete')}}</span>
                    </TableActionsButton>
                </li>
            </ul>
            <div class="pagination">
                <pagination :data="store.tableData" :baseLink="baseLink" ref="paginationComponent"
                            :filters="filters" v-on:sendData="receiveDataMethod"></pagination>
            </div>
        </main>
    </section>
</template>

<script>
import SvgComponent from "../../../Components/admin/Widgets/SvgComponent.vue";
import Pagination from "../../../Components/admin/Widgets/Table/Pagination.vue";
import {onBeforeMount, onMounted, ref, watch} from "vue";
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import Loader from "../../../Components/admin/Widgets/Loader.vue";
import {useArtistsStore} from "../../../Stores/admin/Artists/IndexStore";
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
    name: "ArtistsIndex",
    layout: AdminLayout,
    props:{
        data: Object,
        baseLink: String,
        type: String,
    },
    components: {TableUserItem, TableActionsButton, Loader, Pagination, SvgComponent},
    directives: {
        clickOutside: vClickOutside.directive
    },
    setup(props){
        const filters = ref({
            searchText: '',
        });
        const store = useArtistsStore();
        const headerStore = useHeaderStore();
        let currentSearchTimer = null;

        onBeforeMount(()=>{
            store.setTableData(props.data);
            store.type = props.type;

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
            axios.delete(`/admin/${props.type}s/${rowData.id}`)
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
            axios.delete(`/admin/${props.type}s/api/deleteMultiple`, {
                data: store.selectedIds
            }).then((res)=>{
                console.log(res.status)
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
            filters,
            store,
            paginationComponent, // ref to dom component
            askDeleteAffirmation,
            receiveDataMethod,
            searchMethod,
        }
    },
}
</script>

<style scoped>
    ul li{
        @apply grid /*grid-cols-[3rem,20rem,repeat(4,1fr),6rem] it is now passed as a prop*/
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

    ul li.contents:last-child{
        @apply border-none pb-4;
    }
</style>
