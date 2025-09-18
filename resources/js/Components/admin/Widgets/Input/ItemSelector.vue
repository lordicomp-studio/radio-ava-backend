<template>
    <div
        class="select selectBasic"
        v-click-outside="closeListMethod"
    >
        <div class="selectedOption" @click="showHideListMethod">
            <h4>{{modelValue ? modelValue.name : placeholder}}</h4>
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

                <li v-for="item in dataList" @click="selectOptionMethod(item)">
                    <span :class="{'active' : modelValue === item}">
                        {{item.name}}
                        <i v-if="modelValue === item">
                            <SvgComponent name="fi-rr-check" size=".8rem" color="#fff"/>
                        </i>
                    </span>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import SvgComponent from "@/Components/admin/Widgets/SvgComponent.vue";
import {onMounted, ref, watch} from "vue";
import Loader from "@/Components/admin/Widgets/Loader.vue";
import vClickOutside from "click-outside-vue3";
import i18n from '../../../../i18n';
const t = i18n.global.t;

export default {
    name: "ItemSelector",
    components: {Loader, SvgComponent},
    directives: {
        clickOutside: vClickOutside.directive
    },
    props:{
        modelValue: Object,
        searchLink: String,
        placeholder: {
            type: String,
            default: t('forms.common.choose'),
        }
    },
    setup(props, context){
        const showList = ref(false);
        const showLoader = ref(false);
        const dataList = ref({});

        onMounted(()=>{
            searchData();
        });

        const showHideListMethod = ()=>{
            showList.value = !showList.value;
        }

        const closeListMethod = ()=>{
            showList.value = false;
        }

        const searchText = ref('');
        let timeout;
        watch(searchText, (value, oldValue, onCleanup)=>{
            clearTimeout(timeout);
            timeout = setTimeout(()=>{
                showLoader.value = true;
                searchData();
            }, 2000);
        });

        function searchData(){
            axios.post(props.searchLink, {
                filters:{ searchText: searchText.value }
            }).then((res)=>{
                showLoader.value = false;

                if (res.status === 200){
                    dataList.value = res.data.data;
                }
            }).catch((error)=>{});
        }

        const selectOptionMethod = (item)=>{
            showList.value = false;
            context.emit('update:modelValue', item);
        }

        return {
            showHideListMethod,
            showList,
            searchText,
            dataList,
            showLoader,
            selectOptionMethod,
            closeListMethod,
        }
    }
}
</script>

<style scoped>

</style>
