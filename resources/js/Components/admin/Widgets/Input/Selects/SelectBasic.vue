<template>
    <div
        class="select selectBasic"
        v-click-outside="closeListMethod"
    >
        <div class="selectedOption" @click="showHideListMethod">
            <h4>{{selectedValue !== null ? selectedValue : placeholder}}</h4>
            <i>
                <SvgComponent name="fi-rr-angle-down" size=".7rem" color="#777"/>
            </i>
        </div>

        <div class="options" v-if="showList">
            <div v-if="showSearch">
                <input v-model="searchText" type="text" :placeholder="`${$t('tables.common.search')}...`" class="input-text w-full px-3 h-9">
            </div>
            <ul v-if="showList">
                <li v-for="(value, key) in shownData" @click="selectOptionMethod(key, value)">
                    <span :class="{'active' : modelValue === key}">
                        {{value}}
                        <i v-if="modelValue === key">
                            <SvgComponent name="fi-rr-check" size=".8rem" color="#fff"/>
                        </i>
                    </span>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import {computed, onUpdated, ref, watch} from "vue";
import vClickOutside from "click-outside-vue3";
import SvgComponent from '../../SvgComponent.vue'
import i18n from '../../../../../i18n';
const t = i18n.global.t;

export default {
    name: "SelectBasic",
    components: {SvgComponent},
    directives: {
        clickOutside: vClickOutside.directive
    },
    props: {
        optionsArray: {
            type: Object,
        },
        modelValue: null,
        showSearch:{
            type: Boolean,
            default: true,
        },
        placeholder: {
            type: String,
            default: t('forms.common.choose'),
        },
    },
    setup(props, context){
        const showList = ref(false);
        const searchText = ref('');
        const selectedValue = ref(
            props.modelValue || props.modelValue === 0 ? props.optionsArray[props.modelValue] : null
        );

        watch(() => props.modelValue, ()=>{
            selectedValue.value = props.modelValue || props.modelValue === 0 ? props.optionsArray[props.modelValue] : null;
        });

        const showHideListMethod = ()=>{
            showList.value = !showList.value;
        }

        const closeListMethod = ()=>{
            showList.value = false;
        }

        const shownData = computed(()=>{
            return Object.fromEntries(
                Object.entries(props.optionsArray)
                    .filter(([key, value]) => value.includes(searchText.value))
            );
        });

        const selectOptionMethod = (key, value)=>{
            showList.value = false;
            selectedValue.value = value;
            context.emit('update:modelValue', key);
        }

        return{
            showList,
            showHideListMethod,
            selectOptionMethod,
            shownData,
            searchText,
            closeListMethod,
            selectedValue,
        }
    },
}
</script>

<style scoped>

</style>
