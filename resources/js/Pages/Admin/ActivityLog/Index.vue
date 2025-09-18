<template>
    <section class="tableData itemIndex bg-white p-6 rounded">
        <header class="grid grid-cols-[auto] justify-start">
            <div class="right space-x-reverse space-x-4">
                <div class="relative inline-block">
                    <div class="absolute right-4 top-3 rotate-[90deg] cursor-pointer" @click="searchMethod">
                        <SvgComponent name="fi-rr-search" size="1.2rem" color="#a1a5b7" />
                    </div>

                    <input v-model="filters.searchText" @keyup.enter="searchMethod"
                           type="text" :placeholder="$t('tables.common.search')"
                           class="input-text input-text-solid w-[250px] bg-[#f5f8FA]
                   focus:bg-[#EEF3F7] pr-[2.8rem!important] text-gray-400 text-[.9rem] font-normal">
                </div>
            </div>
        </header>
        <main class="pt-6">
            <ul class="relative">
                <Loader class="rounded-lg" :isActive="store.isSearching"/>
                <li class="labels" :style="`grid-template-columns: ${store.tableLayout.gridTemplateColumns}`">
                    <span v-for="label in store.tableLayout.labels">{{label}}</span>
                </li>

                <li class="contents" v-for="(item,index) in store.tableData.data" :key="index"
                        :style="`grid-template-columns: ${store.tableLayout.gridTemplateColumns}`">

                    <span>{{ item.id }}</span>

                    <TableUserItem :user="item.causer" profilePhotoFieldName="profile_photo"/>

                    <span>{{ item.event }}</span>

                    <span>{{ item.subject_type.split("\\")[2] }}</span>

                    <!-- subject type can vary so different element are needed -->
                    <TablePicturedItem v-if="['App\\Models\\Article'].includes(item.subject_type)"
                                       :pictureUrl="item.subject?.cover?.url" link="" :text="item.subject?.title" />
                    <span v-else-if="['App\\Models\\Category', 'App\\Models\\Tag'].includes(item.subject_type)">
                        {{item.subject?.name}}
                    </span>
                    <p v-else-if="item.subject_type === 'App\\Models\\Chat'" class="twoLine">
                        {{item.subject?.id}}: {{item.subject?.subject}}
                    </p>
                    <p class="twoLine" v-else-if="item.subject_type === 'App\\Models\\Comment'">
                        {{item.subject?.id}}: {{item.subject?.text}}
                    </p>
                    <TablePicturedItem v-else-if="item.subject_type === 'App\\Models\\Medium'"
                                       :pictureUrl="item.subject?.url" link="" :text="item.subject?.name" />
                    <p v-else-if="item.subject_type === 'App\\Models\\Message'" class="twoLine">
                        {{item.subject?.message}}
                    </p>
                    <TablePicturedItem v-else-if="item.subject_type === 'App\\Models\\Page'"
                                       :pictureUrl="item.subject?.photo?.url" link="" :text="item.subject?.name" />
                    <TablePicturedItem v-else-if="item.subject_type === 'App\\Models\\Product'"
                                       :pictureUrl="item.subject?.cover?.url" link="" :text="item.subject?.name" />
                    <TablePicturedItem v-else-if="item.subject_type === 'App\\Models\\ProductEditRequest'"
                                       :pictureUrl="item.subject?.cover?.url" link="" :text="item.subject?.name" />
                    <TableUserItem v-else-if="item.subject_type === 'App\\Models\\User'"
                        :user="item.subject" profilePhotoFieldName="profile_photo"/>

                    <!-- subject end -->

                    <span>{{item.created_at}}</span>

                    <TableActionsButton>
                        <inertia-link :href="`/admin/${type}s/${item.id}`">{{$t('tables.common.show')}}</inertia-link>
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
import vClickOutside from 'click-outside-vue3'
import iziToast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import {useHeaderStore} from "../../../Stores/admin/HeaderStore";
import TableActionsButton from "../../../Components/admin/Widgets/Table/TableActionsButton.vue";
import TableUserItem from "../../../Components/admin/Widgets/Table/TableUserItem.vue";
import TablePicturedItem from "@/Components/admin/Widgets/Table/TablePicturedItem.vue";
import {useActivityLogsStore} from "@/Stores/admin/ActivityLog/IndexStore";


export default {
    name: "DownloadRecordsIndex",
    layout: AdminLayout,
    props:{
        data: Object,
        baseLink: String,
        type: String,
    },
    components: {TablePicturedItem, TableUserItem, TableActionsButton, Loader, Pagination, SvgComponent},
    directives: {
        clickOutside: vClickOutside.directive
    },
    setup(props){
        const filters = ref({
            searchText: '',
        });
        const store = useActivityLogsStore();
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

        return {
            filters,
            store,
            paginationComponent, // ref to dom component
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

    .twoLine{
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
