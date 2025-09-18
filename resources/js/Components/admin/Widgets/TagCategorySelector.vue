<template>
    <div class="bg-white rounded-lg p-4">
        <header class="flex justify-between lowercase">
            <h3 class="text-lg text-black font-medium">{{ Header }}</h3>
            <button
                class="btn btn-sm btn-light-primary btn-light-active-primary"
                @click="createButtonEvent"
            >
                <svg-component name="fi-rr-add" size="1.1rem" color="#ccc"></svg-component>
            </button>
        </header>

        <div class="mt-2">
            <div class="select selectBasic">
                <div class="selectedOption" @click="showHideListMethod">
                    <h4>{{ $t('forms.common.choose') }}</h4>
                    <i>
                        <SvgComponent name="fi-rr-angle-down" size=".7rem" color="#777"/>
                    </i>
                </div>

                <div class="options" v-if="showList">
                    <div>
                        <input v-model="searchText" type="text" :placeholder="`${$t('tables.common.search')}...`" class="input-text w-full px-3 h-9">
                    </div>
                    <ul v-if="showList" class="relative">
                        <Loader :isActive="showLoader" />

                        <li v-for="(item, index) in dataList" @click="selectOptionMethod(item, index)">
                            <span :class="{'active' : modelValue === item}">{{item.name}}</span>
                        </li>
                    </ul>
                </div>

                <ul
                    v-if="!showList"
                    class="grid gap-x-2 grid-cols-[repeat(auto-fit,minmax(5rem,auto))] justify-start"
                >
                    <li v-for="(item, index) in modelValue">
                            <span class="bg-secondary flex items-center text-xs p-2 text-[#5e6278] text-center rounded mt-2"
                                  :class="{'!bg-yellow-50' : item.isSuggested}">
                                <i class="ml-2 cursor-pointer" @click.stop="removeOptionMethod(item, index)">
                                    <svg-component name="fi-rr-cross-circle" size=".85rem" color="#A1A5B7"></svg-component>
                                </i>
                                {{ item.name }}
                            </span>
                    </li>
                </ul>
            </div>
        </div>

        <p class="text-xs text-[#a1a5b7] font-light text-center mt-4">
            {{ $t('forms.tagCategorySelector.comment', {title: Header}) }}</p>
    </div>
</template>

<script>
import SvgComponent from "@/Components/admin/Widgets/SvgComponent.vue";
import {reactive, ref, watch} from "vue";
import iziToast from "izitoast";
import Loader from "@/Components/admin/Widgets/Loader.vue";

export default {
    name: "TagCategorySelector",
    components: {Loader, SvgComponent},
    props: {
        Header: String,
        _dataList: Object,
        modelValue: Array,
        createButtonEvent: Function,
        searchLink: String,
    },
    setup(props, context){
        const showList = ref(false);
        const showLoader = ref(false);

        const showHideListMethod = ()=>{
            showList.value = !showList.value;
        }

        const dataList = ref(cloneData(props._dataList));

        const selectedOptions = props.modelValue ?
            reactive(cloneData(props.modelValue)) : reactive([]);

        const selectOptionMethod = (item, index)=>{
            if (itemIsNew(item)){
                selectedOptions.push(item);
                showList.value = false;
                context.emit('update:modelValue', cloneData(selectedOptions));
            }else {
                iziToast.info({title: 'توجه!', message: 'گزینه تکراری!'});
            }
        }

        function itemIsNew(item){
            for (const itemKey in selectedOptions) {
                if (selectedOptions[itemKey].name === item.name) return false;
            }
            return true;
        }

        const removeOptionMethod = (item, index)=>{
            selectedOptions.splice(index, 1);
            context.emit('update:modelValue', cloneData(selectedOptions));
        };

        const searchText = ref('');
        let timeout;
        watch(searchText, (value, oldValue, onCleanup)=>{
            clearTimeout(timeout);
            timeout = setTimeout(()=>{
                showLoader.value = true;

                axios.post(props.searchLink, {
                    filters:{ searchText: searchText.value }
                }).then((res)=>{
                    showLoader.value = false;

                    if (res.status === 200){
                        dataList.value = res.data.data;
                    }
                }).catch((error)=>{});
            }, 2000);
        });

        /* this fixes the problem in edit page where selectedOptions does not receive the formerData value through modelValue.
        * because when the selectedOptions variable is being set the modelValue is not still processed */
        watch(() => props.modelValue, (value, oldValue, onCleanup)=>{
            selectedOptions.splice(0, selectedOptions.length);
            for (const valueKey in props.modelValue) {
                selectedOptions.push(props.modelValue[valueKey]);
            }
        });

        return {
            showList,
            showHideListMethod,
            dataList,
            selectOptionMethod,
            removeOptionMethod,
            searchText,
            selectedOptions,
            showLoader,
        }
    },
}

function cloneData(json){
    return JSON.parse(JSON.stringify(json));
}

</script>

<style scoped>

</style>
