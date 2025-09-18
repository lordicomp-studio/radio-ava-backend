<template>
    <div
        class="select selectMultiple"
        v-click-outside="closeListMethod"
    >
        <div class="selectedOption" @click="showHideListMethod">
            <h4>{{placeholder}}</h4>
            <i>
                <SvgComponent name="fi-rr-angle-down" size=".7rem" color="#777"/>
            </i>
        </div>

        <div class="options" v-if="showList">
            <div v-if="showSearch">
                <input v-model="searchText" type="text" placeholder="جستجو ..." class="input-text w-full px-3 h-9">
            </div>
            <ul v-if="showList">
                <li v-for="(item, index) in shownDataComputed" @click="selectOptionMethod(item, index)">
                    <span>{{item}}</span>
                </li>
            </ul>
        </div>

        <ul v-if="!showList">
            <li v-for="(item, index) in modelValue">
                <span>
                    <i @click.stop="removeOptionMethod(item, index)">
                        <SvgComponent name="fi-rr-cross-circle" size=".85rem" color="#A1A5B7"/>
                    </i>
                    {{item}}
                </span>
            </li>
        </ul>
    </div>
</template>

<script>
import {computed, onMounted, onUpdated, ref} from "vue";
import SvgComponent from '../../SvgComponent.vue'
import vClickOutside from "click-outside-vue3";
import i18n from '../../../../../i18n';
const t = i18n.global.t;

export default {
    name: "SelectMultiple",
    components: {SvgComponent},
    directives: {
        clickOutside: vClickOutside.directive
    },
    props: {
        optionsArray: Array,
        modelValue: Array,
        showSearch: {
            type: Boolean,
            default: true,
        },
        placeholder: {
            type: String,
            default: t('forms.common.choose'),
        },
    },
    setup(props, context){
        const selectedOptions = ref(props.modelValue);
        const showList = ref(false);
        const searchText = ref('');

        const showHideListMethod = ()=>{
            showList.value = !showList.value;
        }

        const closeListMethod = ()=>{
            showList.value = false;
        }

        const shownDataComputed = computed(()=>{
            return props.optionsArray.filter(item => item.includes(searchText.value));
        });

        onMounted(()=>{
            let editedOptionsArray = [];

            props.optionsArray.forEach((item, index)=>{
                // if the item is not in modelValue then it should be selectable. so it pushes them to editedOptionsArray.
                if (!props.modelValue.includes(item)){
                    editedOptionsArray.push(item);
                }
            })
            // it empties the optionsArray
            props.optionsArray.splice(0, props.optionsArray.length);
            // it pushes the values that inside editedOptionsArray to optionsArray.
            editedOptionsArray.forEach((item)=>{
                props.optionsArray.push(item);
            });
        });

        const selectOptionMethod = (item, index)=>{
            selectedOptions.value.push(item);
            props.optionsArray.splice(index, 1);
            showList.value = false;

            context.emit('update:modelValue', cloneData(selectedOptions.value));
        }

        const removeOptionMethod = (item, index)=>{
            selectedOptions.value.splice(index, 1);
            props.optionsArray.push(item);

            context.emit('update:modelValue', cloneData(selectedOptions.value));
        };

        return{
            selectedOptions,
            showList,
            showHideListMethod,
            selectOptionMethod,
            removeOptionMethod,
            shownDataComputed,
            searchText,
            closeListMethod,
        }
    },
}

function cloneData(json){
    return JSON.parse(JSON.stringify(json));
}
</script>

<style scoped>

</style>
