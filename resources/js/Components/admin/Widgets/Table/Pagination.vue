<template>
    <div
        v-show="!(currentData.to === currentData.total && currentData.from === 1)"
        class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6"
    >
        <div class="flex-1 flex justify-between sm:hidden">
            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"> Previous </a>
            <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"> Next </a>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    {{ $t('tables.paginationComponent.show') }}
                    <span class="font-medium">{{currentData.from}}</span>
                    {{ $t('tables.paginationComponent.to') }}
                    <span class="font-medium">{{currentData.to}}</span>
                    {{ $t('tables.paginationComponent.from') }}
                    <span class="font-medium">{{currentData.total}}</span>
                    {{ $t('tables.paginationComponent.results') }}
                </p>
            </div>
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <a v-if="currentData.prev_page_url" @click="getData(currentData.current_page - 1)" href="#" class="ltr:rotate-180 relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Next</span>
                        <!-- Heroicon name: solid/chevron-right -->
                        <SvgComponent name="solid/chevron-right" size="1.2rem" color="#a1a5b7" class="h-5 w-5"/>
                    </a>
                    <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->
                    <div v-for="link in currentData.links">
                        <a v-if="link.active" @click="getData(link.label)" href="#" aria-current="page" class="z-10 bg-indigo-50 border-indigo-500
                                text-indigo-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium"> {{link.label}} </a>
                        <a v-else-if="!link.label.includes('laquo') && !link.label.includes('raquo')" href="#"
                           @click="getData(link.label)"
                           class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50
                                relative inline-flex items-center px-4 py-2 border text-sm font-medium"> {{link.label}} </a>
                    </div>
                    <a v-if="currentData.next_page_url" @click="getData(currentData.current_page + 1)" href="#" class="ltr:rotate-180 relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Previous</span>
                        <!-- Heroicon name: solid/chevron-left -->
                        <SvgComponent name="solid/chevron-left" size="1.2rem" color="#a1a5b7" class="h-5 w-5"/>
                    </a>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
import {onMounted, ref, watch} from "vue";
import SvgComponent from '../SvgComponent.vue'
    export default {
        props:{
            data: Object,
            filters: Object,
            baseLink: String,
        },
        components:{
            SvgComponent,
        },
        setup(props, context){
            const currentData = ref({});

            onMounted(()=>{
                currentData.value = JSON.parse(JSON.stringify(props.data));
            });

            watch(() => props.data, (data)=>{
                // console.log('pagination data changed: ', data);
                currentData.value = JSON.parse(JSON.stringify(data));
            });

            const getData = (pageNumber, afterRequestMethod = null) =>{
                axios.post(props.baseLink + '?page=' + pageNumber, {
                    filters: props.filters,
                }).then(res => {
                    currentData.value = res.data;
                    context.emit('sendData', currentData.value);
                    if (afterRequestMethod){
                        afterRequestMethod();
                    }
                }).catch((error) =>{

                });
            };

            return{
                currentData,
                getData,
            }
        }
    }
</script>

<style scoped>

</style>
